<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use function app;
use function back;
use function redirect;
use function view;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::where('guard_name', 'admin')
            ->orderBy('name')
            ->get()
            ->groupBy(function($permission) {
                return explode(' ', $permission->name)[1] ?? 'other';
            });
        
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create([
            'name' => $validated['name'],
            'guard_name' => 'admin'
        ]);

        // Optionally, give permission to Super Admin automatically
        $superAdmin = Role::where('name', 'Super Admin')
            ->where('guard_name', 'admin')
            ->first();
        
        if ($superAdmin) {
            $superAdmin->givePermissionTo($validated['name']);
        }

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully and assigned to Super Admin!');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully!');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any role
        if ($permission->roles()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete permission assigned to roles. Please remove from roles first.']);
        }

        // Check if permission is assigned to any user directly
        $usersCount = Admin::permission($permission->name)->count();
        if ($usersCount > 0) {
            return back()->withErrors(['error' => 'Cannot delete permission assigned to users.']);
        }

        $permission->delete();

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }
}