<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * User status constants
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_HIDDEN = 'hidden';

    /**
     * Permission scope constants
     */
    const SCOPE_ALL = 'all';           // Access all records
    const SCOPE_ASSIGNED = 'assigned'; // Only assigned records
    const SCOPE_OWN = 'own';          // Only owned records
    const SCOPE_NONE = 'none';        // No access

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'parent_id',
        'type',
        'status',
        'permission_scopes',
        'first_name',
        'last_name',
        'company_id',
        'department_id',
        'hourly_cost', // ALREADY EXISTS
        'phone',
        'home_phone',
        'mobile',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'zip',
        'country',
        'icq',
        'aol',
        'birthday',
        'pic',
        'signature',
        'signature_icon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'type' => 'integer',
            'permission_scopes' => 'array',
            'hourly_cost' => 'decimal:2', // ALREADY EXISTS
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Check if user is inactive (ghost).
     */
    public function isInactive(): bool
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    /**
     * Check if user is hidden.
     */
    public function isHidden(): bool
    {
        return $this->status === self::STATUS_HIDDEN;
    }

    /**
     * Check if user can login.
     */
    public function canLogin(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Get the permission scope for a specific permission.
     */
    public function getPermissionScope(string $permission): string
    {
        return $this->permission_scopes[$permission] ?? self::SCOPE_NONE;
    }

    /**
     * Set the permission scope for a specific permission.
     */
    public function setPermissionScope(string $permission, string $scope): void
    {
        $scopes = $this->permission_scopes ?? [];
        $scopes[$permission] = $scope;
        $this->permission_scopes = $scopes;
        $this->save();
    }

    /**
     * Check if user has permission with scope validation.
     */
    public function hasPermissionWithScope(string $permission, string $requiredScope = self::SCOPE_ALL): bool
    {
        // First check if user has the base permission
        if (!$this->hasPermissionTo($permission)) {
            return false;
        }

        // Check scope level
        $userScope = $this->getPermissionScope($permission);

        // Scope hierarchy: all > assigned > own > none
        $scopeHierarchy = [
            self::SCOPE_ALL => 3,
            self::SCOPE_ASSIGNED => 2,
            self::SCOPE_OWN => 1,
            self::SCOPE_NONE => 0,
        ];

        return ($scopeHierarchy[$userScope] ?? 0) >= ($scopeHierarchy[$requiredScope] ?? 0);
    }

    /**
     * Check if user can access a specific record based on scope.
     */
    public function canAccessRecord(string $permission, $record): bool
    {
        // First check if user has the permission at all
        if (!$this->hasPermissionTo($permission)) {
            return false;
        }

        $scope = $this->getPermissionScope($permission);

        return match($scope) {
            self::SCOPE_ALL => true,
            self::SCOPE_ASSIGNED => $this->isAssignedTo($record),
            self::SCOPE_OWN => $this->owns($record),
            default => false,
        };
    }

    /**
     * Check if user is assigned to a record (project/task).
     */
    private function isAssignedTo($record): bool
    {
        if (method_exists($record, 'team')) {
            return $record->team()->where('user_id', $this->id)->exists();
        }
        
        if (method_exists($record, 'assignedUsers')) {
            return $record->assignedUsers()->where('user_id', $this->id)->exists();
        }

        return false;
    }

    /**
     * Check if user owns a record.
     */
    private function owns($record): bool
    {
        if (isset($record->owner_id)) {
            return $record->owner_id === $this->id;
        }

        if (isset($record->user_id)) {
            return $record->user_id === $this->id;
        }

        return false;
    }

    /**
     * Get the company that the user belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the department that the user belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the parent user.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the child users.
     */
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    /**
     * Get the projects owned by the user.
     */
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'owner_id');
    }

    /**
     * Get the tasks owned by the user.
     */
    public function ownedTasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    /**
     * Get the projects the user is a team member of.
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_team')
                    ->withPivot('role_id', 'allocation_percent', 'hourly_cost')
                    ->withTimestamps();
    }

    /**
     * Get the tasks assigned to the user.
     */
    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks')
                    ->withPivot('percent_effort')
                    ->withTimestamps();
    }

    /**
     * Get the tasks the user is a team member of (via task_team table).
     */
    public function taskTeam()
    {
        return $this->belongsToMany(Task::class, 'task_team')
                    ->withPivot('hours', 'is_owner', 'assigned_by', 'assigned_at')
                    ->withTimestamps();
    }

    /**
     * Get the user's preferences.
     */
    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    /**
     * Get the user's availability records.
     */
    public function availability()
    {
        return $this->hasMany(UserAvailability::class)->orderBy('availability_date');
    }

    /**
     * Get the user's standard availability (weekly schedule).
     */
    public function standardAvailability()
    {
        return $this->hasMany(UserStandardAvailability::class)->orderBy('day_of_week');
    }

    /**
     * Get total weekly hours from standard availability.
     * NEW
     */
    public function getWeeklyHoursAttribute(): float
    {
        return $this->standardAvailability()->sum('hours_available');
    }

    /**
     * Get availability for a specific date.
     * Returns custom availability if exists, otherwise returns standard for that day of week.
     * NEW
     */
    public function getAvailabilityForDate($date): float
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        
        // Check for custom availability first
        $customAvailability = $this->availability()
            ->where('availability_date', $carbonDate->format('Y-m-d'))
            ->first();
            
        if ($customAvailability) {
            return $customAvailability->hours_available;
        }
        
        // Fall back to standard availability for that day of week
        $dayOfWeek = $carbonDate->dayOfWeek; // 0=Sunday, 6=Saturday
        $standardAvailability = $this->standardAvailability()
            ->where('day_of_week', $dayOfWeek)
            ->first();
            
        return $standardAvailability ? $standardAvailability->hours_available : 0;
    }

    /**
     * Check if user has hourly cost set.
     * NEW
     */
    public function hasHourlyCost(): bool
    {
        return $this->hourly_cost > 0;
    }

    /**
     * Get formatted hourly cost.
     * NEW
     */
    public function getFormattedHourlyCostAttribute(): string
    {
        return '$' . number_format($this->hourly_cost, 2);
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope a query to only include inactive users (ghosts).
     */
    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    /**
     * Scope a query to only include hidden users.
     */
    public function scopeHidden($query)
    {
        return $query->where('status', self::STATUS_HIDDEN);
    }

    /**
     * Scope a query to include users visible in lists (active + inactive, not hidden).
     */
    public function scopeVisibleInLists($query)
    {
        return $query->whereIn('status', [self::STATUS_ACTIVE, self::STATUS_INACTIVE]);
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeOfCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
