<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Team;
use App\Models\Admin;
use App\Models\Player;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LogsController extends Controller
{
    public function logSystem()
    {
        // Get total users count from User model
        $activeUsers = User::count();

        // Get total active admins count from Admin model
        $activeAdmins = Admin::where('is_active', 1)->count();

        // Get current active users from User model where is_active = 1
        $currentActiveUsers = User::where('is_active', 1)->count();

        // Get total players count
        $totalPlayers = Player::count();

        // Get total teams count
        $totalTeams = Team::count();

        // Get total games count
        $totalGames = DB::table('games')->count();

        // Get user activity for the last 7 days using PostgreSQL timestamp functions
        $lastWeekActivity = ActivityLog::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as user_count')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format the activity data for the chart
        $dates = [];
        $userCounts = [];
        
        // Create an array of the last 7 days
        $last7Days = collect(range(6, 0))->map(function($days) {
            return Carbon::now()->subDays($days)->format('Y-m-d');
        });

        // Initialize counts with 0 for all days
        $activityByDate = $lastWeekActivity->pluck('user_count', 'date')->toArray();
        
        foreach ($last7Days as $date) {
            $dates[] = Carbon::parse($date)->format('D');
            $userCounts[] = $activityByDate[$date] ?? 0;
        }

        // Get recent active users with more details
        $recentUsers = User::select('id', 'first_name', 'last_name', 'email', 'is_active', 'role')
            ->where('is_active', 1)  // Only get active users
            ->orderBy('updated_at', 'desc')  // Order by last update
            ->take(4)
            ->get();

        return view('admin.admin-sidebar.logs-system', compact(
            'activeUsers',
            'currentActiveUsers',
            'activeAdmins',
            'totalPlayers',
            'totalTeams',
            'totalGames',
            'dates',
            'userCounts',
            'recentUsers'
        ));
    }
    
    public function getUserDetails($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function updateUserStatus(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = $request->status;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status'
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
                'role' => 'required|string',
                'school_name' => 'required|string'
            ]);

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user'
            ], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user'
            ], 500);
        }
    }
} 