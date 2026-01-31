<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'parent_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'duration',
        'duration_type',
        'owner_id',
        'status',
        'percent_complete',
        'priority',
        'milestone',
        'access',
        'related_url',
        'notify',
        'phase',
        'risk',
        'contact_id',
        'cost_code',
        'type',
        'target_budget',
        'task_order',
        'creator_id',
        'last_edited',
        'last_edited_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'duration' => 'decimal:2',
        'status' => 'integer',
        'percent_complete' => 'integer',
        'priority' => 'integer',
        'milestone' => 'integer',
        'access' => 'integer',
        'notify' => 'integer',
        'phase' => 'integer',
        'risk' => 'integer',
        'type' => 'integer',
        'target_budget' => 'decimal:2',
        'task_order' => 'integer',
        'last_edited' => 'datetime',
    ];

    /**
     * Get the project that owns the task.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the parent task.
     */
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Get the child tasks.
     */
    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    /**
     * Get the owner of the task.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the creator of the task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the contact associated with the task.
     */
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    /**
     * Get the assigned users for the task.
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'user_tasks')
                    ->withPivot('percent_effort')
                    ->withTimestamps();
    }

    /**
     * Get the task logs.
     */
    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    /**
     * Get the task checklist items.
     */
    public function checklist()
    {
        return $this->hasMany(TaskChecklist::class)->orderBy('order');
    }

    /**
     * Get the tasks that this task depends on.
     */
    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'depends_on_task_id')
                    ->withPivot('dependency_type', 'lag_days')
                    ->withTimestamps();
    }

    /**
     * Get the tasks that depend on this task.
     */
    public function dependents()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'depends_on_task_id', 'task_id')
                    ->withPivot('dependency_type', 'lag_days')
                    ->withTimestamps();
    }

    /**
     * Get the files for the task.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Scope a query to only include active tasks.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by project.
     */
    public function scopeOfProject($query, $projectId)
    {
        return $query->where('project_id', $projectId);
    }

    /**
     * Scope a query to only include milestones.
     */
    public function scopeMilestones($query)
    {
        return $query->where('milestone', 1);
    }

    /**
     * Check if the task is overdue.
     */
    public function isOverdue()
    {
        return $this->end_date && $this->end_date->isPast() && $this->percent_complete < 100;
    }

    /**
     * Get total hours logged.
     */
    public function getTotalHoursAttribute()
    {
        return $this->logs()->sum('hours');
    }
}
