<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id', 'ticket_number', 'type', 'status_id', 'priority',
        'category_id', 'department_id', 'subject', 'body', 'mailbox_address',
        'reporter_id', 'reporter_email', 'reporter_name',
        'assignee_id', 'assigned_by', 'assigned_at',
        'project_id', 'task_id', 'parent_ticket_id', 'close_reason_id', 'close_note',
        'sla_policy_id', 'resolve_by', 'first_response_at', 'first_response_breached',
        'resolved_at', 'resolution_breached', 'sla_paused_minutes', 'sla_paused_at',
        'lat', 'lng', 'location_accuracy', 'location_captured_at',
        'dept_lat', 'dept_lng',
        'email_message_id', 'email_thread_id',
        'created_by', 'source', 'last_activity_at',
    ];

    protected $casts = [
        'first_response_breached' => 'boolean',
        'resolution_breached'     => 'boolean',
        'assigned_at'             => 'datetime',
        'resolve_by'              => 'datetime',
        'first_response_at'       => 'datetime',
        'resolved_at'             => 'datetime',
        'sla_paused_at'           => 'datetime',
        'location_captured_at'    => 'datetime',
        'last_activity_at'        => 'datetime',
        'lat'                     => 'decimal:7',
        'lng'                     => 'decimal:7',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'status_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function parentTicket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'parent_ticket_id');
    }

    public function childTickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'parent_ticket_id');
    }

    public function closeReason(): BelongsTo
    {
        return $this->belongsTo(TicketCloseReason::class, 'close_reason_id');
    }

    public function slaPolicy(): BelongsTo
    {
        return $this->belongsTo(SlaPolicy::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->orderBy('created_at');
    }

    public function publicReplies(): HasMany
    {
        return $this->hasMany(TicketReply::class)->where('is_public', true)->orderBy('created_at');
    }

    public function watchers(): HasMany
    {
        return $this->hasMany(TicketWatcher::class);
    }

    public function watcherUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'ticket_watchers', 'ticket_id', 'user_id');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(TicketLog::class)->orderBy('logged_at', 'desc');
    }

    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'ticket_assets');
    }

    public function relations(): HasMany
    {
        return $this->hasMany(TicketRelation::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeOpen($query)
    {
        return $query->whereHas('status', fn($q) => $q->where('is_closed', false)->where('is_resolved', false));
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereNull('assignee_id');
    }

    public function scopeOverdue($query)
    {
        return $query->where('resolve_by', '<', now())
                     ->whereNull('resolved_at')
                     ->whereHas('status', fn($q) => $q->where('is_closed', false));
    }

    public function scopeWatchedBy($query, $userId)
    {
        return $query->whereHas('watchers', fn($q) => $q->where('user_id', $userId));
    }

    public function scopeByDepartment($query, $deptId)
    {
        return $query->where('department_id', $deptId);
    }

    public function scopeRecentlyClosed($query, $days = 7)
    {
        return $query->whereHas('status', fn($q) => $q->where('is_closed', true))
                     ->where('updated_at', '>=', now()->subDays($days));
    }

    public function scopeWithGeoLocation($query)
    {
        return $query->whereNotNull('lat')->whereNotNull('lng');
    }

    // ── Accessors / Helpers ───────────────────────────────────────────

    public function getAgeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getSlaStatusAttribute(): string
    {
        if ($this->resolved_at || ($this->status && $this->status->is_closed)) {
            return 'met';
        }
        if (!$this->resolve_by) return 'none';

        // Adjust deadline for paused time
        $effectiveDeadline = $this->resolve_by->copy()->addMinutes($this->sla_paused_minutes ?? 0);
        $now = now();

        if ($now->gt($effectiveDeadline)) return 'breached';

        $minutesLeft = $now->diffInMinutes($effectiveDeadline, false);
        $totalMinutes = $this->created_at->diffInMinutes($effectiveDeadline);
        $pctElapsed = $totalMinutes > 0 ? (($totalMinutes - $minutesLeft) / $totalMinutes) * 100 : 0;

        if ($pctElapsed >= 80) return 'warning';
        return 'ok';
    }

    public function getSlaColorAttribute(): string
    {
        return match($this->sla_status) {
            'breached' => 'red',
            'warning'  => 'amber',
            'ok'       => 'green',
            'met'      => 'blue',
            default    => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        // Uses company-defined priority colors if available
        $priority = TicketPriority::where('company_id', $this->company_id)
                                  ->where('level', $this->priority)
                                  ->first();
        return $priority?->color ?? match($this->priority) {
            1 => '#dc2626',
            2 => '#ea580c',
            3 => '#ca8a04',
            default => '#16a34a',
        };
    }

    public function getPriorityNameAttribute(): string
    {
        $priority = TicketPriority::where('company_id', $this->company_id)
                                  ->where('level', $this->priority)
                                  ->first();
        return $priority?->name ?? 'P' . $this->priority;
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'incident' => '<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'problem'  => '<svg class="w-5 h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0118 0z"/></svg>',
            'change'   => '<svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>',
            default    => '<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return ucfirst($this->type);
    }

    // Generate next ticket number for a company
    public static function nextTicketNumber(int $companyId): string
    {
        $last = static::where('company_id', $companyId)
                      ->orderByDesc('id')
                      ->lockForUpdate()
                      ->value('ticket_number');

        $prefix = 'TK-' . str_pad($companyId, 2, '0', STR_PAD_LEFT) . '-';

        if (!$last) return $prefix . '00001';

        // Extract sequence from last segment (TK-21-00004 → 4)
        $parts = explode('-', $last);
        $seq   = (int) end($parts);
        return $prefix . str_pad($seq + 1, 5, '0', STR_PAD_LEFT);
    }

    // Compute and set SLA deadline from policy
    public function applySlaPolicy(): void
    {
        if (!$this->sla_policy_id) {
            // Auto-match policy by priority
            $policy = SlaPolicy::where('company_id', $this->company_id)
                               ->where('priority', $this->priority)
                               ->where('is_active', true)
                               ->first();
            if ($policy) {
                $this->sla_policy_id = $policy->id;
                $this->resolve_by    = $this->created_at->copy()->addMinutes($policy->resolution_minutes);
            }
        }
    }
}
