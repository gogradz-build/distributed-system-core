<?php

namespace App\Http\Controllers;

use App\Models\Ref;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use App\Models\WarehouseStock;

class ExcelExportController extends Controller
{
    public function index()
    {
        return view('export-excel');
    }

    public function getStockReport()
    {
        $stock = WarehouseStock::with('product:id,name,code,price', 'warehouse:id,name')->get();
        return view('stock-report-excel', compact('stock'));
    }

    public function getSalesReport(Request $request)
    {

        $id = $request->query('ref_id');
        $ref_name = Ref::select(['first_name','last_name'])->findOrFail($id);
        $status = $request->query('status_id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

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
        return view('sales-by-ref-report-excel', compact('salesReport', 'ref_name', 'start_date', 'end_date'));
    }

    public function getCollectionReport(Request $request)
    {

        $id = $request->query('ref_id');
        $refName = Ref::select(['first_name', 'last_name'])->findOrFail($id);
        $status = $request->query('status_id');
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $refQuery = Ref::with(['sales' => function ($query) use ($status, $start_date, $end_date) {
            if ($status == '2' || $status == '3') {
                $query->whereRaw('total_price > received_amount');
            } elseif ($status == '4') {
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

        return view('collection-by-ref-report-excel', compact('salesReport','refName', 'start_date', 'end_date'));
    }

    public function getProductReport(Request $request)
    {
        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        $query = SaleItem::query();

        $query->whereHas('sale', function ($q) use ($start_date, $end_date) {
            if ($start_date) {
                $q->where('created_at', '>=', $start_date);
            }
            if ($end_date) {
                $q->where('created_at', '<=', $end_date);
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
                    'quantity_sold' => $totalQuantitySold,
                ];
            }
        }

        return view('product-sales-report-excel', compact('productReport'));
    }
}
