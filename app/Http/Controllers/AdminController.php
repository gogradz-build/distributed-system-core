<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Ref;
use App\Models\Sale;
use App\Models\Shop;
use App\Models\Supplier;
use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Month;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $productCount = Product::count();
        $shopCount = Shop::count();
        $refCount = Ref::count();
        $supplierCount = Supplier::count();

        if ($user != null) {
            $roll = $user->getRoleNames();
        } else {
            return redirect()->back();
        }
        if ($roll[0] == 'Super Admin' || $roll[0] == 'Ref') {
            $sales = $this->getRefSaleData();
            $pradeep = array_fill(0, 12, 0);
            $rafi = array_fill(0, 12, 0);
            $susantha = array_fill(0, 12, 0);

            // id->1 = pradeep
            // id->2 = Rafi
            //id->3 = Susantha

            foreach ($sales as $sale) {
                $monthIndex = $sale->month - 1;
                if ($sale->ref_id == 1) {
                    $pradeep[$monthIndex] = $sale->total_sales;
                } elseif ($sale->ref_id == 2) {
                    $rafi[$monthIndex] = $sale->total_sales;
                } elseif ($sale->ref_id == 3) {
                    $susantha[$monthIndex] = $sale->total_sales;
                }
            }

            return Inertia::render('Admin/Dashboard', [
                'productCount' => $productCount,
                'shopCount' => $shopCount,
                'refCount' => $refCount,
                'supplierCount' => $supplierCount,
                'pradeep' => $pradeep,
                'rafi' => $rafi,
                'susantha' => $susantha,
            ]);
        } else {
            return redirect()->back();
        }
    }

    private function getRefSaleData()
    {

        $currentYear = Carbon::now()->year;
        $sales = Sale::with('ref') // Load ref relation
            ->selectRaw('ref_id, MONTH(created_at) as month, COUNT(*) as total_sales')
            ->whereYear('created_at', $currentYear)
            ->groupByRaw('ref_id, MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();
        return $sales;
    }

    public function getUserRole(Request $request)
    {
        $role = Auth::user()->getRoleNames();


        if ($role[0] !== null) {
            // dd();
            return response()->json([
                'role' => $role[0]
            ]);
        } else {
            return response()->json([
                'role' => 'Admin'
            ]);
        }
    }
}
