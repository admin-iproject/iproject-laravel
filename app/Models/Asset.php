<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'assigned_user_id',
        'name',
        'type',
        'make',
        'model',
        'serial_number',
        'asset_tag',
        'location',
        'lat',
        'lng',
        'status',
        'purchase_date',
        'warranty_expiry',
        'notes',
    ];

    protected $casts = [
        'purchase_date'   => 'date',
        'warranty_expiry' => 'date',
        'lat'             => 'decimal:7',
        'lng'             => 'decimal:7',
    ];

    // ── Relationships ─────────────────────────────────────────────────

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function tickets()
    {
        return $this->belongsToMany(Ticket::class, 'ticket_assets');
    }

    // ── Scopes ────────────────────────────────────────────────────────

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
