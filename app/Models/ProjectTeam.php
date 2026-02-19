<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTeam extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'project_team';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'company_skill_id',
        'role_id',
        'allocation_percent',
        'hourly_cost',
        'assigned_date',
        'assigned_by',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'assigned_date'      => 'datetime',
        'allocation_percent' => 'integer',
        'hourly_cost'        => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(CompanySkill::class, 'company_skill_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(ProjectRole::class, 'role_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
