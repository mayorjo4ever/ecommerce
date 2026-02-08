<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('roles')->latest()->paginate(20);
        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['email_verified_at'] = now();

        $admin = Admin::create($validated);

        // Get role objects from IDs and sync
        $roles = Role::whereIn('id', $request->roles)->get();
        $admin->syncRoles($roles);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin created successfully!');
    }

    public function show(Admin $admin)
    {
        $admin->load('roles', 'permissions');
        return view('admin.admins.show', compact('admin'));
    }

    public function edit(Admin $admin)
    {
        // Prevent editing your own account from this page
        if ($admin->id === auth()->guard('admin')->id()) {
            return redirect()->route('admin.profile')
                ->with('info', 'Please use the profile page to edit your own account.');
        }

        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin.admins.edit', compact('admin', 'roles'));
    }

    public function update(Request $request, Admin $admin)
    {
        // Prevent editing your own account from this page
        if ($admin->id === auth()->guard('admin')->id()) {
            return back()->withErrors(['error' => 'You cannot edit your own account from this page.']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'roles' => 'required|array|min:1',
            'roles.*' => 'exists:roles,id',
        ]);

        // Only update password if provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        // Get role objects from IDs and sync
        $roles = Role::whereIn('id', $request->roles)->get();
        $admin->syncRoles($roles);

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin updated successfully!');
    }


    public function destroy(Admin $admin)
    {
        // Prevent deleting yourself
        if ($admin->id === auth()->guard('admin')->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        // Prevent deleting the last super admin
        if ($admin->hasRole('Super Admin')) {
            $superAdminCount = Admin::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                return back()->withErrors(['error' => 'Cannot delete the last Super Admin.']);
            }
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
            ->with('success', 'Admin deleted successfully!');
    }
}