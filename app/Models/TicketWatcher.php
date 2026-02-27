<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketWatcher extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'email',
        'name',
        'notify_replies',
        'notify_status_change',
    ];

    protected $casts = [
        'notify_replies'       => 'boolean',
        'notify_status_change' => 'boolean',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Display name — prefer linked user, fall back to stored name/email.
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->user) {
            return trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''))
                   ?: $this->user->email;
        }
        return $this->name ?: $this->email ?: 'Unknown';
    }

    /**
     * Scope: watchers for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: watchers who want reply notifications.
     */
    public function scopeNotifyOnReply($query)
    {
        return $query->where('notify_replies', true);
    }

    /**
     * Scope: watchers who want status change notifications.
     */
    public function scopeNotifyOnStatusChange($query)
    {
        return $query->where('notify_status_change', true);
    }
}
