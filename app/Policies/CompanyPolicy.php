<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Determine if the user can view any companies.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-companies');
    }

    /**
     * Determine if the user can view the company.
     */
    public function view(User $user, Company $company): bool
    {
        // Admins can view all companies
        if ($user->hasRole('admin')) {
            return true;
        }

        // Users can view their own company
        if ($user->company_id === $company->id) {
            return true;
        }

        // Otherwise check permission
        return $user->can('view-companies');
    }

    /**
     * Determine if the user can create companies.
     */
    public function create(User $user): bool
    {
        return $user->can('create-companies');
    }

    /**
     * Determine if the user can update the company.
     */
    public function update(User $user, Company $company): bool
    {
        // Admins can update any company
        if ($user->hasRole('admin')) {
            return true;
        }

        // Company owners can update their company
        if ($user->id === $company->owner_id) {
            return true;
        }

        // Otherwise check permission
        return $user->can('edit-companies');
    }

    /**
     * Determine if the user can delete the company.
     */
    public function delete(User $user, Company $company): bool
    {
        // Only admins can delete companies
        return $user->hasRole('admin') && $user->can('delete-companies');
    }

    /**
     * Determine if the user can restore the company.
     */
    public function restore(User $user, Company $company): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine if the user can permanently delete the company.
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return $user->hasRole('admin');
    }
}
