<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'parent_id',
        'name',
        'phone',
        'fax',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
        'url',
        'owner_id',
        'description',
        'type',
    ];

    protected $casts = [
        'type' => 'integer',
    ];

    /**
     * Get the company that owns the department.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the parent department.
     */
    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    /**
     * Get the child departments.
     */
    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    /**
     * Get the owner of the department.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users in the department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the projects for the department.
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Scope a query to only include active departments.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
