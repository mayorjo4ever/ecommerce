<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('guard_name', 'admin')
            ->withCount('users')
            ->with('permissions')
            ->get();
        
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::where('guard_name', 'admin')->get()->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'other';
        });
        
        return view('admin.roles.create', compact('permissions'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'admin'
        ]);

        // Get permission objects from IDs and sync
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    public function show(Role $role)
    {
        $role->load('permissions');
        $role->loadCount('users');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->withErrors(['error' => 'Super Admin role cannot be edited.']);
        }

        $permissions = Permission::where('guard_name', 'admin')->get()->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'other';
        });
        
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

   public function update(Request $request, Role $role)
    {
        // Prevent editing Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->withErrors(['error' => 'Super Admin role cannot be edited.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);
        
        // Get permission objects from IDs and sync
        $permissions = Permission::whereIn('id', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        // Prevent deleting Super Admin role
        if ($role->name === 'Super Admin') {
            return back()->withErrors(['error' => 'Super Admin role cannot be deleted.']);
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return back()->withErrors(['error' => 'Cannot delete role with assigned users.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}