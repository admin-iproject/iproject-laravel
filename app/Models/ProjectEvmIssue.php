<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectEvmIssue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'project_evm_issues';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'BAC',
        'BCWS',
        'ACWP',
        'BCWP',
        'CV',
        'SV',
        'SPI',
        'CPI',
        'EAC',
        'iEAC',
        'per_CP',
        'per_SP',
        'budget_issues',
        'clock_issues',
        'risk_issues',
        'calendar_issues',
    ];

    /**
     * Relationships
     */
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
