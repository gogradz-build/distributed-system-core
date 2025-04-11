<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Warehouse;

class AdminWarehouseController extends Controller
{

    public function showWarehouse()
    {
        return Inertia::render('Admin/warehouse/WarehouseManagement');
    }
    public function index()
    {
        $warehouses = Warehouse::get();
        return response()->json($warehouses);
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
            'name' => 'required|string',
        ]);

        Warehouse::create($validatedData);

        return response()->json(['message' => 'Warehouse created successfully'], 201);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
        ]);

        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $warehouse->update($validatedData);

        return response()->json(['message' => 'Warehouse updated successfully'], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = Warehouse::find($id);

        if (!$warehouse) {
            return response()->json(['message' => 'Warehouse not found'], 404);
        }

        $warehouse->delete();

        return response()->json(['message' => 'Warehouse deleted successfully'], 200);
    }
}
