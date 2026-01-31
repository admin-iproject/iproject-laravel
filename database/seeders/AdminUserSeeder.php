<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a demo company
        $company = Company::create([
            'name' => 'Demo Company',
            'email' => 'info@democompany.com',
            'phone1' => '555-0100',
            'city' => 'San Francisco',
            'state' => 'CA',
            'country' => 'USA',
            'type' => 1,
        ]);

        // Create admin user
        $admin = User::create([
            'username' => 'admin',
            'email' => 'admin@iproject.local',
            'password' => Hash::make('password'),
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'company_id' => $company->id,
            'type' => 1,
            'email_verified_at' => now(),
        ]);

        $admin->assignRole('admin');

        // Create manager user
        $manager = User::create([
            'username' => 'manager',
            'email' => 'manager@iproject.local',
            'password' => Hash::make('password'),
            'first_name' => 'Project',
            'last_name' => 'Manager',
            'company_id' => $company->id,
            'type' => 1,
            'email_verified_at' => now(),
        ]);

        $manager->assignRole('manager');

        // Create employee user
        $employee = User::create([
            'username' => 'employee',
            'email' => 'employee@iproject.local',
            'password' => Hash::make('password'),
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_id' => $company->id,
            'type' => 1,
            'email_verified_at' => now(),
        ]);

        $employee->assignRole('employee');

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@iproject.local');
        $this->command->info('Password: password');
    }
}
