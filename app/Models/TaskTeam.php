<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'task_team';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'user_id',
        'hours',
        'is_owner',
        'assigned_by',
        'assigned_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'hours' => 'decimal:2',
        'is_owner' => 'boolean',
        'assigned_at' => 'datetime',
    ];

    /**
     * Get the task this assignment belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user assigned to this task
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who assigned this team member
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Scope to only get task owners
     */
    public function scopeOwners($query)
    {
        return $query->where('is_owner', true);
    }

    /**
     * Scope to only get team members (not owners)
     */
    public function scopeMembers($query)
    {
        return $query->where('is_owner', false);
    }
}
