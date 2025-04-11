<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SaleItem;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showProducts()
    {
        return Inertia::render('Admin/product/ProductManagement');
    }
    public function showCreateProducts()
    {
        return Inertia::render('Admin/product/CreateProduct');
    }

    public function getProductReport(Request $request)
    {
        $start_date = $request->input('params.start_date');
        $end_date = $request->input('params.end_date');

        $query = SaleItem::query();

        $query->whereHas('sale', function ($q) use ($start_date, $end_date) {
            if ($start_date) {
                $q->orWhere('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $q->orWhere('created_at', '<=', $end_date);
            }
        });

        $sales = $query->get()->groupBy('product_id');

        $productReport = [];

        foreach ($sales as $productId => $saleItems) {
            $totalQuantitySold = $saleItems->sum('quantity');

            $product = Product::find($productId);

            if ($product) {
                $productReport[] = [
                    'name' => $product->name,
                    'code' => $product->code,
                    'quantity_sold' => $totalQuantitySold
                ];
            }
        }

        return response()->json($productReport);
    }




    public function index()
    {
        $products = Product::with([
            'warehouseStocks' => function ($query) {
                $query->select('id', 'quantity', 'product_id');
            },
            'brand' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->get();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'code' => 'required|string|unique:products,code',
            'cost' => 'required',
            'price' => 'required',
            'name' => 'required',
            'brand_id' => 'required',
            'purchase_show' => 'required',
        ]);

        DB::beginTransaction();

        try {

            $product = Product::create($validatedData);
            $validatedData['product_id'] = $product->id;

            if ($validatedData['purchase_show'] !== "1") {
                DB::commit();
                return response()->json(['message' => 'Product created successfully']);
            }

            $validatedData['quantity'] = $request->quantity;
            $validatedData['received_amount'] = $request->paid_amount;
            $validatedData['discount'] = 0;
            $validatedData['supplier_id'] = $request->supplier_id;
            $validatedData['warehouse_id'] = $request->warehouse_id;
            $validatedData['order_status'] = (int) $request->order_status;
            $validatedData['purchase_code'] = $request->purchase_code;
            $validatedData['payment_status'] = (int) $request->payment_status;
            $validatedData['user_id'] = Auth::id();

            if (
                ($validatedData['quantity'] * $validatedData['cost']) <= $validatedData['received_amount'] && $validatedData['payment_status'] == 1
            ) {

                $validatedData['received_amount'] =   ($validatedData['quantity'] * $validatedData['cost']);
            } else if (
                $validatedData['received_amount'] < ($validatedData['quantity'] * $validatedData['cost']) &&
                $validatedData['payment_status'] == 3
            ) {
            } else {

                return response()->json(['error' => 'Please select Correct payment type ']);
            }

            $validatedData['total_price'] =   ($validatedData['quantity'] * $validatedData['cost']);
            $validatedData['paid_amount'] =   ($validatedData['quantity'] * $validatedData['cost']);
            $purchase = Purchase::create($validatedData);
            $validatedData['purchase_id'] = $purchase->id;

            PurchasePayment::create([
                'purchase_id' => $purchase->id,
                'amount' => $request['paid_amount'],
            ]);

            $purchaseItem = PurchaseItem::create($validatedData);

            $getUpdateProduct = WarehouseStock::where('product_id', $validatedData['product_id'])->where('warehouse_id',  $validatedData['warehouse_id'])->exists();

            if (!$getUpdateProduct) {
                WarehouseStock::create($validatedData);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'Product already exist on the warehouse']);
            }

            DB::commit();
            return response()->json(['message' => 'Product created successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'something went wrong please try again']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return Inertia::render('Admin/product/UpdateProduct', [
            'product' => $product
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|integer',
            'code' => 'required|string',
            'cost' => 'required',
            'price' => 'required',
            'name' => 'required',
            'brand_id' => 'required',
        ]);
        try {
            $product = Product::find($id);
            $product->update($validatedData);
            return response()->json(['message' => 'Product updated successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'something went wrong please try again']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $productQuantity = $product->warehouseStocks->sum('quantity');

        if ($productQuantity > 0) {
            return response()->json(['warning' => "The stock has $productQuantity items."]);
        }
        $product->delete();
        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
