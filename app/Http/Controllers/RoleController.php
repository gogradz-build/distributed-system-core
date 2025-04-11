<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:role create', ['only' => ['index', 'getRolePermissions']]);
        // $this->middleware('permission:role view', ['only' => ['store']]);
        // $this->middleware('permission:role update', ['only' => ['givePermissionToRole']]);
        // $this->middleware('permission:role delete', ['only' => ['destroy']]);
    }

    public function roll()
    {
        return Inertia::render('Admin/Roll');
    }
    public function index()
    {
        $roles = Role::get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
            ]
        ]);

        $existingRole = Role::where('name', $validated['role'])->exists();

        if (!$existingRole) {
            $role = Role::create([
                'name' => $validated['role'],
            ]);

            return response()->json([
                'status' => 'Role created successfully',
                'role' => $role,
            ]);
        } else {
            return response()->json(['error' => 'Role already exists.'], 422);
        }
    }

    public function destroy($roleId)
    {
        $role = Role::find($roleId);
        $role->delete();
        return response()->json([
            'message' => 'Role deleted successfully.',
        ]);
    }

    public function getRolePermissions($roleId)
    {
        $role = Role::findOrFail($roleId);
        $permissions = $role->permissions->pluck('name');
        return response()->json($permissions);
    }


    public function givePermissionToRole(Request $request, $roleId)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $role = Role::findOrFail($roleId);

        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'massage' => 'Permissions successfully assigned to the role.',
        ]);
    }
}
