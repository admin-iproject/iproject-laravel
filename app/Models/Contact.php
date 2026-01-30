<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'department_id',
        'first_name',
        'last_name',
        'title',
        'email',
        'phone',
        'phone2',
        'mobile',
        'fax',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
        'notes',
        'type',
        'owner_id',
    ];

    protected $casts = [
        'type' => 'integer',
    ];

    /**
     * Get the company that owns the contact.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that owns the contact.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the owner of the contact.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the contact's full name.
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
