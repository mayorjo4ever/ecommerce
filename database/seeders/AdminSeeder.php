<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = Admin::create([
            'name' => 'Ojo Mayowa',
            'email' => 'mayorjo82@yahoo.com',
            'password' => Hash::make('123456'), // Change this in production!
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('Super Admin');

        // Create regular Admin
        $admin = Admin::create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'), // Change this in production!
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin');

        // Create Manager
        $manager = Admin::create([
            'name' => 'Manager User',
            'email' => 'manager@admin.com',
            'password' => Hash::make('password'), // Change this in production!
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('Manager');

        // Create Staff
        $staff = Admin::create([
            'name' => 'Staff User',
            'email' => 'staff@admin.com',
            'password' => Hash::make('password'), // Change this in production!
            'email_verified_at' => now(),
        ]);
        $staff->assignRole('Staff');

        // Create Customer Support
        $support = Admin::create([
            'name' => 'Support User',
            'email' => 'support@admin.com',
            'password' => Hash::make('password'), // Change this in production!
            'email_verified_at' => now(),
        ]);
        $support->assignRole('Customer Support');

        $this->command->info('Admin users created successfully!');
        $this->command->info('Super Admin: superadmin@admin.com / password');
        $this->command->info('Admin: admin@admin.com / password');
        $this->command->info('Manager: manager@admin.com / password');
        $this->command->info('Staff: staff@admin.com / password');
        $this->command->info('Support: support@admin.com / password');
    }
}
