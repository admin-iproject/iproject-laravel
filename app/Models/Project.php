<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
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
        'custom_fields',
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

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
        'last_edited' => 'datetime',
        'active' => 'boolean',
        'private' => 'boolean',
        'target_budget' => 'decimal:2',
        'actual_budget' => 'decimal:2',
        'phases' => 'array',
        'custom_fields' => 'array',
    ];

    /**
     * Boot method to set defaults
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($project) {
            // Auto-generate short_name if not provided
            if (empty($project->short_name)) {
                $project->short_name = substr($project->name, 0, 10);
            }
            
            // Set creator
            if (empty($project->creator_id) && auth()->check()) {
                $project->creator_id = auth()->id();
            }

            // Set default color if not provided
            if (empty($project->color_identifier)) {
                $project->color_identifier = 'eeeeee';
            }

            // Set last_edited_by on creation (required field)
            if (empty($project->last_edited_by) && auth()->check()) {
                $project->last_edited_by = auth()->id();
            }
        });

        static::updating(function ($project) {
            // Track who edited
            $project->last_edited = now();
            $project->last_edited_by = auth()->id();
        });
    }

    /**
     * Relationships
     */
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function lastEditedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activeTasks(): HasMany
    {
        return $this->tasks()->whereNull('deleted_at');
    }

    public function completedTasks(): HasMany
    {
        return $this->tasks()->where('percent_complete', 100);
    }

    public function team(): HasMany
    {
        return $this->hasMany(ProjectTeam::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(ProjectResource::class);
    }

    public function evmIssues(): HasMany
    {
        return $this->hasMany(ProjectEvmIssue::class);
    }

    /**
     * Scopes
     */
    
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->whereHas('team', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeForDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    /**
     * Accessors & Mutators
     */
    
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            0 => 'Not Started',
            1 => 'Proposed',
            2 => 'In Planning',
            3 => 'In Progress',
            4 => 'On Hold',
            5 => 'Complete',
            6 => 'Archived',
            default => 'Unknown',
        };
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->tasks()->count() === 0) {
            return 0;
        }

        return round($this->tasks()->avg('percent_complete'), 2);
    }

    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        return now()->diffInDays($this->end_date, false);
    }

    public function getIsOverdueAttribute(): bool
    {
        if (!$this->end_date) {
            return false;
        }

        return $this->end_date->isPast() && $this->status !== 5;
    }

    public function getBudgetRemainingAttribute(): float
    {
        return $this->target_budget - $this->actual_budget;
    }

    public function getIsOverBudgetAttribute(): bool
    {
        return $this->actual_budget > $this->target_budget;
    }

    /**
     * Helper Methods
     */
    
    public function calculateProgress(): float
    {
        return $this->tasks()
            ->selectRaw('AVG(percent_complete) as avg_progress')
            ->value('avg_progress') ?? 0;
    }

    public function updateProgress(): void
    {
        $this->percent_complete = $this->calculateProgress();
        $this->saveQuietly(); // Don't trigger events
    }

    public function calculateActualBudget(): float
    {
        // Sum up all task budgets in the entire hierarchy
        return $this->tasks()
            ->where('task_ignore_budget', false)
            ->sum('target_budget') ?? 0;
    }

    public function updateActualBudget(): void
    {
        $this->actual_budget = $this->calculateActualBudget();
        $this->saveQuietly(); // Don't trigger events
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }

    public function hasTeamMember(User $user): bool
    {
        return $this->team()->where('user_id', $user->id)->exists();
    }

    public function addTeamMember(User $user, $roleId = null, $allocationPercent = 0): void
    {
        $this->team()->create([
            'user_id' => $user->id,
            'role_id' => $roleId,
            'allocation_percent' => $allocationPercent,
            'assigned_date' => now(),
            'assigned_by' => auth()->id(),
        ]);
    }

    public function removeTeamMember(User $user): void
    {
        $this->team()->where('user_id', $user->id)->delete();
    }
}
