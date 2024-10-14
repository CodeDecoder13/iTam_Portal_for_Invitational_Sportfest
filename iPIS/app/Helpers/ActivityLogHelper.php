<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class ActivityLogHelper
{
    public static function logActivity($user, $activityType, $details)
    {
        $description = $details;

        ActivityLog::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'role' => $user->role,
            'school_name' => $user->school_name,
            'activity_type' => $activityType,
            'description' => $description,
        ]);
    }
}
