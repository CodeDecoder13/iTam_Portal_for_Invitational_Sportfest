<?php

namespace App\Helpers;

use App\Models\Team;
use App\Helpers\ActivityLogHelper;

class TeamHelper
{
    public static function addTeamAndLogActivity($teamData, $user)
    {
        // Create the new team
        $team = Team::create($teamData);

        // Log the activity, including the team name and sport category
        $description = sprintf(
            '%s %s (%s - %s) added a new team: %s (%s)',
            $user->first_name,
            $user->last_name,
            $user->role ?? 'No role',
            $user->school_name ?? 'No school',
            $team->name,  // Team name
            $team->sport_category  // Sport category
        );

        // Log the activity
        ActivityLogHelper::logActivity(
            $user->id,
            'team_added',
            $description
        );

        return $team;
    }
}
