<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketContentRule extends Model
{
    protected $fillable = [
        'company_id','name','priority_order','match_field','match_type',
        'match_value','match_case_sensitive','stop_processing','is_active',
    ];
    protected $casts = [
        'match_case_sensitive' => 'boolean',
        'stop_processing'      => 'boolean',
        'is_active'            => 'boolean',
    ];

    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function actions(): HasMany   { return $this->hasMany(TicketContentRuleAction::class, 'rule_id'); }

    public function scopeForCompany($q, $id) { return $q->where('company_id', $id); }
    public function scopeActive($q)          { return $q->where('is_active', true)->orderBy('priority_order'); }
}

class TicketContentRuleAction extends Model
{
    protected $fillable = ['rule_id','action_type','action_value'];

    public function rule(): BelongsTo { return $this->belongsTo(TicketContentRule::class, 'rule_id'); }
}

class Solution extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    protected $fillable = [
        'company_id','title','body','category_id','tags',
        'source_ticket_id','created_by','is_published',
        'view_count','helpful_count','not_helpful_count',
    ];
    protected $casts = ['is_published' => 'boolean'];

    public function company(): BelongsTo       { return $this->belongsTo(Company::class); }
    public function category(): BelongsTo      { return $this->belongsTo(TicketCategory::class, 'category_id'); }
    public function sourceTicket(): BelongsTo  { return $this->belongsTo(Ticket::class, 'source_ticket_id'); }
    public function creator(): BelongsTo       { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q)           { return $q->where('is_published', true); }
    public function scopeForCompany($q, $id)     { return $q->where('company_id', $id); }
}

class TicketMailbox extends Model
{
    protected $fillable = [
        'company_id','name','email_address','protocol','host','port','use_ssl',
        'username','password','webhook_token',
        'default_status_id','default_category_id','default_department_id','default_priority',
        'is_active','last_checked_at',
    ];
    protected $casts  = ['use_ssl' => 'boolean', 'is_active' => 'boolean', 'last_checked_at' => 'datetime'];
    protected $hidden = ['password'];

    public function company(): BelongsTo        { return $this->belongsTo(Company::class); }
    public function defaultStatus(): BelongsTo  { return $this->belongsTo(TicketStatus::class, 'default_status_id'); }
    public function defaultCategory(): BelongsTo{ return $this->belongsTo(TicketCategory::class, 'default_category_id'); }
}
