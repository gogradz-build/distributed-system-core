<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Models\WarehouseStock;

class AdminAdjustmentController extends Controller
{

    public function showAdjustment()
    {
        return Inertia::render('Admin/adjustment/ProductManagement');
    }

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
        $product = Product::with([
            'warehouseStocks' => function ($query) {
                $query->select('id', 'quantity', 'product_id', 'warehouse_id');
            },
            'brand' => function ($query) {
                $query->select('id', 'name');
            }
        ])
            ->find($id);
        return Inertia::render('Admin/adjustment/UpdateQuantity', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'warehouse_stock_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'new_quantity' => 'required',
            'remark' => 'required',
            'old_quantity' => 'required',
        ]);

        $validatedData['product_id'] = $id;

        DB::beginTransaction();
        try {
            $stock = WarehouseStock::find($validatedData['warehouse_stock_id']);
            $stock->update([
                'quantity' => $validatedData['new_quantity']
            ]);
            Adjustment::create($validatedData);
            DB::commit();
            return response()->json(['message' => 'Quantity Updated successfully']);
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
        //
    }
}
