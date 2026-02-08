<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

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
        $superAdmin = \Spatie\Permission\Models\Role::where('name', 'Super Admin')
            ->where('guard_name', 'admin')
            ->first();
        
        if ($superAdmin) {
            $superAdmin->givePermissionTo($validated['name']);
        }

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    public function destroy(Permission $permission)
    {
        // Check if permission is assigned to any role
        if ($permission->roles()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete permission assigned to roles.']);
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }
}