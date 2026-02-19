<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\Contact;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Task::class);

        $query = Task::with(['owner', 'project', 'parent'])
            ->whereNull('parent_id');

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderBy('task_order')->orderBy('created_at', 'desc')->paginate(50);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Request $request)
    {
        $this->authorize('create', Task::class);

        $projectId = $request->get('project_id');
        $parentId  = $request->get('parent_id');

        $project = $projectId ? Project::findOrFail($projectId) : null;
        $parent  = $parentId  ? Task::findOrFail($parentId)    : null;

        if ($parent) {
            $this->authorize('createChild', $parent);
        }

        $projects = Project::where('active', true)->orderBy('name')->get();
        $users    = $project ? User::where('company_id', $project->company_id)->get() : User::all();
        $contacts = Contact::orderBy('contact_last_name')->get();

        return view('tasks.create', compact('project', 'parent', 'projects', 'users', 'contacts'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        DB::beginTransaction();
        try {
            $task = Task::create($request->validated());

            if ($task->parent_id) {
                $parent = Task::find($task->parent_id);
                $task->level = ($parent->level ?? 0) + 1;
                $task->save();
            }

            // Assign task team in same request as create
            $this->syncTaskTeam($task, $request);

            DB::commit();

            // Calculate target budget after commit — wrapped so it never kills the save
            try {
                if (method_exists($task, 'updateTargetBudget')) {
                    $task->updateTargetBudget();
                }
            } catch (\Exception $budgetEx) {
                \Log::warning('Budget recalc failed for task ' . $task->id . ': ' . $budgetEx->getMessage());
            }

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully.',
                    'task'    => $task->load('owner', 'project'),
                ], 201);
            }

            return redirect()
                ->route('projects.show', $task->project_id)
                ->with('success', 'Task created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create task: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to create task: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified task.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        $task->load([
            'project',
            'owner',
            'parent',
            'children',
            'team.user',
            'checklist' => fn($q) => $q->ordered(),
            'dependencies',
            'timeLogs.user',
        ]);

        $canViewBudget = auth()->user()->can('viewBudget', $task);
        $canViewCosts  = auth()->user()->can('viewHourlyCosts', $task);

        return view('tasks.show', compact('task', 'canViewBudget', 'canViewCosts'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $task->load(['project', 'parent', 'team']);

        $users           = User::where('company_id', $task->project->company_id)->get();
        $contacts        = Contact::orderBy('contact_last_name')->get();
        $potentialParents = Task::where('project_id', $task->project_id)
            ->where('id', '!=', $task->id)
            ->whereNull('parent_id')
            ->get();

        return view('tasks.edit', compact('task', 'users', 'contacts', 'potentialParents'));
    }

    /**
     * Return task data as JSON for the edit modal (AJAX).
     */
    public function editData(Task $task)
    {
        $this->authorize('update', $task);

        $task->load([
            'team.user',
            'project.team.user',
        ]);

        // Build task team: user_id => hours
        $taskTeam = $task->team->map(fn($m) => [
            'user_id'  => $m->user_id,
            'hours'    => (float) ($m->hours ?? 0),
            'is_owner' => (bool) ($m->is_owner ?? false),
        ])->values();

        // Build project team for the member picker (who can be assigned)
        $projectTeam = $task->project->team->map(fn($m) => [
            'user_id'      => $m->user_id,
            'name'         => trim(($m->user->first_name ?? '') . ' ' . ($m->user->last_name ?? '')),
            'initials'     => strtoupper(substr($m->user->first_name ?? '?', 0, 1) . substr($m->user->last_name ?? '', 0, 1)),
            'skill_name'   => $m->skill->name ?? null,
            'hourly_cost'  => (float) ($m->hourly_cost ?? $m->user->hourly_cost ?? 0),
        ])->values();

        return response()->json([
            'success' => true,
            'task'    => [
                'id'                => $task->id,
                'name'              => $task->name,
                'description'       => $task->description,
                'owner_id'          => $task->owner_id,
                'parent_id'         => $task->parent_id,
                'status'            => $task->status,
                'priority'          => $task->priority ?? 5,
                'percent_complete'  => $task->percent_complete ?? 0,
                'start_date'        => $task->start_date?->format('Y-m-d'),
                'end_date'          => $task->end_date?->format('Y-m-d'),
                'duration'          => $task->duration,
                'duration_type'     => $task->duration_type ?? 1,
                'target_budget'     => (float) ($task->target_budget ?? 0),
                'cost_code'         => $task->cost_code,
                'phase'             => $task->phase,
                'related_url'       => $task->related_url,
                'milestone'         => (bool) $task->milestone,
                'access'            => $task->access ?? 0,
                'task_ignore_budget'=> (bool) $task->task_ignore_budget,
            ],
            'taskTeam'    => $taskTeam,
            'projectTeam' => $projectTeam,
        ]);
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        // Capture original parent_id BEFORE update clears getOriginal()
        $originalParentId = $task->parent_id;

        DB::beginTransaction();
        try {
            set_time_limit(30); // prevent runaway budget recalc from timing out

            $task->update($request->validated());

            // Re-level if parent changed
            if ($request->filled('parent_id') && $task->parent_id != $originalParentId) {
                $parent      = Task::find($task->parent_id);
                $task->level = ($parent->level ?? 0) + 1;
                $task->save();
            }

            // Sync task team in same request as update
            $this->syncTaskTeam($task, $request);

            DB::commit();

            // Recalculate budgets AFTER commit — wrapped so a budget error never kills the save
            try {
                $task->refresh();
                if (method_exists($task, 'updateTargetBudget')) {
                    $task->updateTargetBudget();
                }
                if ($task->parent && method_exists($task->parent, 'updateTargetBudget')) {
                    $task->parent->updateTargetBudget();
                }
                if ($task->parent && method_exists($task->parent, 'updateActualBudget')) {
                    $task->parent->updateActualBudget();
                }
            } catch (\Exception $budgetEx) {
                // Budget recalc failed — log it but don't fail the save
                \Log::warning('Budget recalc failed for task ' . $task->id . ': ' . $budgetEx->getMessage());
            }

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task updated successfully.',
                    'task'    => $task->load('owner', 'project'),
                ]);
            }

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', 'Task updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update task: ' . $e->getMessage(),
                ], 500);
            }

            return back()->withInput()->with('error', 'Failed to update task: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('delete', $task);

        $projectId = $task->project_id;
        $parent    = $task->parent;

        DB::beginTransaction();
        try {
            $task->delete();

            if ($parent) {
                if (method_exists($parent, 'updateTargetBudget')) $parent->updateTargetBudget();
                if (method_exists($parent, 'updateActualBudget')) $parent->updateActualBudget();
            }

            DB::commit();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task deleted successfully.',
                ]);
            }

            return redirect()
                ->route('projects.show', $projectId)
                ->with('success', 'Task deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete task: ' . $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Failed to delete task: ' . $e->getMessage());
        }
    }

    /**
     * Sync task team — used by both store() and update().
     * Clears existing team and rebuilds from request data.
     * Handles split_evenly mode.
     */
    private function syncTaskTeam(Task $task, $request): void
    {
        // If no team data submitted, leave existing team intact
        if (!$request->has('team_members') && !$request->has('owner_hours')) {
            return;
        }

        // Clear existing team
        $task->team()->delete();

        $members     = $request->team_members ?? [];
        $memberCount = count($members) + 1; // +1 for owner

        // Resolve hours — split evenly or individual
        if ($request->boolean('split_evenly') && $request->filled('total_hours')) {
            $hoursEach  = round($request->total_hours / $memberCount, 2);
            $ownerHours = $hoursEach;
            $teamHours  = array_fill(0, count($members), $hoursEach);
        } else {
            $ownerHours = (float) ($request->owner_hours ?? 0);
            $teamHours  = $request->team_hours ?? [];
        }

        // Add owner as team member
        $task->team()->create([
            'user_id'     => $task->owner_id,
            'hours'       => $ownerHours,
            'is_owner'    => true,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);

        // Add additional team members
        foreach ($members as $index => $userId) {
            if ($userId == $task->owner_id) continue; // skip if owner added twice
            $task->team()->create([
                'user_id'     => $userId,
                'hours'       => (float) ($teamHours[$index] ?? 0),
                'is_owner'    => false,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
            ]);
        }
    }

    /**
     * Update task team via dedicated endpoint (slideout / standalone).
     */
    public function updateTeam(Request $request, Task $task)
    {
        $this->authorize('manageTeam', $task);

        $request->validate([
            'owner_hours'    => 'nullable|numeric|min:0',
            'team_members'   => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'team_hours'     => 'nullable|array',
            'team_hours.*'   => 'numeric|min:0',
            'split_evenly'   => 'nullable|boolean',
            'total_hours'    => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $this->syncTaskTeam($task, $request);
            if (method_exists($task, 'updateTargetBudget')) $task->updateTargetBudget();
            DB::commit();

            return back()->with('success', 'Task team updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update task team: ' . $e->getMessage());
        }
    }

    // ── Checklist methods ─────────────────────────────────────────

    public function addChecklistItem(Request $request, Task $task)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate(['item_name' => 'required|string|max:255']);

        $maxOrder = $task->checklist()->max('sort_order') ?? 0;
        $task->checklist()->create([
            'item_name'    => $request->item_name,
            'sort_order'   => $maxOrder + 1,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Checklist item added.');
    }

    public function updateChecklistItem(Request $request, Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate(['item_name' => 'required|string|max:255']);

        $item = $task->checklist()->findOrFail($itemId);
        $item->update(['item_name' => $request->item_name]);

        return back()->with('success', 'Checklist item updated.');
    }

    public function deleteChecklistItem(Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $task->checklist()->findOrFail($itemId)->delete();

        return back()->with('success', 'Checklist item deleted.');
    }

    public function toggleChecklistItem(Task $task, $itemId)
    {
        $this->authorize('checkChecklistItem', $task);

        $item = $task->checklist()->findOrFail($itemId);

        if ($item->is_completed) {
            $item->markIncomplete();
        } else {
            $item->markComplete(auth()->id());
        }

        return back()->with('success', 'Checklist item updated.');
    }
}
