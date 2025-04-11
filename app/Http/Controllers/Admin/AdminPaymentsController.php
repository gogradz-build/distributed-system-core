<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ref;
use App\Models\Sale;
use App\Models\Shop;
use Inertia\Inertia;
use App\Models\Cheque;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\PurchasePayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
    }
    public function salesPayments()
    {
        return Inertia::render('Admin/payments/CreateSalesPayment');
    }

    public function purchasePayments()
    {
        return Inertia::render('Admin/payments/CreatePurchasePayment');
    }
    public function shopsPayments() {}

    public function shopsPaymentsData(Request $request)
    {

        $id = $request['searchShop'];
        $status = $request['paymentStatus'];

        $shopQuery = Shop::with(['sales' => function ($query) use ($status) {
            if ($status == '2') {
                $query->whereRaw('total_price > received_amount');
            } else if ($status == '3') {
                $query->whereRaw('total_price <= received_amount');
            }
        }]);

        $shopPayment = $shopQuery->find($id);

        if (!$shopPayment) {
            return response()->json(['error' => 'Shop not found'], 404);
        }

        return response()->json($shopPayment);
    }

    public function getShopsPayment($id)
    {
        $sale = Sale::with('shop')->find($id);
        return Inertia::render('Admin/payments/createPayment', [
            'data' => $sale
        ]);
    }
    public function purchasePaymentsData(string $id)
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

        return Inertia::render('Admin/payments/PurchasePayment', [
            'purchase' => $formattedPurchase,
            'suppliers' => Supplier::all(),
            'warehouses' => Warehouse::all(),
            'products' => Product::all(),
        ]);
    }

    public function addNewShopPayment(Request $request, $id)
    {
        $sale = Sale::find($id);
        $shop = $sale->shop;

        $oldAmount = $sale->received_amount;
        $newPrice = $oldAmount + $request['amount'];

        if ($sale->total_price < $newPrice) {
            $newPrice = $sale->total_price;
        }

        DB::beginTransaction();
        $sale->update([
            'received_amount' => $newPrice,
            'order_status' => $request['order_status']
        ]);
        $shopCredit = $shop->credit;
        $newCredit = $shopCredit - $request['amount'];

        if ($newCredit < 0) {
            $newCredit = 0;
        }
        $shop->update([
            'credit' => $newCredit,
        ]);

        if ($request['payment_type'] == '2') {
            Cheque::create([
                'sale_id' => $id,
                'amount' => $request['amount'],
                'bank_name' => $request['bank_name'],
                'cheque_number' => $request['cheque_number'],
                'expire_date' => $request['expire_date'],
            ]);
        }

        Payment::create([
            'sale_id' => $id,
            'shop_id' => $shop->id,
            'amount' => $request['amount'],
            'payment_type' => $request['payment_type']
        ]);

        DB::commit();
        return response()->json(['message' => 'Payment saved successfully']);
        DB::rollBack();
    }

    public function addNewPurchasePayment(Request $request, $id)
    {
        $purchase = Purchase::find($id);

        $oldAmount = $purchase->received_amount;
        $payment_status = $purchase->payment_status;
        $newPrice = $oldAmount + $request['amount'];

        if ($purchase->total_price < $newPrice) {
            $newPrice = $purchase->total_price;
            $payment_status = 1;
        }

        DB::beginTransaction();
        $purchase->update([
            'received_amount' => $newPrice,
            'payment_status' => $payment_status,
            'order_status' => $request['order_status']
        ]);

        PurchasePayment::create([
            'purchase_id' => $id,
            'amount' => $request['amount'],
        ]);

        DB::commit();
        return response()->json(['message' => 'Payment saved successfully']);
        DB::rollBack();
    }

    public function getSalesReport(Request $request)
    {
        $id = $request['ref_id'];
        $status = $request['paymentStatus'];
        $start_date = $request->input('params.start_date');
        $end_date = $request->input('params.end_date');

        $refQuery = Ref::with(['sales' => function ($query) use ($status, $start_date, $end_date) {
            if ($status == '1') {
                $query->whereRaw('order_status = "1"');
            } elseif ($status == '2') {
                $query->whereRaw('order_status = "2"');
            } elseif ($status == '3') {
                $query->whereRaw('order_status = "3"');
            }elseif($status == '4'){
                $query->whereRaw('order_status = "4"');
            }

            if ($start_date) {
                $query->where('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $query->where('created_at', '<=', $end_date);
            }
        }, 'sales.shop']);

        $salesReport = $refQuery->find($id);

        if (!$salesReport) {
            return response()->json(['error' => 'No record or ref not found'], 404);
        }

        return response()->json($salesReport);
    }


    public function getCollectionReport(Request $request)
    {

        $id = $request['ref_id'];
        $status = $request['paymentStatus'];
        $start_date = $request->input('params.start_date');
        $end_date = $request->input('params.end_date');

        $refQuery = Ref::with(['sales' => function ($query) use ($status, $start_date, $end_date) {
            if ($status == '2') {
                $query->whereRaw('total_price > received_amount');
            } elseif ($status == '3') {
                $query->whereRaw('total_price <= received_amount');
            }

            if ($start_date) {
                $query->where('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $query->where('created_at', '<=', $end_date);
            }
            $query->where('order_status', '3');
        }, 'sales.shop']);

        $salesReport = $refQuery->find($id);

        if (!$salesReport) {
            return response()->json(['error' => 'No record or ref not found'], 404);
        }

        return response()->json($salesReport);
    }
}
