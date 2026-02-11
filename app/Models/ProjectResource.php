<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectResource extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'project_resources';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'project_id',
        'resource_id',
        'selected_by',
        'selected_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'selected_date' => 'datetime',
    ];

    /**
     * Relationships
     */
    
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'resource_id');
    }

    public function selectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'selected_by');
    }
}
