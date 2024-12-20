<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogHelper
{
    public static function logActivity($user, $activityType, $details)
    {
        // Check if $user is an integer (user ID), and if so, fetch the User object
        if (is_int($user)) {
            $user = User::find($user);
        }

        // If the user is still null (not found), log an error or handle accordingly
        if (!$user) {
            \Log::error('User not found for activity logging', ['user_id' => $user]);
            return; // Stop execution if user is not found
        }

        // Proceed with creating an activity log entry
        ActivityLog::create([
            'user_id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'role' => $user->role,
            'school_name' => $user->school_name,
            'activity_type' => $activityType,
            'description' => $details,
        ]);
    }
}
