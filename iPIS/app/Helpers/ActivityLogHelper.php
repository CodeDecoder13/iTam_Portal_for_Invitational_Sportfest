<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogHelper
{
    /**
     * Logs a user activity.
     *
     * @param int $user_id
     * @param string $first_name
     * @param string $last_name
     * @param string|null $role
     * @param string|null $school_name
     * @param string $activity_type
     * @param string $description
     * @return void
     */
    public static function logActivity($user_id, $first_name, $last_name, $role, $school_name, $activity_type, $description)
    {
        ActivityLog::create([
            'user_id' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'role' => $role,
            'school_name' => $school_name,
            'activity_type' => $activity_type,
            'description' => $description,
        ]);
    }
}
