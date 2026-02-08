<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for admin guard
        $permissions = [
            // Product permissions
            'view products',
            'create products',
            'edit products',
            'delete products',
            
            // Category permissions
            'view categories',
            'create categories',
            'edit categories',
            'delete categories',
            
            // Order permissions
            'view orders',
            'create orders',
            'edit orders',
            'delete orders',
            'process orders',
            
            // Customer permissions
            'view customers',
            'create customers',
            'edit customers',
            'delete customers',
            
            // Coupon permissions
            'view coupons',
            'create coupons',
            'edit coupons',
            'delete coupons',
            
            // Review permissions
            'view reviews',
            'approve reviews',
            'delete reviews',
            
            // Admin management permissions
            'view admins',
            'create admins',
            'edit admins',
            'delete admins',
            
            // Role & Permission management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
            
            // Settings permissions
            'view settings',
            'edit settings',
            
            // Reports permissions
            'view reports',
            'export reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Create roles for admin guard
        
        // Super Admin - has all permissions
        $superAdmin = Role::create([
            'name' => 'Super Admin',
            'guard_name' => 'admin'
        ]);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - has most permissions except admin management
        $admin = Role::create([
            'name' => 'Admin',
            'guard_name' => 'admin'
        ]);
        $admin->givePermissionTo([
            'view products', 'create products', 'edit products', 'delete products',
            'view categories', 'create categories', 'edit categories', 'delete categories',
            'view orders', 'create orders', 'edit orders', 'process orders',
            'view customers', 'edit customers',
            'view coupons', 'create coupons', 'edit coupons', 'delete coupons',
            'view reviews', 'approve reviews', 'delete reviews',
            'view reports',
        ]);

        // Manager - can view and edit, but not delete
        $manager = Role::create([
            'name' => 'Manager',
            'guard_name' => 'admin'
        ]);
        $manager->givePermissionTo([
            'view products', 'edit products',
            'view categories', 'edit categories',
            'view orders', 'edit orders', 'process orders',
            'view customers',
            'view coupons',
            'view reviews', 'approve reviews',
            'view reports',
        ]);

        // Staff - limited permissions (view only mostly)
        $staff = Role::create([
            'name' => 'Staff',
            'guard_name' => 'admin'
        ]);
        $staff->givePermissionTo([
            'view products',
            'view categories',
            'view orders', 'process orders',
            'view customers',
            'view reviews',
        ]);

        // Customer Support - customer and order focused
        $support = Role::create([
            'name' => 'Customer Support',
            'guard_name' => 'admin'
        ]);
        $support->givePermissionTo([
            'view products',
            'view orders', 'edit orders', 'process orders',
            'view customers', 'edit customers',
            'view reviews', 'approve reviews',
        ]);
    }
}
