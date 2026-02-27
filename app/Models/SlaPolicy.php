<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SlaPolicy extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'priority',
        'ticket_type',
        'first_response_minutes',
        'resolution_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function getFirstResponseLabelAttribute(): string
    {
        $mins = $this->first_response_minutes;
        if ($mins < 60)   return $mins . 'm';
        if ($mins < 1440) return round($mins / 60) . 'h';
        return round($mins / 1440) . 'd';
    }

    public function getResolutionLabelAttribute(): string
    {
        $mins = $this->resolution_minutes;
        if ($mins < 60)   return $mins . 'm';
        if ($mins < 1440) return round($mins / 60) . 'h';
        return round($mins / 1440) . 'd';
    }
}
