<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TicketRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'related_ticket_id',
        'relation_type',
        'created_by',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function relatedTicket()
    {
        return $this->belongsTo(Ticket::class, 'related_ticket_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
