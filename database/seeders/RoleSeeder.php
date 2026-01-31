<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);
        $client = Role::create(['name' => 'client']);

        // Create Permissions
        $permissions = [
            // User Management
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            
            // Company Management
            'view-companies',
            'create-companies',
            'edit-companies',
            'delete-companies',
            
            // Project Management
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'view-all-projects',
            
            // Task Management
            'view-tasks',
            'create-tasks',
            'edit-tasks',
            'delete-tasks',
            'log-time',
            
            // Ticket Management
            'view-tickets',
            'create-tickets',
            'edit-tickets',
            'delete-tickets',
            'assign-tickets',
            'close-tickets',
            
            // File Management
            'view-files',
            'upload-files',
            'delete-files',
            'approve-files',
            
            // Reports
            'view-reports',
            'export-reports',
            
            // Admin Panel
            'view-admin-panel',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        
        // Admin gets all permissions
        $admin->givePermissionTo(Permission::all());
        
        // Manager permissions
        $manager->givePermissionTo([
            'view-projects', 'create-projects', 'edit-projects', 'view-all-projects',
            'view-tasks', 'create-tasks', 'edit-tasks', 'log-time',
            'view-tickets', 'create-tickets', 'edit-tickets', 'assign-tickets', 'close-tickets',
            'view-files', 'upload-files', 'approve-files',
            'view-reports', 'export-reports',
            'view-companies',
        ]);
        
        // Employee permissions
        $employee->givePermissionTo([
            'view-projects', 'view-tasks', 'create-tasks', 'edit-tasks', 'log-time',
            'view-tickets', 'create-tickets',
            'view-files', 'upload-files',
        ]);
        
        // Client permissions
        $client->givePermissionTo([
            'view-projects', 'view-tasks',
            'view-tickets', 'create-tickets',
            'view-files',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
