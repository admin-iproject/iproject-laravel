<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request)
    {
        $query = Project::with(['company', 'owner', 'department']);

        // Filter by company if not admin
        if (!Auth::user()->hasRole('admin')) {
            $query->where('company_id', Auth::user()->company_id);
        }

        // Apply filters
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortDir = $request->get('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $projects = $query->paginate(20);

        $companies = Company::all();

        return view('projects.index', compact('projects', 'companies'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $this->authorize('create', Project::class);

        $companies = Company::all();
        $departments = Department::all();
        $users = User::all();

        return view('projects.create', compact('companies', 'departments', 'users'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:10',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'owner_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|integer',
            'priority' => 'nullable|integer',
            'description' => 'nullable|string',
            'target_budget' => 'nullable|numeric|min:0',
            'color_identifier' => 'nullable|string|max:6',
        ]);

        $validated['creator_id'] = Auth::id();
        $validated['active'] = true;

        $project = Project::create($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        $this->authorize('view', $project);

        $project->load([
            'company',
            'department',
            'owner',
            'creator',
            'team',
            'tasks' => function ($query) {
                $query->whereNull('parent_id')->with('children');
            }
        ]);

        $taskStats = [
            'total' => $project->tasks->count(),
            'completed' => $project->tasks->where('percent_complete', 100)->count(),
            'in_progress' => $project->tasks->where('percent_complete', '>', 0)->where('percent_complete', '<', 100)->count(),
            'not_started' => $project->tasks->where('percent_complete', 0)->count(),
        ];

        return view('projects.show', compact('project', 'taskStats'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $this->authorize('update', $project);

        $companies = Company::all();
        $departments = Department::where('company_id', $project->company_id)->get();
        $users = User::all();

        return view('projects.edit', compact('project', 'companies', 'departments', 'users'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:10',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'owner_id' => 'required|exists:users,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'actual_end_date' => 'nullable|date',
            'status' => 'required|integer',
            'percent_complete' => 'required|integer|min:0|max:100',
            'priority' => 'nullable|integer',
            'description' => 'nullable|string',
            'target_budget' => 'nullable|numeric|min:0',
            'actual_budget' => 'nullable|numeric|min:0',
            'color_identifier' => 'nullable|string|max:6',
            'active' => 'boolean',
        ]);

        $validated['last_edited'] = now();
        $validated['last_edited_by'] = Auth::id();

        $project->update($validated);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    /**
     * Show the project team management page.
     */
    public function team(Project $project)
    {
        $this->authorize('update', $project);

        $project->load(['team', 'company']);
        $availableUsers = User::where('company_id', $project->company_id)
            ->whereNotIn('id', $project->team->pluck('id'))
            ->get();

        return view('projects.team', compact('project', 'availableUsers'));
    }

    /**
     * Add a team member to the project.
     */
    public function addTeamMember(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'nullable|exists:roles,id',
            'allocation_percent' => 'required|numeric|min:0|max:100',
        ]);

        $project->team()->attach($validated['user_id'], [
            'role_id' => $validated['role_id'] ?? null,
            'allocation_percent' => $validated['allocation_percent'],
        ]);

        return redirect()->route('projects.team', $project)
            ->with('success', 'Team member added successfully.');
    }

    /**
     * Remove a team member from the project.
     */
    public function removeTeamMember(Project $project, User $user)
    {
        $this->authorize('update', $project);

        $project->team()->detach($user->id);

        return redirect()->route('projects.team', $project)
            ->with('success', 'Team member removed successfully.');
    }
}
