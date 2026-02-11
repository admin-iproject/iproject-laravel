<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectRole extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'project_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'role_name',
        'role_description',
        'role_type',
        'role_module',
    ];

    /**
     * Relationships
     */
    
    public function teamMembers(): HasMany
    {
        return $this->hasMany(ProjectTeam::class, 'role_id');
    }
}
