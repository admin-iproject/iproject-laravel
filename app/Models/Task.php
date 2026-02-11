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
        'task_ignore_budget',
        'task_order',
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
}
