<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Note: No before() needed here.
     * Gate::before() in AppServiceProvider handles company_id = null bypass.
     */

    /**
     * Determine if the user can view any companies.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('companies-view');
    }

    /**
     * Determine if the user can view the company.
     * Users can always view the company they belong to.
     */
    public function view(User $user, Company $company): bool
    {
        if (!$user->hasPermissionTo('companies-view')) {
            return false;
        }

        // User can only view their own company
        return $user->company_id === $company->id;
    }

    /**
     * Determine if the user can create companies.
     * Company-level users can NEVER create companies.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine if the user can update the company.
     * Only company_admin and manager can edit their own company.
     */
    public function update(User $user, Company $company): bool
    {
        if (!$user->hasPermissionTo('companies-edit')) {
            return false;
        }

        // Can only edit their own company
        return $user->company_id === $company->id;
    }

    /**
     * Determine if the user can delete the company.
     * Company-level users can NEVER delete companies.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function delete(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine if the user can restore the company.
     * Company-level users can NEVER restore companies.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function restore(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine if the user can permanently delete the company.
     * Company-level users can NEVER force delete companies.
     * Only company_id = null users can (handled by Gate::before()).
     */
    public function forceDelete(User $user, Company $company): bool
    {
        return false;
    }
}