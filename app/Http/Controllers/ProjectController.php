<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request)
    {
        $query = Project::with(['company:id,name', 'owner:id,first_name,last_name', 'department:id,name'])
            ->withCount('tasks')
            ->select('projects.*');

        // Search
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filter by company
        if ($companyId = $request->input('company_id')) {
            $query->forCompany($companyId);
        }

        // Filter by department
        if ($departmentId = $request->input('department_id')) {
            $query->forDepartment($departmentId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->byStatus($status);
        }

        // Filter by active/inactive
        if ($request->has('active')) {
            $request->input('active') === '1' 
                ? $query->active() 
                : $query->inactive();
        }

        // Filter by user's projects
        if ($request->input('my_projects')) {
            $query->forUser(auth()->id());
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        // Validate sort columns
        $allowedSorts = ['name', 'start_date', 'end_date', 'status', 'priority', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Pagination
        $perPage = $request->input('per_page', 25);
        $projects = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();

        return view('projects.index', compact('projects', 'companies', 'departments'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create()
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        $users = User::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('projects.create', compact('companies', 'departments', 'users'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        DB::beginTransaction();
        
        try {
            $project = Project::create($request->validated());

            // Add creator to team if not already owner
            if ($project->owner_id !== $project->creator_id) {
                $project->addTeamMember(
                    User::find($project->creator_id),
                    roleId: null,
                    allocationPercent: 0
                );
            }

            DB::commit();

            return redirect()
                ->route('projects.show', $project)
                ->with('success', 'Project created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create project: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified project.
     */
    public function show(Project $project)
    {
        // Eager load relationships to avoid N+1
        $project->load([
            'company',
            'department',
            'owner',
            'creator',
            'lastEditedBy',
            'tasks' => function ($query) {
                $query->select('id', 'project_id', 'name', 'status', 'percent_complete', 'start_date', 'end_date')
                    ->orderBy('task_order')
                    ->limit(10); // Show first 10, load more via AJAX
            },
            'team.user',
            'team.role',
        ]);

        // Calculate statistics
        $stats = [
            'total_tasks' => $project->tasks()->count(),
            'completed_tasks' => $project->tasks()->where('percent_complete', 100)->count(),
            'overdue_tasks' => $project->tasks()
                ->where('end_date', '<', now())
                ->where('percent_complete', '<', 100)
                ->count(),
            'team_members' => $project->team()->count(),
            'progress_percent' => $project->progress_percent,
            'days_remaining' => $project->days_remaining,
            'budget_remaining' => $project->budget_remaining,
        ];

        return view('projects.show', compact('project', 'stats'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project)
    {
        $companies = Company::select('id', 'name')->orderBy('name')->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        $users = User::select('id', 'first_name', 'last_name')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('projects.edit', compact('project', 'companies', 'departments', 'users'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        DB::beginTransaction();
        
        try {
            $project->update($request->validated());

            DB::commit();

            return redirect()
                ->route('projects.show', $project)
                ->with('success', 'Project updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Failed to update project: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified project from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();

            return redirect()
                ->route('projects.index')
                ->with('success', 'Project deleted successfully.');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Failed to delete project: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft-deleted project.
     */
    public function restore($id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        $project->restore();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project restored successfully.');
    }

    /**
     * Permanently delete a project.
     * This uses CASCADE deletes - single query deletes everything!
     */
    public function forceDelete($id)
    {
        DB::beginTransaction();
        
        try {
            $project = Project::withTrashed()->findOrFail($id);
            
            // The CASCADE foreign keys handle all related deletions automatically!
            // No loops, no multiple queries - database does it all in one transaction
            $project->forceDelete();

            DB::commit();

            return redirect()
                ->route('projects.index')
                ->with('success', 'Project permanently deleted.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to permanently delete project: ' . $e->getMessage());
        }
    }

    /**
     * Update project status in bulk.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'project_ids' => 'required|array',
            'project_ids.*' => 'exists:projects,id',
            'status' => 'required|integer|between:0,6',
        ]);

        DB::transaction(function () use ($request) {
            Project::whereIn('id', $request->project_ids)
                ->update([
                    'status' => $request->status,
                    'last_edited' => now(),
                    'last_edited_by' => auth()->id(),
                ]);
        });

        return back()->with('success', 'Projects updated successfully.');
    }

    /**
     * Export projects to CSV.
     */
    public function export(Request $request)
    {
        $projects = Project::with(['company', 'owner'])
            ->when($request->company_id, fn($q) => $q->forCompany($request->company_id))
            ->when($request->status, fn($q) => $q->byStatus($request->status))
            ->get();

        $filename = 'projects_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($projects) {
            $handle = fopen('php://output', 'w');
            
            // Headers
            fputcsv($handle, [
                'ID', 'Name', 'Company', 'Owner', 'Status', 'Start Date', 
                'End Date', 'Progress %', 'Target Budget', 'Actual Budget'
            ]);

            // Data
            foreach ($projects as $project) {
                fputcsv($handle, [
                    $project->id,
                    $project->name,
                    $project->company->name ?? '',
                    $project->owner->full_name ?? '',
                    $project->status_text,
                    $project->start_date?->format('Y-m-d'),
                    $project->end_date?->format('Y-m-d'),
                    $project->percent_complete,
                    $project->target_budget,
                    $project->actual_budget,
                ]);
            }

            fclose($handle);
        }, $filename);
    }
}
