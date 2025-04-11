<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationData;

class AdminPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showPerches()
    {
        return Inertia::render('Admin/perches/PerchesManagement');
    }

    public function showCreatePerches()
    {
        return Inertia::render('Admin/perches/CreatePerches');
    }

    public function showPerchesReturn()
    {
        return Inertia::render('Admin/perches/purchaseReturn');
    }

    public function search($searchKey)
    {

        $products = Product::where('id', $searchKey)
            ->orWhere('name', 'like', "%{$searchKey}%")
            ->get();

        if ($products->isEmpty()) {
            return response()->json(['error' => 'No products found'], 404);
        }

        return response()->json($products);
    }

    public function searchByWarehouse($searchKey, $warehouseId)
    {
        $warehouseId = intval($warehouseId);
        $productsWithQuantity = Product::join('warehouse_stocks', 'products.id', '=', 'warehouse_stocks.product_id')
            ->where('warehouse_stocks.warehouse_id', $warehouseId) // Filter by warehouse ID
            ->where(function ($query) use ($searchKey) { // Search by name or code
                $query->where('products.name', 'like', '%' . $searchKey . '%')
                    ->orWhere('products.code', 'like', '%' . $searchKey . '%');
            })
            ->select('products.*', 'warehouse_stocks.quantity', 'warehouse_stocks.warehouse_id') // Select product details and stock fields
            ->get();

        return response()->json($productsWithQuantity);
    }

    public function index()
    {
        $purchases = Purchase::with([
            'supplier' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            },
            'warehouse' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->select('*')
            ->get();
        return response()->json($purchases);
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
            'supplier_id' => 'required|integer|min:1',
            'warehouse_id' => 'required|integer|min:1',
            'payment_status' => 'required|integer|min:1',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.cost' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'products.*.paid_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'purchase_code' => 'required',
            'received_amount' => 'required|numeric|min:0',
            'order_status' => 'required|numeric|min:0',
        ]);

        $validatedData['user_id'] = Auth::id();

        if (
            $validatedData['received_amount'] >= $validatedData['total_price'] && $validatedData['payment_status'] == 1
        ) {
            $validatedData['received_amount'] =  $validatedData['total_price'];
        } else if (
            $validatedData['received_amount'] < $validatedData['total_price'] &&
            $validatedData['payment_status'] == 3
        ) {
        } else {

            return response()->json(['error' => 'Please select Correct payment type ']);
        }

        DB::beginTransaction();
        try {

            $purchase = Purchase::create($validatedData);
            $purchaseId = $purchase->id;

            PurchasePayment::create([
                'purchase_id' => $purchase->id,
                'amount' => $request['received_amount'],
            ]);


            foreach ($validatedData['products'] as $product) {
                $productID =  $product['product_id'];
                $productQuantity =  $product['quantity'];
                $purchaseItem = PurchaseItem::create([
                    'purchase_id' => $purchaseId,
                    'product_id' => $productID,
                    'discount' => $product['discount'],
                    'quantity' => $productQuantity,
                    'paid_amount' => $product['paid_amount'],
                ]);

                $product = Product::where('id', $productID)->first();

                // $variationList = $product->variationList;

                // if ($variationList) {
                //     $variationListId = $variationList->id;
                // } else {
                //     DB::rollBack();
                //     return response()->json(['error' => 'something went wrong please try again']);
                // }

                $getUpdateProduct = WarehouseStock::where('product_id', $product->id)->where('warehouse_id',  $validatedData['warehouse_id'])->first();
                if ($getUpdateProduct) {
                    $oldQuantity = $getUpdateProduct->quantity;
                    $newQuantity = $oldQuantity + $purchaseItem->quantity;
                    $getUpdateProduct->update(['quantity' => $newQuantity]);
                } else {
                    $warehouseStock = WarehouseStock::create([
                        'warehouse_id' => $validatedData['warehouse_id'],
                        'product_id' => $productID,
                        'quantity' => $productQuantity,
                    ]);
                }
            }
            DB::commit();
            return response()->json(['message' => 'purchase created successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'something went wrong please try again']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(string $id)
    {
        $purchase = Purchase::with(['purchaseItems.product.variationList', 'supplier', 'warehouse'])
            ->find($id);

        if (!$purchase) {
            return response()->json(['error' => 'Purchase not found'], 404);
        }

        $formattedPurchase = [
            'id' => $purchase->id,
            'supplier_id' => $purchase->supplier_id,
            'warehouse_id' => $purchase->warehouse_id,
            'payment_status' => $purchase->payment_status,
            'products' => $purchase->purchaseItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'cost' => $item->product->cost,
                    'name' => $item->product->name,
                    'code' => $item->product->code,
                    'quantity' => $item->quantity - $item->return,
                    'discount' => $item->discount,
                    'paid_amount' => $item->paid_amount,
                ];
            }),
            'total_price' => $purchase->total_price,
            'received_amount' => intval($purchase->received_amount),
            'order_status' => $purchase->order_status,
            'purchase_code' => $purchase->purchase_code,
        ];

        return Inertia::render('Admin/perches/UpdatePurchase', [
            'purchase' => $formattedPurchase,
            'suppliers' => Supplier::all(),
            'warehouses' => Warehouse::all(),
            'products' => Product::all(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'supplier_id' => 'required|integer|min:1',
            'warehouse_id' => 'required|integer|min:1',
            'payment_status' => 'required|integer|min:1',
            'products' => 'required|array',
            'products.*.purchase_item_id' => 'required|integer',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer',
            'products.*.cost' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'products.*.paid_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric',
            'purchase_code' => 'required',
            'received_amount' => 'required|numeric|min:0',
            'order_status' => 'required|numeric|min:0',
        ]);

        $validatedData['user_id'] = Auth::id();

        if (
            $validatedData['received_amount'] >= $validatedData['total_price'] && $validatedData['payment_status'] == 1
        ) {

            $validatedData['received_amount'] =  $validatedData['total_price'];
        } else if ($validatedData['received_amount'] < $validatedData['total_price'] && ($validatedData['payment_status'] == 2
            || $validatedData['payment_status'] == 3)) {
        } else {

            return response()->json(['error' => 'Please select Correct payment type ']);
        }

        DB::beginTransaction();
        // try {

        $purchase = Purchase::find($id);
        $purchase->update($validatedData);

        foreach ($validatedData['products'] as $product) {

            $productID = $product['product_id'];

            $purchaseItem = PurchaseItem::find($product['purchase_item_id']);

            $oldPurchaseItemQuantity = $purchaseItem->quantity;

            $purchaseItem->update([
                'discount' => $product['discount'],
                'quantity' => $product['quantity'],
                'paid_amount' => $product['paid_amount'],
            ]);


            $getUpdateProduct = WarehouseStock::where('product_id', $productID)->where('warehouse_id',  $validatedData['warehouse_id'])->first();

            if ($getUpdateProduct) {
                $oldQuantity = $getUpdateProduct->quantity;
                $removeOld = $oldQuantity - $oldPurchaseItemQuantity;
                $newQuantity = $removeOld + $product['quantity'];
                if ($newQuantity < 0) {
                    DB::rollBack();
                    return response()->json(['error' => 'Warehouse has not enough  product']);
                }


                $getUpdateProduct->update(['quantity' => $newQuantity]);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'something went wrong please try again']);
            }
        }
        DB::commit();
        return response()->json(['message' => 'purchase return created successfully']);
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['error' => 'something went wrong please try again']);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
