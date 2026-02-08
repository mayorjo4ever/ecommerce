<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use function app;

class AddPOSPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create POS permissions
        $posPermissions = [
            'access pos',
            'create pos sales',
            'view pos history',
            'print pos receipts',
        ];

        foreach ($posPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admin'
            ]);
        }

        // Assign to Super Admin and Admin roles
        $superAdmin = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();
        $admin = Role::where('name', 'Admin')->where('guard_name', 'admin')->first();
        
        if ($superAdmin) {
            $superAdmin->givePermissionTo($posPermissions);
        }
        
        if ($admin) {
            $admin->givePermissionTo($posPermissions);
        }

        $this->command->info('POS permissions added successfully!');
    }
}
