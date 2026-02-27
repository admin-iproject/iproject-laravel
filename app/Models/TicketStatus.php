<?php
// ── TicketStatus ──────────────────────────────────────────────────────
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    protected $fillable = [
        'company_id','name','color','sort_order',
        'stops_sla_clock','is_default_open','is_resolved','is_closed','is_active',
    ];
    protected $casts = [
        'stops_sla_clock' => 'boolean',
        'is_default_open' => 'boolean',
        'is_resolved'     => 'boolean',
        'is_closed'       => 'boolean',
        'is_active'       => 'boolean',
    ];
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function tickets(): HasMany   { return $this->hasMany(Ticket::class, 'status_id'); }
    public function scopeActive($q)      { return $q->where('is_active', true)->orderBy('sort_order'); }
    public function scopeForCompany($q, $id) { return $q->where('company_id', $id); }
}
