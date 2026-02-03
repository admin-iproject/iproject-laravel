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

        // ============================================================
        // ROLES
        // ============================================================
        // System-level roles (company_id = null ONLY - hidden from company users)
        $super_admin = Role::firstOrCreate(['name' => 'super_admin']);
        $cloud_manager = Role::firstOrCreate(['name' => 'cloud_manager']);

        // Company-level roles (visible to company users)
        $company_admin = Role::firstOrCreate(['name' => 'company_admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $employee = Role::firstOrCreate(['name' => 'employee']);
        $client = Role::firstOrCreate(['name' => 'client']);

        // Remove old 'admin' role if exists (replaced by super_admin + company_admin)
        Role::where('name', 'admin')->delete();

        // ============================================================
        // PERMISSIONS
        // ============================================================
        $permissions = [
            // User Management
            'users-view',
            'users-create',
            'users-edit',
            'users-delete',

            // Company Management
            'companies-view',
            'companies-create',
            'companies-edit',
            'companies-delete',

            // Department Management
            'departments-view',
            'departments-create',
            'departments-edit',
            'departments-delete',

            // Project Management
            'projects-view',
            'projects-create',
            'projects-edit',
            'projects-delete',

            // Task Management
            'tasks-view',
            'tasks-create',
            'tasks-edit',
            'tasks-delete',
            'tasks-log-time',

            // Ticket Management
            'tickets-view',
            'tickets-create',
            'tickets-edit',
            'tickets-delete',
            'tickets-assign',
            'tickets-close',

            // File Management
            'files-view',
            'files-create',
            'files-edit',
            'files-delete',
            'files-approve',

            // Reports
            'reports-view',
            'reports-create',
            'reports-edit',
            'reports-delete',
            'reports-export',

            // License & Usage (cloud_manager)
            'licenses-view',
            'licenses-manage',
            'usage-view',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Remove old-style permissions (view-users, create-users, etc.)
        $oldPermissions = [
            'view-users', 'create-users', 'edit-users', 'delete-users',
            'view-companies', 'create-companies', 'edit-companies', 'delete-companies',
            'view-projects', 'create-projects', 'edit-projects', 'delete-projects', 'view-all-projects',
            'view-tasks', 'create-tasks', 'edit-tasks', 'delete-tasks', 'log-time',
            'view-tickets', 'create-tickets', 'edit-tickets', 'delete-tickets', 'assign-tickets', 'close-tickets',
            'view-files', 'upload-files', 'delete-files', 'approve-files',
            'view-reports', 'export-reports',
            'view-admin-panel',
        ];
        Permission::whereIn('name', $oldPermissions)->delete();

        // ============================================================
        // ASSIGN PERMISSIONS TO ROLES
        // ============================================================

        // --- SUPER ADMIN: Everything ---
        $super_admin->syncPermissions(Permission::all());

        // --- CLOUD MANAGER: Cross-company management ---
        $cloud_manager->syncPermissions([
            'companies-view', 'companies-create', 'companies-edit', 'companies-delete',
            'users-view',
            'licenses-view', 'licenses-manage',
            'usage-view',
            'reports-view', 'reports-export',
        ]);

        // --- COMPANY ADMIN: Full access within their company ---
        $company_admin->syncPermissions([
            'users-view', 'users-create', 'users-edit', 'users-delete',
            'companies-view', 'companies-edit',
            'departments-view', 'departments-create', 'departments-edit', 'departments-delete',
            'projects-view', 'projects-create', 'projects-edit', 'projects-delete',
            'tasks-view', 'tasks-create', 'tasks-edit', 'tasks-delete', 'tasks-log-time',
            'tickets-view', 'tickets-create', 'tickets-edit', 'tickets-delete', 'tickets-assign', 'tickets-close',
            'files-view', 'files-create', 'files-edit', 'files-delete', 'files-approve',
            'reports-view', 'reports-create', 'reports-export',
        ]);

        // --- MANAGER: Manage projects/tasks/tickets ---
        $manager->syncPermissions([
            'users-view',
            'companies-view',
            'departments-view', 'departments-create', 'departments-edit', 'departments-delete',
            'projects-view', 'projects-create', 'projects-edit', 'projects-delete',
            'tasks-view', 'tasks-create', 'tasks-edit', 'tasks-delete', 'tasks-log-time',
            'tickets-view', 'tickets-create', 'tickets-edit', 'tickets-delete', 'tickets-assign', 'tickets-close',
            'files-view', 'files-create', 'files-edit', 'files-delete', 'files-approve',
            'reports-view', 'reports-create', 'reports-export',
        ]);

        // --- EMPLOYEE: Work on assigned items ---
        $employee->syncPermissions([
            'users-view',
            'companies-view',
            'departments-view',
            'projects-view', 'projects-edit',
            'tasks-view', 'tasks-create', 'tasks-edit', 'tasks-log-time',
            'tickets-view', 'tickets-create', 'tickets-edit',
            'files-view', 'files-create',
            'reports-view',
        ]);

        // --- CLIENT: View only assigned items ---
        $client->syncPermissions([
            'companies-view',
            'projects-view',
            'tasks-view',
            'tickets-view', 'tickets-create',
            'files-view',
        ]);

        $this->command->info('Roles and permissions created successfully!');
        $this->command->info('Roles: super_admin, cloud_manager, company_admin, manager, employee, client');
    }
}