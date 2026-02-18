<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskChecklist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'task_checklist';

    /**
     * The primary key for the model.
     * DB uses checklist_id, not id.
     */
    protected $primaryKey = 'checklist_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'task_id',
        'checklist',   // The checklist item text
        'checkedby',   // FK to users — null means not completed
        'checkeddate', // Datetime when checked
        'order',       // Sort order
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'checkeddate' => 'datetime',
        'order'       => 'integer',
    ];

    /**
     * Relationships
     */

    /**
     * Get the task this checklist item belongs to
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * Get the user who checked this item
     */
    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checkedby');
    }

    /**
     * Accessors
     */

    /**
     * Is this item completed?
     * Completion = checkedby is not null
     */
    public function getIsCompletedAttribute(): bool
    {
        return !is_null($this->checkedby);
    }

    /**
     * Scopes
     */

    /**
     * Only completed items (checkedby is set)
     */
    public function scopeCompleted($query)
    {
        return $query->whereNotNull('checkedby');
    }

    /**
     * Only incomplete items (checkedby is null)
     */
    public function scopeIncomplete($query)
    {
        return $query->whereNull('checkedby');
    }

    /**
     * Order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Actions
     */

    /**
     * Mark item as complete
     */
    public function markComplete(int $userId): void
    {
        $this->update([
            'checkedby'   => $userId,
            'checkeddate' => now(),
        ]);
    }

    /**
     * Mark item as incomplete
     */
    public function markIncomplete(): void
    {
        $this->update([
            'checkedby'   => null,
            'checkeddate' => null,
        ]);
    }
}
