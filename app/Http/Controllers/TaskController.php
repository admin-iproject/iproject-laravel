<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
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

            // Walk up parent chain recalculating rollups — never touches siblings
            $this->bubbleUp($task);

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
                'task_sprint'       => (bool) $task->task_sprint,
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

            // Handle flag audit columns when flagged state sent from kanban edit
            if ($request->has('flagged')) {
                if ($request->input('flagged')) {
                    $task->flagged_by = $task->flagged_by ?? auth()->id();
                    $task->flagged_at = $task->flagged_at ?? now();
                } else {
                    $task->flagged_by = null;
                    $task->flagged_at = null;
                }
                $task->saveQuietly();
            }

            // Re-level if parent changed
            if ($request->filled('parent_id') && $task->parent_id != $originalParentId) {
                $parent      = Task::find($task->parent_id);
                $task->level = ($parent->level ?? 0) + 1;
                $task->save();
            }

            // Sync task team in same request as update
            $this->syncTaskTeam($task, $request);

            DB::commit();

            // Walk up parent chain recalculating rollups — never touches siblings
            $this->bubbleUp($task);

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

            DB::commit();

            // Bubble up from deleted task's parent
            if ($parent) {
                $this->bubbleUp($parent);
            }

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
     * Walk UP the parent chain from the saved task, recalculating each ancestor
     * from its direct children only. Never looks down more than one level.
     * Max iterations = tree depth (typically 3-5 levels).
     * Zero impact on sibling tasks — only direct ancestors are touched.
     */
    private function bubbleUp(Task $task): void
    {
        try {
            $parentId = $task->parent_id;
            $visited  = [$task->id]; // cycle guard — tracks every ID we've touched

            while ($parentId) {
                // Self-reference or cycle detected — stop immediately
                if (in_array($parentId, $visited)) break;

                $parent = Task::find($parentId);
                if (!$parent) break;

                $visited[] = $parentId;
                $parent->recalculateFromChildren();

                $parentId = $parent->parent_id;
            }
        } catch (\Exception $e) {
            \Log::warning('bubbleUp failed for task ' . $task->id . ': ' . $e->getMessage());
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
            DB::commit();

            $this->bubbleUp($task);

            return back()->with('success', 'Task team updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update task team: ' . $e->getMessage());
        }
    }

    // ── Checklist methods ─────────────────────────────────────────

    /**
     * Return checklist items as JSON for the checklist slideout.
     * Also returns permission flags so JS can render correctly.
     *
     * can_manage = task owner OR project owner → can add/edit/delete items
     * can_uncheck = same as can_manage → only owners can uncheck
     * Everyone can CHECK (mark complete).
     */
    public function getChecklist(Task $task)
    {
        $this->authorize('view', $task);

        $user       = auth()->user();
        $canManage  = $user->id === $task->owner_id
                   || $user->id === $task->project->owner_id
                   || $user->hasRole('super_admin');

        $items = $task->checklist()
            ->with('checkedBy')
            ->orderBy('order')
            ->get()
            ->map(fn($item) => [
                'id'                     => $item->checklist_id,
                'item_name'              => $item->checklist,
                'is_completed'           => $item->is_completed,
                'completed_by_name'      => $item->checkedBy
                    ? trim(($item->checkedBy->first_name ?? '') . ' ' . ($item->checkedBy->last_name ?? ''))
                    : null,
                'completed_at_formatted' => $item->checkeddate
                    ? $item->checkeddate->format('M d, Y g:ia')
                    : null,
                'order'                  => $item->order,
            ]);

        return response()->json([
            'success'    => true,
            'task_name'  => $task->name,
            'can_manage' => $canManage,
            'can_uncheck'=> $canManage,
            'items'      => $items,
        ]);
    }

    public function addChecklistItem(Request $request, Task $task)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate(['item_name' => 'required|string|max:255']);

        $maxOrder = $task->checklist()->max('order') ?? 0;
        $task->checklist()->create([
            'checklist' => $request->item_name,
            'order'     => $maxOrder + 1,
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Checklist item added.');
    }

    public function updateChecklistItem(Request $request, Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate(['item_name' => 'required|string|max:255']);

        $item = $task->checklist()->where('checklist_id', $itemId)->firstOrFail();
        $item->update(['checklist' => $request->item_name]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Checklist item updated.');
    }

    public function deleteChecklistItem(Request $request, Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $task->checklist()->where('checklist_id', $itemId)->firstOrFail()->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Checklist item deleted.');
    }

    public function toggleChecklistItem(Request $request, Task $task, $itemId)
    {
        $this->authorize('view', $task); // everyone can attempt toggle

        $item      = $task->checklist()->where('checklist_id', $itemId)->firstOrFail();
        $user      = auth()->user();
        $canManage = $user->id === $task->owner_id
                  || $user->id === $task->project->owner_id
                  || $user->hasRole('super_admin');

        // Uncheck is restricted to owners only
        if ($item->is_completed && !$canManage) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only the task owner or project manager can uncheck items.',
                ], 403);
            }
            return back()->with('error', 'Only the task owner or project manager can uncheck items.');
        }

        if ($item->is_completed) {
            $item->markIncomplete();
        } else {
            $item->markComplete(auth()->id());
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Checklist item updated.');
    }
    // ── Kanban Board ──────────────────────────────────────────────

    /**
     * GET /tasks/{task}/kanban
     * Returns sprint task metadata + all direct children as kanban cards.
     */
    public function kanban(Task $task)
    {
        $this->authorize('view', $task);

        // Sprint summary
        $sprint = [
            'id'               => $task->id,
            'name'             => $task->name,
            'start_date'       => $task->start_date?->format('Y-m-d'),
            'end_date'         => $task->end_date?->format('Y-m-d'),
            'hours_worked'     => (float) ($task->hours_worked ?? 0),
            'duration'         => (float) ($task->duration ?? 0),
            'duration_type'    => $task->duration_type ?? 1,
            'actual_budget'    => (float) ($task->actual_budget ?? 0),
            'target_budget'    => (float) ($task->target_budget ?? 0),
            'percent_complete' => $task->percent_complete ?? 0,
        ];

        // Load children (one level deep only — sprint cards)
        $children = Task::where('parent_id', $task->id)
            ->with(['team.user', 'checklist'])
            ->orderBy('task_order')
            ->orderBy('id')
            ->get();

        $cards = $children->map(function ($c) {
            $checkTotal = $c->checklist->count();
            $checkDone  = $c->checklist->filter(fn($i) => !is_null($i->checkedby))->count();

            // Bubble-up is_overdue
            $isOverdue = $c->end_date && $c->end_date->isPast() && ($c->percent_complete ?? 0) < 100;

            return [
                'id'               => $c->id,
                'name'             => $c->name,
                'description'      => $c->description,
                'owner_id'         => $c->owner_id,
                'task_assigned'    => $c->task_assigned,
                'phase'            => $c->phase,
                'flagged'          => (bool) $c->flagged,
                'status'           => $c->status,
                'priority'         => $c->priority ?? 5,
                'percent_complete' => $c->percent_complete ?? 0,
                'start_date'       => $c->start_date?->format('Y-m-d'),
                'end_date'         => $c->end_date?->format('Y-m-d'),
                'hours_worked'     => (float) ($c->hours_worked ?? 0),
                'duration'         => (float) ($c->duration ?? 0),
                'duration_type'    => $c->duration_type ?? 1,
                'actual_budget'    => (float) ($c->actual_budget ?? 0),
                'target_budget'    => (float) ($c->target_budget ?? 0),
                'access'           => $c->access ?? 0,
                'is_overdue'       => $isOverdue,
                'checklist_total'  => $checkTotal,
                'checklist_done'   => $checkDone,
                'team'             => $c->team->map(fn($m) => [
                    'user_id'  => $m->user_id,
                    'name'     => trim(($m->user->first_name ?? '') . ' ' . ($m->user->last_name ?? '')),
                    'initials' => strtoupper(substr($m->user->first_name ?? '?', 0, 1) . substr($m->user->last_name ?? '', 0, 1)),
                    'is_owner' => (bool) ($m->is_owner ?? false),
                ])->values(),
            ];
        })->values();

        return response()->json(['success' => true, 'sprint' => $sprint, 'cards' => $cards]);
    }

    /**
     * PATCH /tasks/{task}/move
     * Kanban drag-drop: updates phase, flagged state, and optionally percent_complete.
     */
    public function moveCard(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'phase'            => 'nullable|integer',
            'flagged'          => 'nullable|boolean',
            'task_order'       => 'nullable|integer',
            'percent_complete' => 'nullable|integer|between:0,100',
            'priority'         => 'nullable|integer|between:0,10',
        ]);

        DB::beginTransaction();
        try {
            if (array_key_exists('phase', $validated))    $task->phase      = $validated['phase'];
            if (array_key_exists('flagged', $validated))  {
                $task->flagged = $validated['flagged'] ? 1 : 0;
                if ($validated['flagged']) {
                    $task->flagged_by = auth()->id();
                    $task->flagged_at = now();
                } else {
                    $task->flagged_by = null;
                    $task->flagged_at = null;
                }
            }
            if (array_key_exists('task_order', $validated))       $task->task_order       = $validated['task_order'];
            if (array_key_exists('percent_complete', $validated)) $task->percent_complete = $validated['percent_complete'];
            if (array_key_exists('priority', $validated))         $task->priority         = $validated['priority'];

            $task->saveQuietly();
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ── Task Log methods ──────────────────────────────────────────

    /**
     * Return all log entries for a task as JSON (for the log modal).
     * Returns: logs, phase list, task team (for assigned-user radio), cost data (owner only).
     */
    public function getLogs(Task $task)
    {
        $this->authorize('view', $task);

        $authUser     = auth()->user();
        $canViewCosts = $authUser->can('viewHourlyCosts', $task);

        // Parse project phases — stored as JSON array of "10|Design" strings
        $rawPhases = $task->project->phases ?? [];
        if (is_string($rawPhases)) {
            $rawPhases = json_decode($rawPhases, true) ?? [];
        }

        // Build task team for the "assigned user" radio list
        $task->loadMissing('team.user');
        $taskTeam = $task->team->map(fn($m) => [
            'user_id'  => $m->user_id,
            'name'     => trim(($m->user->first_name ?? '') . ' ' . ($m->user->last_name ?? '')),
            'initials' => strtoupper(substr($m->user->first_name ?? '?', 0, 1) . substr($m->user->last_name ?? '', 0, 1)),
            'is_owner' => (bool) ($m->is_owner ?? false),
        ])->values();

        $logs = TaskLog::where('task_log_task', $task->id)
            ->with(['creator', 'assignedUser'])
            ->orderBy('task_log_date', 'desc')
            ->get()
            ->map(fn($log) => [
                'id'               => $log->task_log_id,
                'name'             => $log->task_log_name,
                'description'      => $log->task_log_description,
                'hours'            => (float) $log->task_log_hours,
                'date'             => $log->task_log_date?->format('Y-m-d'),
                'date_formatted'   => $log->task_log_date?->format('M d, Y'),
                'costcode'         => $log->task_log_costcode,
                'phase'            => $log->task_log_phase,
                'risk'             => $log->task_log_risk,
                'percent_complete' => $log->task_percent_complete,
                'creator_id'       => $log->task_log_creator,
                'creator_name'     => trim(($log->creator->first_name ?? '') . ' ' . ($log->creator->last_name ?? '')),
                'assigned_id'      => $log->task_log_assigned,
                'assigned_name'    => $log->task_log_assigned
                                        ? trim(($log->assignedUser->first_name ?? '') . ' ' . ($log->assignedUser->last_name ?? ''))
                                        : null,
                'hourly_rate'      => $canViewCosts ? (float) ($log->creator->hourly_cost ?? 0) : null,
                'cost'             => $canViewCosts ? $log->cost : null,
            ]);

        $totalCost = $canViewCosts ? round($logs->sum('cost'), 2) : null;

        return response()->json([
            'success'          => true,
            'task_name'        => $task->name,
            'flagged'          => (bool) $task->flagged,
            'flag_tooltip'     => $task->flag_tooltip ?? '',
            'can_view_costs'   => $canViewCosts,
            'my_hourly_rate'   => (float) ($authUser->hourly_cost ?? 0),
            'task_assigned'    => $task->task_assigned,
            'task_phase'       => $task->phase,
            'task_percent'     => $task->percent_complete,
            'task_team'        => $taskTeam,
            'phases'           => $rawPhases,
            'logs'             => $logs,
            'total_cost'       => $totalCost,
        ]);
    }

    /**
     * Store a new task log entry.
     * - Saves phase name as string (e.g. "Design")
     * - Updates task_assigned if provided ("pass the ball")
     * - Updates percent_complete from most recent log entry
     * - Triggers bubbleUp for hours/budget rollup
     */
    public function logTime(Request $request, Task $task)
    {
        $this->authorize('logTime', $task);

        $validated = $request->validate([
            'task_log_name'        => 'nullable|string|max:255',
            'task_log_description' => 'nullable|string',
            'task_log_hours'       => 'required|numeric|min:0|max:999',
            'task_log_date'        => 'required|date',
            'task_log_costcode'    => 'nullable|string|max:8',
            'task_log_phase'       => 'nullable|string|max:100',
            'task_log_risk'        => 'nullable|boolean',
            'task_percent_complete'=> 'nullable|integer|min:0|max:100',
            'task_assigned'        => 'nullable|integer|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $costCode = $validated['task_log_costcode']
                ?? auth()->user()->cost_code
                ?? null;

            $log = TaskLog::create([
                'task_log_task'        => $task->id,
                'task_log_name'        => $validated['task_log_name']        ?? null,
                'task_log_description' => $validated['task_log_description'] ?? null,
                'task_log_creator'     => auth()->id(),
                'task_log_hours'       => $validated['task_log_hours'],
                'task_log_date'        => $validated['task_log_date'],
                'task_log_costcode'    => $costCode,
                'task_log_phase'       => $validated['task_log_phase']       ?? null,
                'task_log_risk'        => $validated['task_log_risk']        ?? 0,
                'task_log_assigned'    => $validated['task_assigned']        ?? $task->task_assigned ?? null,
                'task_percent_complete'=> $validated['task_percent_complete'] ?? null,
                'task_log_from_portal' => 'Yes',
            ]);

            // "Pass the ball" — update who is currently working on this task
            if (!empty($validated['task_assigned'])) {
                $task->task_assigned = $validated['task_assigned'];
            }

            // Sync task.phase from the log's phase name — find the integer index
            // in the project phases array e.g. ["10|Design","25|Develop"] → "Design" = index 0
            if (!empty($validated['task_log_phase'])) {
                $rawPhases = $task->project->phases ?? [];
                if (is_string($rawPhases)) {
                    $rawPhases = json_decode($rawPhases, true) ?? [];
                }
                foreach ($rawPhases as $index => $entry) {
                    $parts = explode('|', $entry, 2);
                    if (count($parts) === 2 && trim($parts[1]) === trim($validated['task_log_phase'])) {
                        $task->phase = $index;
                        break;
                    }
                }
            }

            // Flag state — driven by this log entry
            $raised = !empty($validated['task_log_risk']);
            $task->flagged    = $raised;
            $task->flagged_by = $raised ? auth()->id() : null;
            $task->flagged_at = $raised ? now()        : null;

            // percent_complete = value from the most recent log entry
            if ($validated['task_percent_complete'] !== null) {
                $task->percent_complete = $validated['task_percent_complete'];
                if ($validated['task_percent_complete'] >= 100 && !$task->end_date) {
                    $task->end_date = $validated['task_log_date'];
                }
            }

            // Sum all hours including the new entry
            $totalHours = TaskLog::where('task_log_task', $task->id)->sum('task_log_hours');
            $task->hours_worked    = $totalHours;
            $task->task_lastupdate = $validated['task_log_date'];

            // Compute actual_budget = sum of (hours × creator's hourly_cost) across all logs
            $task->actual_budget = (float) DB::table('task_log')
                ->join('users', 'task_log.task_log_creator', '=', 'users.id')
                ->where('task_log.task_log_task', $task->id)
                ->sum(DB::raw('task_log.task_log_hours * users.hourly_cost'));

            $task->saveQuietly();

            DB::commit();

            // bubbleUp suspended — re-enable once all CRUD modules are stable
            // $this->bubbleUp($task);

            return response()->json([
                'success' => true,
                'message' => 'Time logged successfully.',
                'log'     => [
                    'id'    => $log->task_log_id,
                    'hours' => $log->task_log_hours,
                    'date'  => $log->task_log_date?->format('M d, Y'),
                ],
                'task'    => [
                    'hours_worked'     => $task->hours_worked,
                    'percent_complete' => $task->percent_complete,
                    'flagged'          => (bool) $task->flagged,
                    'flag_tooltip'     => $task->flag_tooltip,
                    'task_assigned'    => $task->task_assigned,
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to log time: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a task log entry and recalculate task totals.
     */
    public function deleteLog(Request $request, Task $task, TaskLog $log)
    {
        $this->authorize('logTime', $task);

        // Only creator or project owner can delete
        if ($log->task_log_creator !== auth()->id()) {
            $this->authorize('manageTeam', $task);
        }

        DB::beginTransaction();
        try {
            $log->delete();

            // Recalculate hours_worked, actual_budget, and percent_complete from remaining logs
            $totalHours = TaskLog::where('task_log_task', $task->id)->sum('task_log_hours');
            $maxPct     = TaskLog::where('task_log_task', $task->id)
                ->whereNotNull('task_percent_complete')
                ->max('task_percent_complete') ?? $task->percent_complete;

            $task->hours_worked   = $totalHours;
            $task->percent_complete = $maxPct;

            // Recompute actual_budget from remaining logs × creator rates
            $task->actual_budget = (float) DB::table('task_log')
                ->join('users', 'task_log.task_log_creator', '=', 'users.id')
                ->where('task_log.task_log_task', $task->id)
                ->sum(DB::raw('task_log.task_log_hours * users.hourly_cost'));

            $task->saveQuietly();

            DB::commit();

            // bubbleUp suspended — re-enable once all CRUD modules are stable
            // $this->bubbleUp($task);

            return response()->json(['success' => true, 'message' => 'Log entry deleted.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}