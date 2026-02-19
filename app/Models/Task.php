<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'parent_id',
        'task_linked',
        'name',
        'description',
        'start_date',
        'end_date',
        'duration',
        'duration_type',
        'owner_id',
        'task_assigned',
        'assign_hour_type',
        'status',
        'percent_complete',
        'priority',
        'milestone',
        'access',
        'related_url',
        'tag_it_url',
        'task_sprint',
        'notify',
        'phase',
        'task_phase',
        'task_category',
        'risk',
        'contact_id',
        'cost_code',
        'type',
        'target_budget',
        'actual_budget',
        'task_ignore_budget',
        'task_order',
        'level',
        'creator_id',
        'last_edited',
        'last_edited_by',
        'task_lastupdate',
        'task_level',
        'task_level_refer',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'last_edited' => 'datetime',
        'task_lastupdate' => 'datetime',
        'task_ignore_budget' => 'boolean',
        'duration' => 'decimal:2',
        'target_budget' => 'decimal:2',
        'actual_budget' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'task_assigned');
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function lastEditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    /**
     * Get the task team members
     */
    public function team(): HasMany
    {
        return $this->hasMany(TaskTeam::class);
    }

    /**
     * Get the task checklist items
     */
    public function checklist(): HasMany
    {
        return $this->hasMany(TaskChecklist::class);
    }

    /**
     * Get task dependencies (tasks this task depends on)
     */
    public function dependencies(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'task_id');
    }

    /**
     * Get tasks that depend on this task
     */
    public function dependents(): HasMany
    {
        return $this->hasMany(TaskDependency::class, 'depends_on_task_id');
    }

    /**
     * Get task time logs
     */
    public function timeLogs(): HasMany
    {
        return $this->hasMany(TaskLog::class);
    }

    /**
     * Get all descendants (children, grandchildren, etc.)
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors (parent, grandparent, etc.)
     */
    public function ancestors()
    {
        return $this->parent ? $this->parent->ancestors()->prepend($this->parent) : collect();
    }

    /**
     * Budget Calculation Methods
     */

    /**
     * Calculate target budget from team assignments
     */
    public function calculateTargetFromTeam(): float
    {
        return $this->team()
            ->join('users', 'task_team.user_id', '=', 'users.id')
            ->selectRaw('SUM(task_team.hours * users.hourly_cost) as total')
            ->value('total') ?? 0;
    }

    /**
     * Calculate target budget from children (for parent tasks)
     */
    public function calculateTargetFromChildren(): float
    {
        return $this->children()
            ->where('task_ignore_budget', false)
            ->sum('target_budget');
    }

    /**
     * Calculate actual budget from own time logs + stored children actual_budget.
     * Does NOT recurse — relies on children already having correct actual_budget values.
     */
    public function calculateActualBudget(): float
    {
        $ownCost = $this->timeLogs()
            ->join('users', 'task_log.user_id', '=', 'users.id')
            ->selectRaw('SUM(task_log.hours * users.hourly_cost) as total')
            ->value('total') ?? 0;

        $childrenCost = $this->children()
            ->where('task_ignore_budget', false)
            ->sum('actual_budget');

        return $ownCost + $childrenCost;
    }

    /**
     * Update target budget for this task only (no recursion into children).
     * Then walk UP the parent chain — each ancestor recalculates itself
     * from its direct children only. Stops when there is no parent.
     * Max depth = tree depth (typically 3-4 levels), never touches siblings.
     */
    public function updateTargetBudget(): void
    {
        if ($this->hasChildren()) {
            // Parent task: sum direct children only (children already saved)
            $this->target_budget = $this->calculateTargetFromChildren();
        } else {
            // Leaf task: derive from team assignment
            if ($this->team()->exists()) {
                $this->target_budget = $this->calculateTargetFromTeam();
            }
            // Otherwise leave manual entry intact
        }

        $this->saveQuietly();

        // Walk up ONE level — let parent recalculate itself (not this whole tree)
        if ($this->parent_id) {
            $parent = Task::find($this->parent_id); // fresh query, not cached relation
            if ($parent) {
                $parent->updateTargetBudget();
            }
        }
    }

    /**
     * Update actual budget for this task only.
     * Uses a single SUM query on direct children — no recursive PHP loops.
     * Then walks UP the parent chain.
     */
    public function updateActualBudget(): void
    {
        // Own time logs cost
        $ownCost = $this->timeLogs()
            ->join('users', 'task_log.user_id', '=', 'users.id')
            ->selectRaw('SUM(task_log.hours * users.hourly_cost) as total')
            ->value('total') ?? 0;

        // Children actual budgets — already stored on each child row, just sum them
        $childrenCost = $this->children()
            ->where('task_ignore_budget', false)
            ->sum('actual_budget');

        $this->actual_budget = $ownCost + $childrenCost;
        $this->saveQuietly();

        // Walk up ONE level
        if ($this->parent_id) {
            $parent = Task::find($this->parent_id);
            if ($parent) {
                $parent->updateActualBudget();
            }
        }
    }

    /**
     * Check if task has children
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Helper Methods
     */

    /**
     * Get status text
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            0 => 'Not Started',
            1 => 'In Progress',
            2 => 'On Hold',
            3 => 'Complete',
            4 => 'Cancelled',
            default => 'Unknown',
        };
    }

    /**
     * Get priority level text
     */
    public function getPriorityTextAttribute(): string
    {
        return match(true) {
            $this->priority >= 7 => 'High',
            $this->priority >= 4 => 'Medium',
            default => 'Low',
        };
    }

    /**
     * Check if task is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return $this->end_date->isPast() && $this->percent_complete < 100;
    }

    /**
     * Check if task is owned by user
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    /**
     * Check if user is on task team
     */
    public function hasTeamMember(User $user): bool
    {
        return $this->team()->where('user_id', $user->id)->exists();
    }

    /**
     * Scopes
     */

    /**
     * Scope for tasks by status
     */
    public function scopeByStatus($query, int $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for completed tasks
     */
    public function scopeCompleted($query)
    {
        return $query->where('percent_complete', 100);
    }

    /**
     * Scope for overdue tasks
     */
    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
                    ->where('percent_complete', '<', 100);
    }

    /**
     * Scope for high priority tasks
     */
    public function scopeHighPriority($query)
    {
        return $query->where('priority', '>=', 7);
    }

    /**
     * Scope for tasks owned by user
     */
    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('owner_id', $userId);
    }

    /**
     * Scope for top-level tasks (no parent)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id')
                    ->orWhereColumn('parent_id', 'id');
    }
}
