<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'description',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Get the company that owns this skill.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get all users that have this skill.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')
                    ->withPivot('proficiency_level', 'acquired_date', 'notes')
                    ->withTimestamps();
    }

    /**
     * Get count of users with this skill.
     */
    public function getUserCountAttribute(): int
    {
        return $this->users()->count();
    }

    /**
     * Check if skill is assigned to any users.
     */
    public function isAssigned(): bool
    {
        return $this->users()->exists();
    }

    /**
     * Scope to order by sort_order then name.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope to filter by company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Scope to search by name.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}
