<?php
namespace App\Http\Controllers\Admin;
use Exception;
use Carbon\Carbon;
use App\Models\Ref;
use App\Models\Sale;
use App\Models\Shop;
use Inertia\Inertia;
use App\Models\Cheque;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showSales()
    {
        return Inertia::render('Admin/sales/SalesManagement');
    }
    public function showCreateSales()
    {
        if (Auth::user()->hasRole('Ref')) {

            $auth_email = auth()->user()->email;
            $auth_ref = Ref::where('email', $auth_email)->first();

            return Inertia::render('Admin/sales/CreateSale', [
                'ref_data' => $auth_ref,
            ]);
        } else {
            return Inertia::render('Admin/sales/CreateSale');
        }
    }

    public function index()
    {
        if (Auth::user()->hasRole('Ref')) {

            $auth_email = auth()->user()->email;
            $auth_ref = Ref::where('email', $auth_email)->first();

            $sales = Sale::with([
                'ref:id,first_name,last_name,phone_number',
                'warehouse:id,name',
                'shop:id,name,contact,address,owner_name,email,telephone_number',
                'saleItems' => function ($query) {
                    $query->select('id', 'sale_id', 'product_id', 'quantity', 'discount', 'return', 'price')
                        ->with('product:id,name,code,price');
                }
            ])
                ->select('id', 'warehouse_id', 'shop_id', 'ref_id', 'received_amount', 'total_price', 'created_at', 'order_status', 'status_update_at')
                ->where('ref_id', $auth_ref->id)
                ->orderBy('id', 'desc')
                ->get();
        } else {
            $sales = Sale::with([
                'ref:id,first_name,last_name,phone_number',
                'warehouse:id,name',
                'shop:id,name,contact,address,owner_name,email,telephone_number',
                'saleItems' => function ($query) {
                    $query->select('id', 'sale_id', 'product_id', 'quantity', 'discount', 'return', 'price')
                        ->with('product:id,name,code,price');
                }
            ])
                ->select('id', 'warehouse_id', 'shop_id', 'ref_id', 'received_amount', 'total_price', 'created_at', 'order_status', 'status_update_at')
                ->orderBy('id', 'desc')
                ->get();
        }

        $salesData = $sales->map(function ($sale) {

            return [
                'sale_id' => $sale->id,
                'created_at' => $sale->created_at ? $sale->created_at->format('Y-m-d') : null,
                'total_price' => $sale->total_price,
                'received_amount' => $sale->received_amount,
                'ref_name' => $sale->ref ? $sale->ref->first_name . ' ' . $sale->ref->last_name : null,
                'ref_phone_number' => $sale->ref ? $sale->ref->phone_number : null,
                'warehouse_name' => $sale->warehouse ? $sale->warehouse->name : null,
                'order_status' => $sale->order_status,
                'status_update_at' => $sale->status_update_at ? Carbon::parse($sale->status_update_at)->diffForHumans() : null,
                'shop' => [
                    'name' => $sale->shop ? $sale->shop->name : null,
                    'contact' => $sale->shop ? $sale->shop->contact : null,
                    'address' => $sale->shop ? $sale->shop->address : null,
                    'owner_name' => $sale->shop ? $sale->shop->owner_name : null,
                    'email' => $sale->shop ? $sale->shop->email : null,
                    'telephone_number' => $sale->shop ? $sale->shop->telephone_number : null,
                ],
                'items' => $sale->saleItems->map(function ($item) {
                    return [
                        'product_name' => $item->product ? $item->product->name : null,
                        'quantity' => $item->quantity,
                        'discount' => $item->discount,
                        'price' => $item->product ? $item->product->price : null,
                        'adjustPrise' => $item->price,
                        'return' => $item->return,
                    ];
                }),
            ];
        });

        return response()->json($salesData);
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
            'todyDate' => 'required',
            'payment_type' => 'required|integer',
            'products' => 'required|array',
            'products.*.product_id' => 'required|integer',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'products.*.discounted_price' => 'required|numeric|min:0',
            'products.*.subtotal' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'received_amount' => 'required|numeric|min:0',
            'shop_id' => 'required|exists:shops,id',
            'ref_id' => 'required|exists:refs,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'credit' => 'required',
            'order_status' => 'required',
        ]);

        if ($validatedData['payment_type'] != 2) {
            if (
                $validatedData['received_amount'] >= $validatedData['total_price'] && $validatedData['payment_type'] == 1
            ) {
                $validatedData['received_amount'] = $validatedData['total_price'];
            } else if (
                $validatedData['total_price'] > $validatedData['received_amount'] &&
                $validatedData['payment_type'] == 3
            ) {
            } else {
                return response()->json(['error' => 'Please select Correct payment type ']);
            }
        }

        DB::beginTransaction();
        try {

            $sale = Sale::create($validatedData);

            $validatedData['sale_id'] = $sale->id;
            if ($validatedData['payment_type'] == 2) {
                $validatedData['amount'] = $validatedData['received_amount'];
                $validatedData['bank_name'] = $request['bank_name'];
                $validatedData['cheque_number'] = $request['cheque_number'];
                $validatedData['expire_date'] = $request['expire_date'];
                Cheque::create($validatedData);
            }
            $shop = Shop::find($validatedData['shop_id']);

            $saleBalance = $validatedData['total_price'] -  $validatedData['received_amount'];
            $newCredit = $shop->credit + $saleBalance;

            if ($newCredit > $shop->credit_limit) {
                return response()->json(['error' => "Sales cannot be completed. The shop's credit limit is below the required credit."]);
            }

            $shop->update([
                'credit' => $newCredit
            ]);

            $saleId = $sale->id;

            foreach ($validatedData['products'] as $product) {
                $salesItem = SaleItem::create([
                    'sale_id' => $saleId,
                    'product_id' => $product['product_id'],
                    'discount' => $product['discount'],
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                ]);

                $product = Product::where('id', $product['product_id'])->first();
                // $variationList = $product->variationList;

                // if ($variationList) {
                //     $variationListId = $variationList->id;
                // } else {
                //     DB::rollBack();
                //     return response()->json(['message' => 'something went wrong please try again']);
                // }

                // $getUpdateProduct = WarehouseStock::where('product_id', $product->id)
                //     ->where('variation_list_id', $variationListId)->where('warehouse_id',  $validatedData['warehouse_id'])->first();
                $getUpdateProduct = WarehouseStock::where('product_id', $product->id)->where('warehouse_id',  $validatedData['warehouse_id'])->first();

                if ($getUpdateProduct) {
                    $oldQuantity = $getUpdateProduct->quantity;
                    $newQuantity = $oldQuantity - $salesItem->quantity;
                    if ($newQuantity < 0) {
                        return response()->json(['error' => "only {$oldQuantity} products are available in the stock"]);
                    }
                    $getUpdateProduct->update(['quantity' => $newQuantity]);
                } else {
                    DB::rollBack();
                    return response()->json(['error' => 'something went wrong please try again']);
                }
            }

            DB::commit();
            return response()->json(['message' => 'Sales created successfully']);
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
        $sale = Sale::with(['shop', 'warehouse'])
            ->findOrFail($id);

        $response = [
            'id' => $sale->id,
            'warehouse_id' => $sale->warehouse_id,
            'shop_id' => $sale->shop_id,
            'ref_id' => $sale->ref_id,
            'order_status' => $sale->order_status,
            'payment_type' => $sale->payment_type,
            'total_price' => $sale->total_price,
            'received_amount' => $sale->received_amount,
            'products' => $sale->saleItems->map(function ($item) {
                return [
                    'sale_item_id' => $item->id,
                    'id' => $item->product->id,
                    'price' => ($item->price == "0.00") ? $item->product->price : $item->price,
                    'name' => $item->product->name,
                    'code' => $item->product->code,
                    'sellCount' => $item->quantity - $item->return,
                    'oldQuantity' => $item->quantity - $item->return,
                    'discount' => $item->discount,
                ];
            }),

        ];

        return Inertia::render('Admin/sales/UpdateSales', [
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
            'products.*.sale_item_id' => 'integer',
            'products.*.quantity' => 'required|integer',
            'products.*.oldQuantity' => 'integer',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.discount' => 'nullable|numeric|min:0|max:100',
            'ref_id' => 'required|integer|min:1',
            'shop_id' => 'required|integer|min:1',
            'payment_type' => 'required|integer|min:1',
            'total_price' => 'required|numeric',
            'received_amount' => 'required|numeric|min:0',
        ]);

        $validatedData['user_id'] = Auth::id();
        $totalSPrice = abs($validatedData['total_price']);
        $receivePrice =  $validatedData['received_amount'];
        if (
            $receivePrice >=  $totalSPrice && $validatedData['payment_type'] == 1
        ) {

            $validatedData['received_amount'] =  $totalSPrice;
        } else if ($validatedData['received_amount'] < $totalSPrice && ($validatedData['payment_type'] == 2
            || $validatedData['payment_type'] == 3)) {
        } else {

            return response()->json(['error' => 'Please select Correct payment type ']);
        }

        DB::beginTransaction();
        try {

            $validatedData['sale_id'] = intval($id);
            $sale = Sale::find($id);
            $oldSaleItems = $sale->saleItems->keyBy('id');
            $oldSalePayAmount = intval($sale->received_amount);
            $oldSalePrice = intval($sale->total_price);

            $sale->update($validatedData);

            $updatedProductIds = collect($validatedData['products'])->pluck('sale_item_id')->filter()->toArray();

            foreach ($oldSaleItems as $oldSaleItem) {
                if (!in_array($oldSaleItem->id, $updatedProductIds)) {
                    WarehouseStock::where('product_id', $oldSaleItem->product_id)
                        ->where('warehouse_id', $validatedData['warehouse_id'])
                        ->increment('quantity', $oldSaleItem->quantity);

                    $oldSaleItem->delete();
                }
            }

            foreach ($validatedData['products'] as $product) {
                if ($product['sale_item_id'] != 0) {
                    $saleItem = SaleItem::find($product['sale_item_id']);

                    if ($saleItem) {
                        $oldSaleQuantity = $saleItem->quantity;
                        $saleItem->update([
                            'discount' => $product['discount'],
                            'quantity' => $product['quantity'],
                            'price' => $product['price'],
                        ]);

                        $productID = $product['product_id'];
                        $getUpdateProduct = WarehouseStock::where('product_id', $productID)
                            ->where('warehouse_id', $validatedData['warehouse_id'])
                            ->first();

                        if ($getUpdateProduct) {
                            $oldQuantity = $getUpdateProduct->quantity;
                            $addOldQuantity = $oldQuantity + $oldSaleQuantity;
                            $newQuantity = $addOldQuantity - $product['quantity'];

                            if ($newQuantity < 0) {
                                DB::rollBack();
                                return response()->json(['error' => 'Warehouse has not enough product']);
                            }

                            $getUpdateProduct->update(['quantity' => $newQuantity]);
                        } else {
                            DB::rollBack();
                            return response()->json(['error' => 'Warehouse stock update failed']);
                        }
                    } else {
                        DB::rollBack();
                        return response()->json(['error' => 'SaleItem not found']);
                    }
                } else {
                    $saleItem = SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product['product_id'],
                        'discount' => $product['discount'],
                        'price' => $product['price'],
                        'quantity' => $product['quantity'],
                    ]);
                    $productID = $product['product_id'];
                    $getUpdateProduct = WarehouseStock::where('product_id', $productID)
                        ->where('warehouse_id', $validatedData['warehouse_id'])
                        ->first();

                    if ($getUpdateProduct) {
                        $oldQuantity = $getUpdateProduct->quantity;
                        $newQuantity = $oldQuantity - $product['quantity'];

                        if ($newQuantity < 0) {
                            DB::rollBack();
                            return response()->json(['error' => 'Warehouse has not enough product']);
                        }

                        $getUpdateProduct->update(['quantity' => $newQuantity]);
                    } else {
                        DB::rollBack();
                        return response()->json(['error' => 'Warehouse stock update failed']);
                    }
                }
            }

            if ($receivePrice < $totalSPrice || $oldSalePayAmount < $oldSalePrice) {
                $shop = Shop::find($validatedData['shop_id']);

                if ($oldSalePayAmount < $oldSalePrice) {
                    $oldCredit = $oldSalePrice - $oldSalePayAmount;
                } else {
                    $oldCredit = 0;
                }

                $shopCredit = intval($shop->credit);
                $removeOldValue = $shopCredit - $oldCredit;
                $newCredit = $removeOldValue + ($totalSPrice - $receivePrice);

                if ($shop->credit_limit < $newCredit) {
                    DB::rollBack();
                    return response()->json(['error' => "Sales cannot be completed. The shop's credit limit is below the required credit."]);
                }
                $shop->update([
                    'credit' => $newCredit
                ]);
            }

            DB::commit();
            return response()->json(['message' => 'Sale updated successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong please try again']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $sale = Sale::find($id);

            if (!$sale) {
                return response()->json(['error' => 'Sale not found'], 404);
            }

            foreach ($sale->saleItems as $saleItem) {
                WarehouseStock::where('product_id', $saleItem->product_id)
                    ->where('warehouse_id', $sale->warehouse_id)
                    ->increment('quantity', $saleItem->quantity);

                $saleItem->delete();
            }

            $shop = Shop::find($sale->shop_id);
            if ($shop) {
                $newCredit = $shop->credit - ($sale->total_price - $sale->received_amount);
                $shop->update(['credit' => $newCredit]);
            }

            if ($sale->payment_type == 2) {
                Cheque::where('sale_id', $sale->id)->delete();
            }

            $sale->delete();

            DB::commit();
            return response()->json(['message' => 'Sale deleted successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, please try again']);
        }
    }

    public function print(Request $request)
    {
        $sale = json_decode($request->input('sale'), true);
        // Use $sale as needed

        return Inertia::render('Admin/print/SalesInvoicePrint', [
            'sale' => $sale,
        ]);
    }

    public function shopSearch($searchKey)
    {
        $shops = Shop::where('name', 'LIKE', "%$searchKey%")->get();
        return response()->json($shops);
    }

    public function saleStatusUpdate(Request $request, $saleId)
    {
        $request->validate([
            'order_status' => 'required|integer|between:1,4'
        ]);
        $sale = Sale::findOrFail($saleId);

        $sale->update(['order_status' => $request->order_status, 'status_update_at' => $request->date_input]);
        return response()->json(['message' => "Status updated successfully of sale ID " . $saleId]);
    }
}
