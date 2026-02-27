<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketReply extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ticket_id','author_id','author_email','author_name',
        'body','is_public','source','email_message_id',
    ];
    protected $casts = ['is_public' => 'boolean'];

    public function ticket(): BelongsTo { return $this->belongsTo(Ticket::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class, 'author_id'); }

    public function getAuthorDisplayNameAttribute(): string
    {
        if ($this->author) {
            return trim(($this->author->first_name ?? '') . ' ' . ($this->author->last_name ?? ''))
                   ?: $this->author->email;
        }
        return $this->author_name ?: $this->author_email ?: 'Unknown';
    }

    public function getAuthorInitialsAttribute(): string
    {
        $name = $this->author_display_name;
        $parts = explode(' ', $name);
        return strtoupper(
            substr($parts[0] ?? '?', 0, 1) . substr($parts[1] ?? '', 0, 1)
        );
    }
}

class TicketLog extends Model
{
    protected $fillable = [
        'ticket_id','user_id','hours','description','logged_at','cost_code','hourly_rate',
    ];
    protected $casts = ['logged_at' => 'date', 'hours' => 'decimal:2'];

    public function ticket(): BelongsTo { return $this->belongsTo(Ticket::class); }
    public function user(): BelongsTo   { return $this->belongsTo(User::class); }

    public function getCostAttribute(): float
    {
        return round((float)$this->hours * (float)$this->hourly_rate, 2);
    }
}

class TicketWatcher extends Model
{
    protected $fillable = [
        'ticket_id','user_id','email','name','notify_replies','notify_status_change',
    ];
    protected $casts = [
        'notify_replies'       => 'boolean',
        'notify_status_change' => 'boolean',
    ];

    public function ticket(): BelongsTo { return $this->belongsTo(Ticket::class); }
    public function user(): BelongsTo   { return $this->belongsTo(User::class); }
}

class TicketRelation extends Model
{
    protected $fillable = ['ticket_id','related_ticket_id','relation_type','created_by'];

    public function ticket(): BelongsTo        { return $this->belongsTo(Ticket::class, 'ticket_id'); }
    public function relatedTicket(): BelongsTo { return $this->belongsTo(Ticket::class, 'related_ticket_id'); }
    public function createdBy(): BelongsTo     { return $this->belongsTo(User::class, 'created_by'); }
}

class Asset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id','department_id','assigned_user_id',
        'name','type','make','model','serial_number','asset_tag',
        'location','lat','lng','status','purchase_date','warranty_expiry','notes',
    ];
    protected $casts = [
        'purchase_date'   => 'date',
        'warranty_expiry' => 'date',
        'lat'             => 'decimal:7',
        'lng'             => 'decimal:7',
    ];

    public function company(): BelongsTo      { return $this->belongsTo(Company::class); }
    public function department(): BelongsTo   { return $this->belongsTo(Department::class); }
    public function assignedUser(): BelongsTo { return $this->belongsTo(User::class, 'assigned_user_id'); }
    public function tickets(): BelongsToMany  { return $this->belongsToMany(Ticket::class, 'ticket_assets'); }

    public function scopeActive($q)          { return $q->where('status', 'active'); }
    public function scopeForCompany($q, $id) { return $q->where('company_id', $id); }
}
