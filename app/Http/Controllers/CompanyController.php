<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Company::class);

        $query = Company::with(['owner', 'lastEditor']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        // Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $companies = $query->paginate(20);

        // Get filter options
        $types = Company::distinct()->pluck('type')->filter();
        $categories = Company::distinct()->pluck('category')->filter();

        return view('companies.index', compact('companies', 'types', 'categories'));
    }

    /**
     * Show the form for creating a new company.
     */
    public function create()
    {
        $this->authorize('create', Company::class);

        $users = User::select('id', 'first_name', 'last_name', 'email')
                    ->orderBy('first_name')
                    ->get();

        return view('companies.create', compact('users'));
    }

    /**
     * Store a newly created company.
     */
    public function store(StoreCompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $validated['last_edited'] = now();
        $validated['last_edited_by'] = Auth::id();

        $company = Company::create($validated);

        return redirect()
            ->route('companies.show', $company)
            ->with('success', 'Company created successfully.');
    }

    /**
     * Display the specified company.
     */
    public function show(Company $company)
    {
        $this->authorize('view', $company);

        $company->load([
            'owner',
            'lastEditor',
            'departments',
            'users',
            'projects',
            'contacts',
            // 'tickets'  // TODO: Add when tickets module ready
        ]);

        // Get statistics
        $stats = [
            'total_users' => $company->users()->count(),
            'total_departments' => $company->departments()->count(),
            'total_projects' => $company->projects()->count(),
            'active_projects' => $company->projects()->where('active', true)->count(),
            'total_contacts' => $company->contacts()->count(),
            'open_tickets' => 0, // TODO: Add when tickets module ready
        ];

        return view('companies.show', compact('company', 'stats'));
    }

    /**
     * Show the form for editing the company.
     */
    public function edit(Company $company)
    {
        $this->authorize('update', $company);

        $users = User::select('id', 'first_name', 'last_name', 'email')
                    ->orderBy('first_name')
                    ->get();

        return view('companies.edit', compact('company', 'users'));
    }

    /**
     * Update the specified company.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $validated = $request->validated();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            $validated['logo'] = $request->file('logo')->store('company-logos', 'public');
        }

        $validated['last_edited'] = now();
        $validated['last_edited_by'] = Auth::id();

        $company->update($validated);

        return redirect()
            ->route('companies.show', $company)
            ->with('success', 'Company updated successfully.');
    }

    /**
     * Remove the specified company.
     */
    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);

        // Check if company has dependencies
        if ($company->users()->count() > 0) {
            return back()->with('error', 'Cannot delete company with associated users. Please reassign users first.');
        }

        if ($company->projects()->count() > 0) {
            return back()->with('error', 'Cannot delete company with associated projects. Please reassign projects first.');
        }

        // Delete logo if exists
        if ($company->logo) {
            Storage::disk('public')->delete($company->logo);
        }

        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Company deleted successfully.');
    }

    /**
     * Display company settings.
     */
    public function settings(Company $company)
    {
        $this->authorize('update', $company);

        return view('companies.settings', compact('company'));
    }

    /**
     * Update company settings.
     */
    public function updateSettings(Request $request, Company $company)
    {
        $this->authorize('update', $company);

        $validated = $request->validate([
            'ticket_priorities' => 'nullable|string',
            'ticket_categories' => 'nullable|string',
            'ticket_notification' => 'nullable|string|in:Yes,No',
            'ticket_notify_email' => 'nullable|string',
            'ticket_close_reasons' => 'nullable|string',
            'num_of_licensed_users' => 'nullable|integer|min:0',
        ]);

        $validated['last_edited'] = now();
        $validated['last_edited_by'] = Auth::id();

        $company->update($validated);

        return back()->with('success', 'Company settings updated successfully.');
    }

    /**
     * Get company statistics for dashboard.
     */
    public function statistics(Company $company)
    {
        $this->authorize('view', $company);

        return response()->json([
            'users' => $company->users()->count(),
            'departments' => $company->departments()->count(),
            'projects' => [
                'total' => $company->projects()->count(),
                'active' => $company->projects()->where('active', true)->count(),
                'completed' => $company->projects()->where('percent_complete', 100)->count(),
            ],
            'tasks' => [
                'total' => $company->projects()->withCount('tasks')->get()->sum('tasks_count'),
            ],
            'tickets' => [
				'open' => 0, // TODO
				'closed' => 0, // TODO
            ],
        ]);
    }
}
