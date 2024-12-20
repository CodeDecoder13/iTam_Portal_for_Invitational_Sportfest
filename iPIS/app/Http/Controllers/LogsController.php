<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogsController extends Controller
{
    public function index()
    {
        // Get total active users (users who have logged in within the last 30 days)
        $activeUsers = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('sessions.last_activity', '>=', Carbon::now()->subDays(30)->timestamp)
            ->distinct('users.id')
            ->count();

        // Get current active users (users with active sessions in the last 15 minutes)
        $currentActiveUsers = DB::table('sessions')
            ->where('last_activity', '>=', Carbon::now()->subMinutes(15)->timestamp)
            ->count();

        // Get active admins
        $activeAdmins = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('users.role', 'admin')
            ->where('sessions.last_activity', '>=', Carbon::now()->subDays(30)->timestamp)
            ->distinct('users.id')
            ->count();

        // Get user activity for the last 7 days
        $lastWeekActivity = DB::table('sessions')
            ->select(
                DB::raw('DATE(FROM_UNIXTIME(last_activity)) as date'),
                DB::raw('COUNT(DISTINCT user_id) as user_count')
            )
            ->where('last_activity', '>=', Carbon::now()->subDays(7)->timestamp)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format the activity data for the chart
        $dates = [];
        $userCounts = [];
        
        foreach ($lastWeekActivity as $activity) {
            $dates[] = Carbon::parse($activity->date)->format('D');
            $userCounts[] = $activity->user_count;
        }

        // Get recent active users
        $recentUsers = User::whereIn('id', function($query) {
            $query->select('user_id')
                ->from('sessions')
                ->where('last_activity', '>=', Carbon::now()->subHours(24)->timestamp);
        })->take(4)->get();

        return view('admin.admin-sidebar.logs-system', compact(
            'activeUsers',
            'currentActiveUsers',
            'activeAdmins',
            'dates',
            'userCounts',
            'recentUsers'
        ));
    }
} 