<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskLog extends Model
{
    protected $table = 'task_log';
    protected $primaryKey = 'task_log_id';

    protected $fillable = [
        'task_log_task',
        'task_log_name',
        'task_log_description',
        'task_log_creator',
        'task_log_hours',
        'task_log_date',
        'task_log_costcode',
        'task_log_phase',
        'task_log_risk',
        'task_percent_complete',
        'task_log_from_portal',
    ];

    protected $casts = [
        'task_log_hours'        => 'float',
        'task_log_date'         => 'datetime',
        'task_log_phase'        => 'integer',
        'task_log_risk'         => 'integer',
        'task_percent_complete' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_log_task');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'task_log_creator');
    }

    // Alias — 'user' is more natural in views
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'task_log_creator');
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeForTask($query, int $taskId)
    {
        return $query->where('task_log_task', $taskId);
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('task_log_date', 'desc')->limit($limit);
    }

    // ── Accessors ─────────────────────────────────────────────────

    /**
     * Formatted hours string e.g. "2.5 hrs"
     */
    public function getFormattedHoursAttribute(): string
    {
        return number_format($this->task_log_hours, 1) . ' hrs';
    }

    /**
     * Cost of this log entry = hours * creator's hourly_cost
     */
    public function getCostAttribute(): float
    {
        $rate = $this->creator?->hourly_cost ?? 0;
        return round($this->task_log_hours * $rate, 2);
    }
}
