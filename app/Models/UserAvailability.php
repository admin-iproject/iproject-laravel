<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserAvailability extends Model
{
    use HasFactory;

    protected $table = 'user_availability';

    protected $fillable = [
        'user_id',
        'availability_date',
        'hours_available',
        'notes',
    ];

    protected $casts = [
        'availability_date' => 'date',
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
     * Scope to filter by specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('availability_date', Carbon::parse($date)->format('Y-m-d'));
    }

    /**
     * Scope to filter by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('availability_date', [
            Carbon::parse($startDate)->format('Y-m-d'),
            Carbon::parse($endDate)->format('Y-m-d'),
        ]);
    }

    /**
     * Scope to get upcoming availability.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('availability_date', '>=', Carbon::today()->format('Y-m-d'));
    }

    /**
     * Scope to get past availability.
     */
    public function scopePast($query)
    {
        return $query->where('availability_date', '<', Carbon::today()->format('Y-m-d'));
    }

    /**
     * Check if this is a full day off (0 hours).
     */
    public function isDayOff(): bool
    {
        return $this->hours_available == 0;
    }

    /**
     * Check if this is reduced hours (less than standard).
     */
    public function isReducedHours(): bool
    {
        return $this->hours_available > 0 && $this->hours_available < 8;
    }
}
