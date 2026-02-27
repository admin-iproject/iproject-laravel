<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketCategory extends Model
{
    protected $fillable = ['company_id','department_id','parent_id','name','is_active','sort_order'];
    protected $casts    = ['is_active' => 'boolean'];

    public function company(): BelongsTo    { return $this->belongsTo(Company::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function parent(): BelongsTo     { return $this->belongsTo(TicketCategory::class, 'parent_id'); }
    public function children(): HasMany     { return $this->hasMany(TicketCategory::class, 'parent_id'); }
    public function tickets(): HasMany      { return $this->hasMany(Ticket::class, 'category_id'); }

    public function scopeActive($q)             { return $q->where('is_active', true)->orderBy('sort_order'); }
    public function scopeForCompany($q, $id)    { return $q->where('company_id', $id); }
    public function scopeTopLevel($q)           { return $q->whereNull('parent_id'); }
}
