<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'parent_id',
        'name',
        'description',
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
        'type',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
/**
     * Fields that can be inherited from parent or company
     */
    protected $inheritableFields = [
        'phone',
        'fax',
        'url',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
    ];

    // Relationships

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'department_id');
    }

    // Helper Methods

    /**
     * Get a field value with inheritance from parent or company
     */
    public function getInheritedValue($field)
    {
        // If this department has the value, return it
        if (!empty($this->$field)) {
            return $this->$field;
        }

        // Try to get from parent department
        if ($this->parent_id && $this->parent) {
            if (!empty($this->parent->$field)) {
                return $this->parent->$field;
            }
        }

        // Try to get from company
        if ($this->company) {
            // Map department fields to company fields
            // Map department fields to company fields
            $companyFieldMap = [
                'phone' => 'phone1',
                'fax' => 'fax',
                'url' => 'primary_url',
                'address_line1' => 'address_line1',
                'address_line2' => 'address_line2',
                'city' => 'city',
                'state' => 'state',
                'zip' => 'zip',
                'country' => 'country',
            ];

            if (isset($companyFieldMap[$field])) {
                $companyField = $companyFieldMap[$field];
                if (!empty($this->company->$companyField)) {
                    return $this->company->$companyField;
                }
            }
        }

        return null;
    }

    /**
     * Apply inherited values to null fields
     * Call this before saving
     */
    public function applyInheritedDefaults()
    {
        foreach ($this->inheritableFields as $field) {
            if (empty($this->$field)) {
                $inheritedValue = $this->getInheritedValue($field);
                if ($inheritedValue) {
                    $this->$field = $inheritedValue;
                }
            }
        }
    }

    /**
     * Get display value with inheritance indicator
     */
    public function getDisplayValue($field)
    {
        $value = $this->$field;
        
        if (!empty($value)) {
            return ['value' => $value, 'inherited' => false, 'source' => 'department'];
        }

        // Check parent
        if ($this->parent_id && $this->parent && !empty($this->parent->$field)) {
            return ['value' => $this->parent->$field, 'inherited' => true, 'source' => 'parent'];
        }

        // Check company
        $companyFieldMap = [
            'phone' => 'phone1',
            'fax' => 'fax',
            'url' => 'primary_url',
            'address_line1' => 'address_line1',
            'address_line2' => 'address_line2',
            'city' => 'city',
            'state' => 'state',
            'zip' => 'zip',
            'country' => 'country',
        ];

        if ($this->company && isset($companyFieldMap[$field])) {
            $companyField = $companyFieldMap[$field];
            if (!empty($this->company->$companyField)) {
                return ['value' => $this->company->$companyField, 'inherited' => true, 'source' => 'company'];
            }
        }

        return ['value' => null, 'inherited' => false, 'source' => null];
    }
}