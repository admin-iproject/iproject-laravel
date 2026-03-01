<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use App\Models\CompanySkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\GeocoderService;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Roles that are ONLY visible to system-level users (company_id = null)
     */
    protected array $systemRoles = ['super_admin', 'cloud_manager'];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $status = $request->get('status', 'active');
        $currentUser = auth()->user();

        $query = User::with(['company', 'department', 'roles', 'skills'])
            ->orderBy('last_name')
            ->orderBy('first_name');

        // Filter by status
        switch ($status) {
            case 'inactive':
                $query->inactive();
                break;
            case 'hidden':
                $query->hidden();
                break;
            default:
                $query->active();
                break;
        }

        // Company-level users can ONLY see users in their own company
        // System-level users (company_id = null) see everyone
        if ($currentUser->company_id !== null) {
            $query->where('company_id', $currentUser->company_id);
        } else {
            // System users: apply scope-based filtering
            $scope = $currentUser->getPermissionScope('users-view');
            if ($scope === User::SCOPE_OWN) {
                $query->where('id', $currentUser->id);
            }
            // SCOPE_ALL and SCOPE_ASSIGNED for system users = see everyone
        }

        // Filter by company if provided (system users only)
        if ($request->has('company_id') && $currentUser->company_id === null) {
            $query->where('company_id', $request->company_id);
        }

        // Filter by department if provided
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by skill if provided
        if ($request->has('skill_id') && $request->skill_id) {
            $query->whereHas('skills', function($q) use ($request) {
                $q->where('company_skill_id', $request->skill_id);
            });
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(50)->appends($request->except('page'));

        // Get counts respecting company scope
        $countsQuery = User::query();
        if ($currentUser->company_id !== null) {
            $countsQuery->where('company_id', $currentUser->company_id);
        }

        $counts = [
            'active'   => (clone $countsQuery)->active()->count(),
            'inactive' => (clone $countsQuery)->inactive()->count(),
            'hidden'   => (clone $countsQuery)->hidden()->count(),
        ];

        // Get departments and skills for filters
        $departments = Department::where('company_id', $currentUser->company_id ?? 0)
            ->orderBy('name')
            ->get();
            
        $skills = CompanySkill::where('company_id', $currentUser->company_id ?? 0)
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users', 'status', 'counts', 'departments', 'skills'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', User::class);

        $currentUser = auth()->user();

        // System users see all companies, company users see only theirs
        $companies = $currentUser->company_id === null
            ? Company::orderBy('name')->get()
            : Company::where('id', $currentUser->company_id)->get();

        // Departments filtered by company
        $departments = $currentUser->company_id === null
            ? Department::orderBy('name')->get()
            : Department::where('company_id', $currentUser->company_id)->orderBy('name')->get();

        // Filter roles based on user type
        $roles = $this->getAvailableRoles($currentUser);

        // Load company skills
        $companySkills = CompanySkill::where('company_id', $currentUser->company_id ?? 0)
            ->orderBy('name')
            ->get();

        return view('users.create', compact('companies', 'departments', 'roles', 'companySkills'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $currentUser = auth()->user();

        // ---------------------------------------------------------------
        // LICENSE CHECK – block creation when the target company is full
        // ---------------------------------------------------------------
        $targetCompanyId = $currentUser->company_id ?? $request->company_id;

        if ($targetCompanyId) {
            $company = Company::find($targetCompanyId);

            if ($company && !$company->hasAvailableLicense()) {
                return redirect()->route('users.index')
                    ->with('error', 'License limit reached. ' . $company->name .
                          ' has ' . $company->getLicenseUsage() .
                          ' active users. Contact your administrator to increase the license.');
            }
        }
        // ---------------------------------------------------------------

        $validated = $request->validate([
            'username'       => 'required|string|max:70|unique:users,username',
            'email'          => 'required|email|max:255|unique:users,email',
            'password'       => 'required|string|min:10|confirmed', // Changed to 10
            'first_name'     => 'required|string|max:50',
            'last_name'      => 'required|string|max:50',
            'company_id'     => 'nullable|exists:companies,id',
            'department_id'  => 'nullable|exists:departments,id',
            'hourly_cost'    => 'nullable|numeric|min:0', // ADDED
            'status'         => 'nullable|in:active,inactive,hidden',
            'type'           => 'nullable|integer',
            'phone'          => 'nullable|string|max:30',
            'home_phone'     => 'nullable|string|max:30',
            'mobile'         => 'nullable|string|max:30',
            'address_line1'  => 'nullable|string|max:100',
            'address_line2'  => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:50',
            'state'          => 'nullable|string|max:50',
            'zip'            => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:50',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'birthday'       => 'nullable|string|max:20',
            'availability'   => 'nullable|array', // ADDED
            'availability.*' => 'nullable|numeric|min:0|max:24', // ADDED
            'roles'          => 'nullable|array',
            'roles.*'        => 'exists:roles,name',
        ]);

        // Company-level users can only create users in their own company
        if ($currentUser->company_id !== null) {
            $validated['company_id'] = $currentUser->company_id;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['status']   = $validated['status'] ?? User::STATUS_ACTIVE;

        $user = User::create($validated);

        // The UserObserver will auto-create standard availability with defaults (8hrs Mon-Fri)
        // BUT if admin provided custom hours, override them
        if ($request->has('availability')) {
            foreach ($request->availability as $dayOfWeek => $hours) {
                \App\Models\UserStandardAvailability::where('user_id', $user->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->update(['hours_available' => $hours ?? 0]);
            }
        }

        // Assign roles – sanitize to only allowed roles
        if ($request->has('roles')) {
            $allowedRoles    = $this->getAvailableRoles($currentUser)->pluck('name')->toArray();
            $sanitizedRoles  = array_intersect($request->roles, $allowedRoles);
            $user->syncRoles($sanitizedRoles);
        }

        // Assign skills
        if ($request->has('skills')) {
            $user->skills()->sync($request->skills);
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'User created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load(['company', 'department', 'parent', 'roles', 'permissions', 'skills']);

        $stats = [
            'owned_projects'   => $user->ownedProjects()->count(),
            'team_projects'    => $user->projects()->count(),
            'owned_tasks'      => $user->ownedTasks()->count(),
            'assigned_tasks'   => $user->assignedTasks()->count(),
        ];

        return view('users.show', compact('user', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $currentUser = auth()->user();

        // System users see all companies, company users see only theirs
        $companies = $currentUser->company_id === null
            ? Company::orderBy('name')->get()
            : Company::where('id', $currentUser->company_id)->get();

        // Departments filtered by the TARGET user's company (or current user's company)
        $targetCompanyId = $user->company_id ?? $currentUser->company_id;
        $departments = $currentUser->company_id === null
            ? Department::where('company_id', $targetCompanyId)->orderBy('name')->get()
            : Department::where('company_id', $currentUser->company_id)->orderBy('name')->get();

        // Filter roles based on user type
        $roles = $this->getAvailableRoles($currentUser);

        // Load company skills
        $companySkills = CompanySkill::where('company_id', $user->company_id ?? 0)
            ->orderBy('name')
            ->get();

        return view('users.edit', compact('user', 'companies', 'departments', 'roles', 'companySkills'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $currentUser = auth()->user();

        $validated = $request->validate([
            'username'       => ['required', 'string', 'max:70', Rule::unique('users')->ignore($user->id)],
            'email'          => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password'       => 'nullable|string|min:10|confirmed', // Changed to 10
            'first_name'     => 'required|string|max:50',
            'last_name'      => 'required|string|max:50',
            'company_id'     => 'nullable|exists:companies,id',
            'department_id'  => 'nullable|exists:departments,id',
            'hourly_cost'    => 'nullable|numeric|min:0', // ADDED
            'status'         => 'nullable|in:active,inactive,hidden',
            'type'           => 'nullable|integer',
            'phone'          => 'nullable|string|max:30',
            'home_phone'     => 'nullable|string|max:30',
            'mobile'         => 'nullable|string|max:30',
            'address_line1'  => 'nullable|string|max:100',
            'address_line2'  => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:50',
            'state'          => 'nullable|string|max:50',
            'zip'            => 'nullable|string|max:20',
            'country'        => 'nullable|string|max:50',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'lat'            => 'nullable|numeric',
            'lng'            => 'nullable|numeric',
            'birthday'       => 'nullable|string|max:20',
            'availability'   => 'nullable|array', // ADDED
            'availability.*' => 'nullable|numeric|min:0|max:24', // ADDED
            'roles'          => 'nullable|array',
            'roles.*'        => 'exists:roles,name',
        ]);

        // Company-level users cannot change company_id
        if ($currentUser->company_id !== null) {
            $validated['company_id'] = $currentUser->company_id;
        }

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Silently geocode address if address fields were submitted
        if ($request->hasAny(['address_line1','city','state','zip'])) {
            if ($user->address_line1 || $user->city) {
                if (GeocoderService::geocodeModel($user)) {
                    $user->saveQuietly(); // avoid triggering updated events again
                }
            }
        }

        // Update standard availability
        if ($request->has('availability')) {
            foreach ($request->availability as $dayOfWeek => $hours) {
                \App\Models\UserStandardAvailability::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'day_of_week' => $dayOfWeek,
                    ],
                    [
                        'hours_available' => $hours ?? 0,
                    ]
                );
            }
        }

        // Sync roles – sanitize to only allowed roles
        if ($request->has('roles')) {
            $allowedRoles    = $this->getAvailableRoles($currentUser)->pluck('name')->toArray();
            $sanitizedRoles  = array_intersect($request->roles, $allowedRoles);
            $user->syncRoles($sanitizedRoles);
        }

        // Sync skills
        if ($request->has('skills')) {
            $user->skills()->sync($request->skills);
        } else {
            // If skills array not present, remove all skills
            $user->skills()->detach();
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'User updated successfully');
    }

    /**
     * Remove the specified resource from storage (soft delete).
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Change user status to inactive (ghost user)
     */
    public function makeInactive(User $user)
    {
        $this->authorize('changeStatus', $user);

        $user->update(['status' => User::STATUS_INACTIVE]);

        return redirect()->route('users.index', ['status' => 'inactive'])
            ->with('success', 'User marked as inactive (ghost)');
    }

    /**
     * Change user status to hidden
     */
    public function makeHidden(User $user)
    {
        $this->authorize('changeStatus', $user);

        $user->update(['status' => User::STATUS_HIDDEN]);

        return redirect()->route('users.index', ['status' => 'hidden'])
            ->with('success', 'User marked as hidden');
    }

    /**
     * Change user status to active.
     * Blocked when the user's company has no available license slot.
     */
    public function makeActive(User $user)
    {
        $this->authorize('changeStatus', $user);

        // ---------------------------------------------------------------
        // LICENSE CHECK – cannot activate when the company is at its cap
        // ---------------------------------------------------------------
        if ($user->company_id !== null) {
            $company = $user->company;

            if ($company && !$company->hasAvailableLicense()) {
                return redirect()->route('users.index', ['status' => $user->status])
                    ->with('error', 'License limit reached. Cannot activate user. ' .
                          $company->name . ' has ' . $company->getLicenseUsage() .
                          ' active users. Contact your administrator to increase the license.');
            }
        }
        // ---------------------------------------------------------------

        $user->update(['status' => User::STATUS_ACTIVE]);

        return redirect()->route('users.index', ['status' => 'active'])
            ->with('success', 'User activated successfully');
    }

    /**
     * Get departments for a specific company (AJAX)
     */
    public function getDepartments(Company $company)
    {
        $departments = $company->departments()
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'departments' => $departments
        ]);
    }

    /**
     * Get available roles based on the current user's level.
     * System users (company_id = null) see ALL roles.
     * Company-level users NEVER see super_admin or cloud_manager.
     * company_admin can assign: company_admin, manager, employee, client
     * manager can assign: employee, client
     * employee/client cannot assign roles.
     */
    private function getAvailableRoles(User $currentUser): \Illuminate\Database\Eloquent\Collection
    {
        // System-level users see all roles
        if ($currentUser->company_id === null) {
            return Role::orderBy('name')->get();
        }

        // Company-level users never see system roles
        $roles = Role::whereNotIn('name', $this->systemRoles)->orderBy('name')->get();

        // Manager can only assign employee and client
        if ($currentUser->hasRole('manager')) {
            return $roles->filter(fn($role) => in_array($role->name, ['employee', 'client']));
        }

        // Employee and client cannot assign any roles
        if ($currentUser->hasRole(['employee', 'client'])) {
            return collect();
        }

        // company_admin sees all non-system roles
        return $roles;
    }
}