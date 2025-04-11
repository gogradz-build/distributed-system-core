<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class AdminSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showSupplier()
    {
        return Inertia::render('Admin/suppllier/SupplierManagement');
    }
    public function showCreateSupplier()
    {
        return Inertia::render('Admin/suppllier/SupplierCreate');
    }
    public function index()
    {
        $suppliers = Supplier::get();
        return response()->json($suppliers);
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'nic_number' => 'required|string',
            'register_number' => 'required'
        ]);

        Supplier::create($validatedData);

        return response()->json(['message' => 'Supplier created successfully'], 201);
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
    public function edit($id)
    {

        $supplier = Supplier::find($id);

        return Inertia::render('Admin/suppllier/UpdateSupplier', [
            'supplier' => $supplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'nic_number' => 'required|string',
            'register_number' => 'required'
        ]);
        $supplier = Supplier::find($id);
        $supplier->update($validatedData);

        return response()->json(['message' => 'Supplier updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $warehouse = Supplier::find($id);

        if (!$warehouse) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $warehouse->delete();

        return response()->json(['message' => 'Supplier deleted successfully'], 200);
    }
}
