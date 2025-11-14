<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(15);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Group by model name (e.g., 'car.list' -> 'car')
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRoleRequest $request)
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $role = Role::create($data);
        
        if (!empty($permissions)) {
            $role->syncPermissions($permissions);
        }

        return redirect()->route('roles.index')
            ->with('success', __('Role created successfully.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Group by model name (e.g., 'car.list' -> 'car')
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        $role->load('permissions');
        $selectedPermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit', compact('role', 'permissions', 'selectedPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $data = $request->validated();
        $permissions = $data['permissions'] ?? [];
        unset($data['permissions']);

        $role->update($data);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('success', __('Role updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Prevent deletion of super admin role
        if ($role->name === 'super-admin') {
            return redirect()->route('roles.index')
                ->with('error', __('Cannot delete super admin role.'));
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', __('Role deleted successfully.'));
    }
}
