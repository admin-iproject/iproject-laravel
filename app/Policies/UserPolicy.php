<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Perform pre-authorization checks.
     * Note: Gate::before() in AppServiceProvider handles company_id = null bypass.
     * We only need to block inactive/hidden users here.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Inactive and hidden users cannot perform any actions
        if (!$user->isActive()) {
            return false;
        }

        return null; // Continue with regular authorization
    }

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('users-view');
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        if (!$user->hasPermissionTo('users-view')) {
            return false;
        }

        // Can always view users in same company
        if ($user->company_id === $model->company_id) {
            return true;
        }

        return $user->canAccessRecord('users-view', $model);
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users-create');
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model): bool
    {
        // Cannot edit yourself (use profile for that)
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hasPermissionTo('users-edit')) {
            return false;
        }

        // Can only edit users in same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Company admin can edit all users in their company
        if ($user->hasRole('company_admin')) {
            return true;
        }

        // For other roles, check scope
        return $user->canAccessRecord('users-edit', $model);
    }

    /**
     * Determine whether the user can change user status.
     */
    public function changeStatus(User $user, User $model): bool
    {
        // Cannot change own status
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hasPermissionTo('users-edit')) {
            return false;
        }

        // Can only change status of users in same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Company admin can change status of all users in their company
        if ($user->hasRole('company_admin')) {
            return true;
        }

        // For other roles, check scope
        return $user->canAccessRecord('users-edit', $model);
    }

    /**
     * Determine whether the user can soft delete the user.
     */
    public function delete(User $user, User $model): bool
    {
        // Cannot delete yourself
        if ($user->id === $model->id) {
            return false;
        }

        if (!$user->hasPermissionTo('users-delete')) {
            return false;
        }

        // Can only delete users in same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        // Company admin can delete all users in their company
        if ($user->hasRole('company_admin')) {
            return true;
        }

        // For other roles, check scope
        return $user->canAccessRecord('users-delete', $model);
    }

    /**
     * Determine whether the user can restore the user.
     */
    public function restore(User $user, User $model): bool
    {
        if (!$user->hasPermissionTo('users-edit')) {
            return false;
        }

        // Can only restore users in same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        return $user->canAccessRecord('users-edit', $model);
    }

    /**
     * Determine whether the user can permanently delete the user.
     * Destructive operation - handled by Gate::before() for company_id = null.
     * Company-level users should NEVER force delete.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return false; // Only company_id = null users can force delete (Gate::before handles this)
    }

    /**
     * Determine whether the user can manage user roles and permissions.
     * Only company_admin role within their own company.
     */
    public function manageRoles(User $user, User $model): bool
    {
        // Cannot change own roles
        if ($user->id === $model->id) {
            return false;
        }

        // Must be company_admin
        if (!$user->hasRole('company_admin')) {
            return false;
        }

        // Can only manage roles within same company
        return $user->company_id === $model->company_id;
    }

    /**
     * Determine whether the user can view user permissions.
     */
    public function viewPermissions(User $user, User $model): bool
    {
        // Can view own permissions
        if ($user->id === $model->id) {
            return true;
        }

        if (!$user->hasPermissionTo('users-view')) {
            return false;
        }

        // Can only view permissions of users in same company
        if ($user->company_id !== $model->company_id) {
            return false;
        }

        return $user->canAccessRecord('users-view', $model);
    }
}