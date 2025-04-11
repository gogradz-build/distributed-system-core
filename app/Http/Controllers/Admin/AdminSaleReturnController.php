<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Ref;
use App\Models\Sale;
use App\Models\Shop;
use Inertia\Inertia;
use App\Models\SaleItem;
use App\Models\SaleReturn;
use Illuminate\Http\Request;
use App\Models\SaleReturnItem;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminSaleReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showSaleReturns()
    {
        return Inertia::render('Admin/sales/SalesReturnManagement');
    }

    public function index()
    {
        if (Auth::user()->hasRole('Ref')) {

            $auth_email = auth()->user()->email;
            $auth_ref = Ref::where('email', $auth_email)->first();

            $saleReturns = SaleReturn::with([
                'sale:id,total_price',
                'ref:id,first_name,last_name,phone_number',
                'shop:id,name,contact,address,owner_name,email,telephone_number',
                'saleItems' => function ($query) {
                    $query->select('id', 'sale_return_id', 'product_id', 'quantity', 'discount')
                        ->with('product:id,name,code,price');
                }
            ])
                ->select('id', 'shop_id', 'ref_id', 'sale_id', 'total_price', 'payment_status', 'reason', 'created_at')
                ->where('ref_id', $auth_ref->id)
                ->get();
        } else {

            $saleReturns = SaleReturn::with([
                'sale:id,total_price',
                'ref:id,first_name,last_name,phone_number',
                'shop:id,name,contact,address,owner_name,email,telephone_number',
                'saleItems' => function ($query) {
                    $query->select('id', 'sale_return_id', 'product_id', 'quantity', 'discount')
                        ->with('product:id,name,code,price');
                }
            ])
                ->select('id', 'shop_id', 'ref_id', 'sale_id', 'total_price', 'payment_status', 'reason', 'created_at')
                ->get();
        }

        $salesReturnData = $saleReturns->map(function ($saleReturn) {
            return [
                'sale_return_id' => $saleReturn->id,
                'sale_id' => $saleReturn->sale ? $saleReturn->sale->id : null,
                'total_price' => $saleReturn->sale ? $saleReturn->sale->total_price : null,
                'created_at' => $saleReturn->created_at ? $saleReturn->created_at->format('Y-m-d') : null,
                'return_total_price' => $saleReturn->total_price,
                'payment_status' => $saleReturn->payment_status,
                'reason' => $saleReturn->reason,
                'ref_name' => $saleReturn->ref ? $saleReturn->ref->first_name . ' ' . $saleReturn->ref->last_name : null,
                'ref_phone_number' => $saleReturn->ref ? $saleReturn->ref->phone_number : null,
                'shop_name' => $saleReturn->shop ? $saleReturn->shop->name : null,
                'items' => $saleReturn->saleItems->map(function ($item) {
                    return [
                        'product_name' => $item->product ? $item->product->name : null,
                        'quantity' => $item->quantity,
                        'discount' => $item->discount,
                        'price' => $item->product ? $item->product->price : null,
                        'return_quantity' => $item->return,
                    ];
                }),
            ];
        });

        return response()->json($salesReturnData);
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
        //
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
        $sale = Sale::with(['shop', 'warehouse'])
            ->findOrFail($id);

        $saleReturn = SaleReturn::where('sale_id', $id)->first();

        $response = [
            'id' => $sale->id,
            'warehouse_id' => $sale->warehouse_id,
            'shop_id' => $sale->shop_id,
            'ref_id' => $sale->ref_id,
            'total_price' => $sale->total_price,
            'receives_total' => $sale->total_price,
            'reason' => $saleReturn ? $saleReturn->reason : null,
            'payment_status' => $saleReturn ? $saleReturn->payment_status : null,
            'products' => $sale->saleItems->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'price' => ($item->price == "0.00") ? $item->product->price : $item->price,
                    'name' => $item->product->name,
                    'code' => $item->product->code,
                    'sellCount' => $item->quantity,
                    'old_return_quantity' => $item->return,
                    'return_quantity' => 0,
                    'discount' => $item->discount,
                ];
            }),

        ];

        return Inertia::render('Admin/sales/SalesReturn', [
            'sale' => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'warehouse_id' => 'required|integer|min:1',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.return_quantity' => 'required|integer',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'ref_id' => 'required|integer|min:1',
            'shop_id' => 'required|integer|min:1',
            'payment_status' => 'required',
            'total_price' => 'required|numeric|regex:/^-?\d+(\.\d{1,2})?$/',
            'reason' => 'required',
            'received_amount' => 'required',
        ]);

        $validatedData['user_id'] = Auth::id();
        DB::beginTransaction();

        $saleReturn = SaleReturn::where('sale_id', $id)->first();
        $sale = Sale::find($id);

        if ($saleReturn) {
            if ($validatedData['payment_status'] == 3) {
                $shop = Shop::find($validatedData['shop_id']);

                $allCredit = $saleReturn->by_credit + $validatedData['total_price'];
                $credit = $shop->credit;
                $newCredit = $credit - $allCredit;

                if ($newCredit < 0) {
                    return response()->json(['error' => "Shop's credit amount is Rs:" . $credit . ". can not complete the return by credit"]);
                }

                $shop->update([
                    'credit' => $newCredit
                ]);
            }

            $saleTotal = $sale->total_price;
            $newSaleTotal =  $saleTotal - $validatedData['total_price'];
            $sale->update([
                'total_price' => $newSaleTotal
            ]);

            $validatedData['total_price'] += $saleReturn->total_price;
            $saleReturn->update($validatedData);
            $saleReturnId  = $saleReturn->id;

            foreach ($validatedData['products'] as $product) {
                $productID = $product['product_id'];
                $quantity = $product['return_quantity'];

                $existingSaleItem = SaleReturnItem::where('sale_return_id', $saleReturnId)
                    ->where('product_id', $productID)
                    ->first();

                if ($existingSaleItem) {

                    $oldQuantity = $existingSaleItem->quantity;
                    $stockQuantity = $quantity - $oldQuantity;
                    $existingSaleItem->update([
                        'discount' => $product['discount'],
                        'quantity' => $quantity,
                    ]);
                } else {
                    SaleReturnItem::create([
                        'sale_return_id' => $saleReturnId,
                        'product_id' => $productID,
                        'discount' => $product['discount'],
                        'quantity' => $quantity,
                    ]);
                    $stockQuantity = $quantity;
                }

                $updateSale = SaleItem::where('product_id', $productID)
                    ->where('sale_id', $id)
                    ->first();

                if ($updateSale->quantity < $quantity) {
                    return response()->json(['error' => 'Quantity exceeded' + `$productID`]);
                } else {
                    $updateSale->update(['return' => $quantity]);
                }

                $stockUpdate = WarehouseStock::where('product_id', $productID)
                    ->where('warehouse_id', $validatedData['warehouse_id'])
                    ->first();

                if ($stockUpdate) {
                    if ($stockUpdate->quantity + $stockQuantity < 0) {
                        return response()->json(['error' => `Something went wrong, please check the quantity of product . $stockUpdate->product_id . `]);
                    }
                    $stockUpdate->update(['quantity' => $stockUpdate->quantity + $stockQuantity]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'Something went wrong, please try again.']);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Sale return updated successfully']);
        }

        if ($validatedData['payment_status'] == 3) {
            $shop = Shop::find($validatedData['shop_id']);

            $credit = $shop->credit;
            $newCredit = $credit - $validatedData['total_price'];

            if ($newCredit < 0) {
                return response()->json(['error' => "Shop's credit amount is Rs:" . $credit . ". can not complete the return by credit"]);
            }

            $shop->update([
                'credit' => $newCredit
            ]);
        }

        $saleTotal = $sale->total_price;
        $newSaleTotal =  $saleTotal - $validatedData['total_price'];
        $sale->update([
            'total_price' => $newSaleTotal
        ]);

        $validatedData['sale_id'] = intval($id);
        $saleR = SaleReturn::create($validatedData);
        $saleRId = $saleR->id;

        foreach ($validatedData['products'] as $product) {

            $productID = $product['product_id'];
            $quantity = $product['return_quantity'];

            $saleItem = SaleReturnItem::create([
                'sale_return_id' => $saleRId,
                'product_id' => $productID,
                'discount' => $product['discount'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ]);


            $updateSale = SaleItem::where('product_id', $productID)
                ->where('sale_id', $id)
                ->first();


            $updateSale->update(['return' => $quantity]);

            $stockUpdate = WarehouseStock::where('product_id', $productID)->where('warehouse_id',  $validatedData['warehouse_id'])->first();


            if ($stockUpdate) {
                $oldQuantity = $stockUpdate->quantity;
                $newQuantity = $oldQuantity + $quantity;
                $stockUpdate->update(['quantity' => $newQuantity]);
            } else {
                DB::rollBack();
                return response()->json(['error' => 'something went wrong please try again']);
            }
        }

        DB::commit();
        return response()->json(['message' => 'sale return created successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function modalSalesEdit(string $id)
    {

        $sale = Sale::with(['shop', 'warehouse'])
            ->findOrFail($id);

        $saleReturn = SaleReturn::where('sale_id', $id)->first();

        $response = [
            'id' => $sale->id,
            'warehouse_id' => $sale->warehouse_id,
            'shop_id' => $sale->shop_id,
            'ref_id' => $sale->ref_id,
            'total_price' => $sale->total_price,
            'receives_total' => $sale->total_price,
            'reason' => $saleReturn ? $saleReturn->reason : null,
            'payment_status' => $saleReturn ? $saleReturn->payment_status : null,
            'products' => $sale->saleItems->map(function ($item) {
                return [
                    'id' => $item->product->id,
                    'price' => ($item->price == "0.00") ? $item->product->price : $item->price,
                    'name' => $item->product->name,
                    'code' => $item->product->code,
                    'sellCount' => $item->quantity,
                    'old_return_quantity' => $item->return,
                    'return_quantity' => 0,
                    'discount' => $item->discount,
                ];
            }),

        ];
        return response()->json(['sale' => $response]);
    }
}
