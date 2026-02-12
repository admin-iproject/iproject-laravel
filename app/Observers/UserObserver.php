<?php

namespace App\Observers;

use App\Models\User;
use App\Models\UserStandardAvailability;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Automatically populate standard availability with 8 hours Monday-Friday.
     */
    public function created(User $user): void
    {
        // Create standard availability for all 7 days of the week
        // Default: 8 hours Monday-Friday, 0 hours Saturday-Sunday
        $standardHours = [
            0 => 0, // Sunday
            1 => 8, // Monday
            2 => 8, // Tuesday
            3 => 8, // Wednesday
            4 => 8, // Thursday
            5 => 8, // Friday
            6 => 0, // Saturday
        ];

        foreach ($standardHours as $dayOfWeek => $hours) {
            UserStandardAvailability::create([
                'user_id' => $user->id,
                'day_of_week' => $dayOfWeek,
                'hours_available' => $hours,
            ]);
        }
    }

    /**
     * Handle the User "deleted" event.
     * Standard availability and custom availability are cascade deleted by DB.
     */
    public function deleted(User $user): void
    {
        // Cascade deletes are handled by database foreign keys
        // No action needed here
    }
}
