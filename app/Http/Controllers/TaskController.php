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
            ->whereNull('parent_id'); // Top-level tasks only

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by owner
        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        // Search
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
        $parentId = $request->get('parent_id');

        $project = $projectId ? Project::findOrFail($projectId) : null;
        $parent = $parentId ? Task::findOrFail($parentId) : null;

        // If creating child task, check permission
        if ($parent) {
            $this->authorize('createChild', $parent);
        }

        $projects = Project::where('active', true)->orderBy('name')->get();
        $users = $project ? User::where('company_id', $project->company_id)->get() : User::all();
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
            // Create the task
            $task = Task::create($request->validated());

            // Calculate level if parent exists
            if ($task->parent_id) {
                $parent = Task::find($task->parent_id);
                $task->level = ($parent->level ?? 0) + 1;
                $task->save();
            }

            // Assign task team if provided
            if ($request->filled('team_members')) {
                $this->assignTaskTeam($task, $request);
            }

            // Calculate target budget from team
            if ($task->team()->exists()) {
                $task->updateTargetBudget();
            }

            DB::commit();

            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task created successfully.',
                    'task' => $task->load('owner', 'project')
                ], 201);
            }

            return redirect()
                ->route('projects.show', $task->project_id)
                ->with('success', 'Task created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Return JSON error for AJAX requests
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create task: ' . $e->getMessage()
                ], 500);
            }
            
            return back()
                ->withInput()
                ->with('error', 'Failed to create task: ' . $e->getMessage());
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
            'checklist' => function($query) {
                $query->ordered();
            },
            'dependencies',
            'timeLogs.user',
        ]);

        $canViewBudget = auth()->user()->can('viewBudget', $task);
        $canViewCosts = auth()->user()->can('viewHourlyCosts', $task);

        return view('tasks.show', compact('task', 'canViewBudget', 'canViewCosts'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);

        $task->load(['project', 'parent', 'team']);

        $users = User::where('company_id', $task->project->company_id)->get();
        $contacts = Contact::orderBy('contact_last_name')->get();
        $potentialParents = Task::where('project_id', $task->project_id)
            ->where('id', '!=', $task->id)
            ->whereNull('parent_id')
            ->get();

        return view('tasks.edit', compact('task', 'users', 'contacts', 'potentialParents'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        DB::beginTransaction();
        try {
            $task->update($request->validated());

            // Recalculate level if parent changed
            if ($request->filled('parent_id') && $task->parent_id != $task->getOriginal('parent_id')) {
                $parent = Task::find($task->parent_id);
                $task->level = ($parent->level ?? 0) + 1;
                $task->save();
            }

            // If task became a parent, update budget
            if ($task->hasChildren()) {
                $task->updateTargetBudget();
            }

            // Bubble up changes to parent
            if ($task->parent) {
                $task->parent->updateTargetBudget();
                $task->parent->updateActualBudget();
            }

            DB::commit();

            return redirect()
                ->route('tasks.show', $task)
                ->with('success', 'Task updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update task: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $projectId = $task->project_id;
        $parent = $task->parent;

        DB::beginTransaction();
        try {
            $task->delete();

            // Update parent budgets if exists
            if ($parent) {
                $parent->updateTargetBudget();
                $parent->updateActualBudget();
            }

            DB::commit();

            return redirect()
                ->route('projects.show', $projectId)
                ->with('success', 'Task deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Failed to delete task: ' . $e->getMessage());
        }
    }

    /**
     * Update task team assignments.
     */
    public function updateTeam(Request $request, Task $task)
    {
        $this->authorize('manageTeam', $task);

        $request->validate([
            'owner_hours' => 'nullable|numeric|min:0',
            'team_members' => 'nullable|array',
            'team_members.*' => 'exists:users,id',
            'team_hours' => 'nullable|array',
            'team_hours.*' => 'numeric|min:0',
            'split_evenly' => 'nullable|boolean',
            'total_hours' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Clear existing team
            $task->team()->delete();

            // Handle split evenly option
            if ($request->boolean('split_evenly') && $request->filled('total_hours')) {
                $memberCount = count($request->team_members ?? []) + 1; // +1 for owner
                $hoursPerMember = $request->total_hours / $memberCount;
                
                $ownerHours = $hoursPerMember;
                $teamHours = array_fill(0, count($request->team_members ?? []), $hoursPerMember);
            } else {
                $ownerHours = $request->owner_hours ?? 0;
                $teamHours = $request->team_hours ?? [];
            }

            // Add owner to team
            $task->team()->create([
                'user_id' => $task->owner_id,
                'hours' => $ownerHours,
                'is_owner' => true,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
            ]);

            // Add team members
            if ($request->filled('team_members')) {
                foreach ($request->team_members as $index => $userId) {
                    $task->team()->create([
                        'user_id' => $userId,
                        'hours' => $teamHours[$index] ?? 0,
                        'is_owner' => false,
                        'assigned_by' => auth()->id(),
                        'assigned_at' => now(),
                    ]);
                }
            }

            // Recalculate budget from team
            $task->updateTargetBudget();

            DB::commit();

            return back()->with('success', 'Task team updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update task team: ' . $e->getMessage());
        }
    }

    /**
     * Helper: Assign task team on creation
     */
    private function assignTaskTeam(Task $task, StoreTaskRequest $request): void
    {
        // Add owner with hours
        $task->team()->create([
            'user_id' => $task->owner_id,
            'hours' => $request->owner_hours ?? 0,
            'is_owner' => true,
            'assigned_by' => auth()->id(),
            'assigned_at' => now(),
        ]);

        // Add team members
        if ($request->filled('team_members')) {
            foreach ($request->team_members as $index => $userId) {
                $task->team()->create([
                    'user_id' => $userId,
                    'hours' => $request->team_hours[$index] ?? 0,
                    'is_owner' => false,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                ]);
            }
        }
    }

    /**
     * Add a checklist item to the task.
     */
    public function addChecklistItem(Request $request, Task $task)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate([
            'item_name' => 'required|string|max:255',
        ]);

        $maxOrder = $task->checklist()->max('sort_order') ?? 0;

        $task->checklist()->create([
            'item_name' => $request->item_name,
            'sort_order' => $maxOrder + 1,
            'is_completed' => false,
        ]);

        return back()->with('success', 'Checklist item added.');
    }

    /**
     * Update a checklist item.
     */
    public function updateChecklistItem(Request $request, Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $request->validate([
            'item_name' => 'required|string|max:255',
        ]);

        $item = $task->checklist()->findOrFail($itemId);
        $item->update(['item_name' => $request->item_name]);

        return back()->with('success', 'Checklist item updated.');
    }

    /**
     * Delete a checklist item.
     */
    public function deleteChecklistItem(Task $task, $itemId)
    {
        $this->authorize('manageChecklist', $task);

        $item = $task->checklist()->findOrFail($itemId);
        $item->delete();

        return back()->with('success', 'Checklist item deleted.');
    }

    /**
     * Toggle checklist item completion.
     */
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
