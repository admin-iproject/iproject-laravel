<?php

namespace App\Policies;

use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartmentPolicy
{
    /**
     * Note: No before() needed here.
     * Gate::before() in AppServiceProvider handles company_id = null bypass.
     */

    /**
     * Determine whether the user can view any departments.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('departments-view');
    }

    /**
     * Determine whether the user can view the department.
     */
    public function view(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('departments-view')) {
            return false;
        }

        // Can only view departments in their own company
        return $user->company_id === $department->company_id;
    }

    /**
     * Determine whether the user can create departments.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('departments-create');
    }

    /**
     * Determine whether the user can update the department.
     */
    public function update(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('departments-edit')) {
            return false;
        }

        // Can only edit departments in their own company
        return $user->company_id === $department->company_id;
    }

    /**
     * Determine whether the user can delete the department.
     */
    public function delete(User $user, Department $department): bool
    {
        if (!$user->hasPermissionTo('departments-delete')) {
            return false;
        }

        // Can only delete departments in their own company
        return $user->company_id === $department->company_id;
    }

    /**
     * Determine whether the user can restore the department.
     * Company-level users can NEVER restore departments.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function restore(User $user, Department $department): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the department.
     * Company-level users can NEVER force delete departments.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function forceDelete(User $user, Department $department): bool
    {
        return false;
    }
}