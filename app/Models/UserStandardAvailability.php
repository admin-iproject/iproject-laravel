<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStandardAvailability extends Model
{
    use HasFactory;

    protected $table = 'user_standard_availability';

    protected $fillable = [
        'user_id',
        'day_of_week',
        'hours_available',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'hours_available' => 'decimal:2',
    ];

    /**
     * Get the user that owns this availability record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the day name.
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Get short day name.
     */
    public function getDayShortAttribute(): string
    {
        $days = [
            0 => 'Sun',
            1 => 'Mon',
            2 => 'Tue',
            3 => 'Wed',
            4 => 'Thu',
            5 => 'Fri',
            6 => 'Sat',
        ];

        return $days[$this->day_of_week] ?? '???';
    }

    /**
     * Scope to filter by specific day of week.
     */
    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    /**
     * Scope to get weekday availability (Monday-Friday).
     */
    public function scopeWeekdays($query)
    {
        return $query->whereBetween('day_of_week', [1, 5]);
    }

    /**
     * Scope to get weekend availability (Saturday-Sunday).
     */
    public function scopeWeekends($query)
    {
        return $query->whereIn('day_of_week', [0, 6]);
    }
}
