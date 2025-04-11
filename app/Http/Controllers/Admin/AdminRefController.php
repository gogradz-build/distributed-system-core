<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Ref;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminRefController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showRef()
    {
        return Inertia::render('Admin/ref/RefManagement');
    }
    public function showCreateRef()
    {
        return Inertia::render('Admin/ref/CreateRef');
    }
    public function index()
    {
        $refs = Ref::get();
        return response()->json($refs);
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

        if (!Role::where('name', 'Ref')->exists()) {
            return response()->json(['error' => 'Rfe role does not exist']);
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $validatedData['first_name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['nic_number']),
            ]);

            $user->assignRole('Ref');

            Ref::create($validatedData);

            DB::commit();
            return response()->json(['message' => 'Ref created successfully'], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong, please try again']);
        }
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
        $ref = Ref::find($id);
        return Inertia::render('Admin/ref/UpdateRef', [
            'refData' => $ref
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
        $ref = Ref::find($id);
        $ref->update($validatedData);

        return response()->json(['message' => 'Ref updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ref = Ref::find($id);

        if (!$ref) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }

        $ref->delete();

        return response()->json(['message' => 'Supplier deleted successfully'], 200);
    }
}
