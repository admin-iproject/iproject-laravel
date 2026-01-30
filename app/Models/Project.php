<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'name',
        'short_name',
        'owner_id',
        'url',
        'start_date',
        'end_date',
        'actual_end_date',
        'status',
        'percent_complete',
        'color_identifier',
        'active',
        'private',
        'description',
        'target_budget',
        'actual_budget',
        'phases',
        'categories',
        'contract',
        'priority',
        'additional_tasks',
        'allocation_alert_range',
        'task_dates',
        'creator_id',
        'last_edited',
        'last_edited_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'status' => 'integer',
        'percent_complete' => 'integer',
        'active' => 'boolean',
        'private' => 'boolean',
        'target_budget' => 'decimal:2',
        'actual_budget' => 'decimal:2',
        'priority' => 'integer',
        'last_edited' => 'datetime',
    ];

    /**
     * Get the company that owns the project.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that owns the project.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the owner of the project.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the creator of the project.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get the user who last edited the project.
     */
    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    /**
     * Get the tasks for the project.
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the team members for the project.
     */
    public function team()
    {
        return $this->belongsToMany(User::class, 'project_team')
                    ->withPivot('role_id', 'allocation_percent')
                    ->withTimestamps();
    }

    /**
     * Get the resources for the project.
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class, 'project_resources')
                    ->withPivot('allocation_percent')
                    ->withTimestamps();
    }

    /**
     * Get the files for the project.
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Get the EVM issues for the project.
     */
    public function evmIssues()
    {
        return $this->hasMany(ProjectEvmIssue::class);
    }

    /**
     * Scope a query to only include active projects.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Get the budget variance.
     */
    public function getBudgetVarianceAttribute()
    {
        return $this->actual_budget - $this->target_budget;
    }

    /**
     * Check if the project is overbudget.
     */
    public function isOverbudget()
    {
        return $this->actual_budget > $this->target_budget;
    }

    /**
     * Check if the project is overdue.
     */
    public function isOverdue()
    {
        return $this->end_date && $this->end_date->isPast() && $this->percent_complete < 100;
    }
}
