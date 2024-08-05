<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function documents() {
        return view('admin.admin-sidebar.documents');
    }

    public function calendar() {
        return view('admin.admin-sidebar.calendar');
    }

    public function playersTeams() {
        return view('admin.admin-sidebar.players-teams');
    }

    public function usersManagement() {
        return view('admin.admin-sidebar.user-management');
    }

    //public function coachApproval() {
     //   return view('admin.admin-sidebar.coach-approval');
   // }
   public function coachApproval(Request $request)
{
    try {
        $users = User::select('users.id', 'users.first_name', 'users.last_name', 'users.is_active')
            ->leftJoin('teams', 'users.id', '=', 'teams.coach_id')
            ->get();

        $teams = Team::select('teams.id', 'teams.acronym', 'teams.sport_category', 'teams.created_at', 'teams.coach_id')
            ->get();

        Log::info('Fetched users: ', $users->toArray());
        Log::info('Fetched teams: ', $teams->toArray());

        $data = [
            'users' => $users,
            'teams' => $teams
        ];

        return view('admin.admin-sidebar.coach-approval', compact('data'));
    } catch (\Exception $e) {
        Log::error('Error fetching coach approval data: '.$e->getMessage());
        return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
    }
}

public function updateStatus($id, Request $request)
{
    try {
        $user = User::findOrFail($id);
        if ($request->action === 'activate') {
            $user->is_active = 1;
        } elseif ($request->action === 'deactivate') {
            $user->is_active = 0;
        }
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'user' => $user
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the status'
        ], 500);
    }
}




   
   
}
