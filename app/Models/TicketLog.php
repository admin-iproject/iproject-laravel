<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'hours',
        'description',
        'logged_at',
        'cost_code',
        'hourly_rate',
    ];

    protected $casts = [
        'hours'       => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'logged_at'   => 'date',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessors ─────────────────────────────────────────────────────

    // Cost = hours * hourly_rate
    public function getCostAttribute(): float
    {
        return round((float) $this->hours * (float) $this->hourly_rate, 2);
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBetweenDates($query, string $from, string $to)
    {
        return $query->whereBetween('logged_at', [$from, $to]);
    }
}
