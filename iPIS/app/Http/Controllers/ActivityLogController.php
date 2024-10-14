<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function getLatestActivities()
    {
        $activities = ActivityLog::with('user')->latest()->take(10)->get(); // Fetch the latest 10 activities
        return response()->json($activities);
    }
}