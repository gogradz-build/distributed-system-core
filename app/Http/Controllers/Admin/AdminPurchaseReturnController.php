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
use App\Models\PurchaseReturn;
use App\Models\WarehouseStock;
use App\Models\PurchaseReturnItem;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminPurchaseReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request) {}

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
                    'product_id' => $item->product_id,
                    'cost' => $item->product->cost,
                    'name' => $item->product->name,
                    'code' => $item->product->code,
                    'quantity' => $item->quantity - $item->return,
                    'oldQuantity' => $item->quantity - $item->return,
                    'discount' => $item->discount,
                    'paid_amount' => $item->paid_amount,
                ];
            }),
            'total_price' => $purchase->total_price,
            'received_amount' => intval($purchase->received_amount),
            'order_status' => $purchase->order_status,
            'purchase_code' => $purchase->purchase_code,
        ];

        // dd($formattedPurchase);

        return Inertia::render('Admin/perches/purchaseReturn', [
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
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer',
            'products.*.oldQuantity' => 'required|integer',
            'products.*.cost' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'products.*.paid_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric',
            'receives_total' => 'required|numeric|min:0',
            'order_status' => 'required|numeric|min:0',
            'reason' => 'required',
            'purchase_code' => 'purchase_code',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['total_price'] = abs($validatedData['total_price']);

        if (
            $validatedData['receives_total'] >= $validatedData['total_price'] && $validatedData['payment_status'] == 1
        ) {

            $validatedData['paid_amount'] =  $validatedData['total_price'];
        } else if ($validatedData['receives_total'] < $validatedData['total_price'] && ($validatedData['payment_status'] == 2
            || $validatedData['payment_status'] == 3)) {

            $validatedData['paid_amount'] =  $validatedData['receives_total'];
        } else {

            return response()->json(['error' => 'Please select Correct payment type ']);
        }

        DB::beginTransaction();
        // try {

        $validatedData['purchase_id'] = intval($id);
        $purchaseR = PurchaseReturn::create($validatedData);

        $purchaseRId = $purchaseR->id;



        foreach ($validatedData['products'] as $product) {
            $purchaseItem = PurchaseReturnItem::create([
                'purchase_return_id' => $purchaseRId,
                'product_id' => $product['product_id'],
                'discount' => $product['discount'],
                'quantity' => $product['quantity'],
                'paid_amount' => $product['paid_amount'],
            ]);

            $productID = $product['product_id'];

            $updatePurchase = PurchaseItem::where('product_id', $productID)
                ->where('purchase_id', $id)
                ->first();

            $updatePurchase->update(['return' => $product['oldQuantity'] - $product['quantity']]);

            $searchProduct = Product::where('id', $productID)->first();


            $getUpdateProduct = WarehouseStock::where('product_id', $productID)->where('warehouse_id',  $validatedData['warehouse_id'])->first();


            if ($getUpdateProduct) {
                $oldQuantity = $getUpdateProduct->quantity;
                $newQuantity = $oldQuantity - ($product['oldQuantity'] - $product['quantity']);
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
