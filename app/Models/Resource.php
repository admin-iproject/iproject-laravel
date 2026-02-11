<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'resources';

    /**
     * The primary key associated with the table.
     */
    protected $primaryKey = 'resource_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'company_id',
        'parent_id',
        'resource_name',
        'description',
        'cost_per_unit',
        'renewal_required',
        'expiry_date',
        'field1',
        'field2',
        'field3',
        'field4',
        'field5',
        'field6',
        'field7',
        'field8',
        'field9',
        'field10',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expiry_date' => 'datetime',
        'cost_per_unit' => 'float',
    ];

    /**
     * Relationships
     */
    
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'parent_id', 'resource_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Resource::class, 'parent_id', 'resource_id');
    }

    public function projectResources(): HasMany
    {
        return $this->hasMany(ProjectResource::class, 'resource_id', 'resource_id');
    }
}
