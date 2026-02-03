<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\DepartmentPolicy;
use App\Policies\UserPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ============================================================
        // GLOBAL GATE BYPASS - company_id = null bypasses ALL policies
        // This covers super_admin AND cloud_manager roles
        // ============================================================
        Gate::before(function (User $user, string $ability) {
            if ($user->company_id === null) {
                return true;
            }
        });

        // Register Policies
        Gate::policy(Company::class, CompanyPolicy::class);
        Gate::policy(Department::class, DepartmentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}