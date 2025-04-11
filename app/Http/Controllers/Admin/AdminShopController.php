<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Shop;

class AdminShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showShop()
    {
        return Inertia::render('Admin/shop/ShopManagement');
    }
    public function showCreateShop()
    {
        return Inertia::render('Admin/shop/CreateShop');
    }
    public function index()
    {
        $shop = Shop::get();
        return response()->json($shop);
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
            'owner_name' => 'required|string',
            'contact' => 'required|string',
            'telephone_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'ref_id' => 'required',
            'credit_limit' => 'required',
        ]);

        Shop::create($validatedData);

        return response()->json(['message' => 'Shop created successfully'], 201);
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
        $shop = Shop::find($id);
        return Inertia::render('Admin/shop/UpdateShop', [
            'shop' => $shop
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            'owner_name' => 'required|string',
            'contact' => 'required|string',
            'telephone_number' => 'required|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'ref_id' => 'required',
            'credit_limit' => 'required',
        ]);

        $shop = Shop::find($id);
        $shop->update($validatedData);
        return response()->json(['message' => 'Shop updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = Shop::find($id);

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        $shop->delete();

        return response()->json(['message' => 'Shop deleted successfully'], 200);
    }
}
