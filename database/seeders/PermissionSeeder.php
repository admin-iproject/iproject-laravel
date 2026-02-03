<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all module permissions
        $modules = [
            'users',
            'companies',
            'departments',
            'projects',
            'tasks',
            'tickets',
            'files',
            'reports',
        ];

        $actions = ['view', 'create', 'edit', 'delete'];

        // Create permissions for each module
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}-{$action}",
                    'guard_name' => 'web'
                ]);
            }
        }

        // Get roles
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();
        $employee = Role::where('name', 'employee')->first();
        $client = Role::where('name', 'client')->first();

        // Assign permissions to Admin role
        if ($admin) {
            $admin->syncPermissions(Permission::all());
            
            // Set admin user scopes to 'all' for everything
            $adminUsers = User::role('admin')->get();
            foreach ($adminUsers as $adminUser) {
                $scopes = [];
                foreach ($modules as $module) {
                    foreach ($actions as $action) {
                        $scopes["{$module}-{$action}"] = User::SCOPE_ALL;
                    }
                }
                $adminUser->permission_scopes = $scopes;
                $adminUser->save();
            }
        }

        // Assign permissions to Manager role
        if ($manager) {
            $manager->syncPermissions([
                // Users
                'users-view', 'users-create', 'users-edit',
                
                // Companies
                'companies-view', 'companies-edit',
                
                // Departments
                'departments-view', 'departments-create', 'departments-edit', 'departments-delete',
                
                // Projects
                'projects-view', 'projects-create', 'projects-edit', 'projects-delete',
                
                // Tasks
                'tasks-view', 'tasks-create', 'tasks-edit', 'tasks-delete',
                
                // Tickets
                'tickets-view', 'tickets-create', 'tickets-edit', 'tickets-delete',
                
                // Files
                'files-view', 'files-create', 'files-edit', 'files-delete',
                
                // Reports
                'reports-view', 'reports-create',
            ]);

            // Set manager user scopes
            $managerUsers = User::role('manager')->get();
            foreach ($managerUsers as $managerUser) {
                $managerUser->permission_scopes = [
                    // Users - can view all, edit assigned
                    'users-view' => User::SCOPE_ALL,
                    'users-create' => User::SCOPE_ALL,
                    'users-edit' => User::SCOPE_ASSIGNED,
                    
                    // Companies - view all, edit own company
                    'companies-view' => User::SCOPE_ALL,
                    'companies-edit' => User::SCOPE_OWN,
                    
                    // Departments - full access to own company
                    'departments-view' => User::SCOPE_ALL,
                    'departments-create' => User::SCOPE_ALL,
                    'departments-edit' => User::SCOPE_ALL,
                    'departments-delete' => User::SCOPE_ALL,
                    
                    // Projects - full access to assigned projects
                    'projects-view' => User::SCOPE_ALL,
                    'projects-create' => User::SCOPE_ALL,
                    'projects-edit' => User::SCOPE_ASSIGNED,
                    'projects-delete' => User::SCOPE_OWN,
                    
                    // Tasks - full access
                    'tasks-view' => User::SCOPE_ALL,
                    'tasks-create' => User::SCOPE_ALL,
                    'tasks-edit' => User::SCOPE_ALL,
                    'tasks-delete' => User::SCOPE_ASSIGNED,
                    
                    // Tickets - full access
                    'tickets-view' => User::SCOPE_ALL,
                    'tickets-create' => User::SCOPE_ALL,
                    'tickets-edit' => User::SCOPE_ALL,
                    'tickets-delete' => User::SCOPE_ASSIGNED,
                    
                    // Files - full access
                    'files-view' => User::SCOPE_ALL,
                    'files-create' => User::SCOPE_ALL,
                    'files-edit' => User::SCOPE_ALL,
                    'files-delete' => User::SCOPE_ASSIGNED,
                    
                    // Reports - view and create
                    'reports-view' => User::SCOPE_ALL,
                    'reports-create' => User::SCOPE_ALL,
                ];
                $managerUser->save();
            }
        }

        // Assign permissions to Employee role
        if ($employee) {
            $employee->syncPermissions([
                // Users - view only
                'users-view',
                
                // Companies - view only
                'companies-view',
                
                // Departments - view only
                'departments-view',
                
                // Projects - view and edit assigned
                'projects-view', 'projects-edit',
                
                // Tasks - full access to assigned tasks
                'tasks-view', 'tasks-create', 'tasks-edit',
                
                // Tickets - create and view
                'tickets-view', 'tickets-create', 'tickets-edit',
                
                // Files - view and create
                'files-view', 'files-create',
                
                // Reports - view only
                'reports-view',
            ]);

            // Set employee user scopes
            $employeeUsers = User::role('employee')->get();
            foreach ($employeeUsers as $employeeUser) {
                $employeeUser->permission_scopes = [
                    // Users - view assigned only
                    'users-view' => User::SCOPE_ASSIGNED,
                    
                    // Companies - view all
                    'companies-view' => User::SCOPE_ALL,
                    
                    // Departments - view all
                    'departments-view' => User::SCOPE_ALL,
                    
                    // Projects - view all, edit assigned
                    'projects-view' => User::SCOPE_ALL,
                    'projects-edit' => User::SCOPE_ASSIGNED,
                    
                    // Tasks - view all, create/edit assigned only
                    'tasks-view' => User::SCOPE_ALL,
                    'tasks-create' => User::SCOPE_ASSIGNED,
                    'tasks-edit' => User::SCOPE_ASSIGNED,
                    
                    // Tickets - view all, edit own
                    'tickets-view' => User::SCOPE_ALL,
                    'tickets-create' => User::SCOPE_ALL,
                    'tickets-edit' => User::SCOPE_OWN,
                    
                    // Files - view all, create for assigned
                    'files-view' => User::SCOPE_ALL,
                    'files-create' => User::SCOPE_ASSIGNED,
                    
                    // Reports - view assigned
                    'reports-view' => User::SCOPE_ASSIGNED,
                ];
                $employeeUser->save();
            }
        }

        // Assign permissions to Client role
        if ($client) {
            $client->syncPermissions([
                // Projects - view only
                'projects-view',
                
                // Tasks - view only
                'tasks-view',
                
                // Tickets - create and view own
                'tickets-view', 'tickets-create',
                
                // Files - view only
                'files-view',
            ]);

            // Set client user scopes
            $clientUsers = User::role('client')->get();
            foreach ($clientUsers as $clientUser) {
                $clientUser->permission_scopes = [
                    // Projects - view assigned only
                    'projects-view' => User::SCOPE_ASSIGNED,
                    
                    // Tasks - view assigned only
                    'tasks-view' => User::SCOPE_ASSIGNED,
                    
                    // Tickets - view and create own
                    'tickets-view' => User::SCOPE_OWN,
                    'tickets-create' => User::SCOPE_OWN,
                    
                    // Files - view assigned
                    'files-view' => User::SCOPE_ASSIGNED,
                ];
                $clientUser->save();
            }
        }

        $this->command->info('Permissions and scopes created successfully!');
    }
}