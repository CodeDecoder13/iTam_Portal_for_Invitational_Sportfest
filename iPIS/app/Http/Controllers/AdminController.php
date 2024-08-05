<?php

namespace App\Http\Controllers;



use App\Models\Team;
use App\Models\User;
use App\Models\Admin;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function documents()
    {
        return view('admin.admin-sidebar.documents');
    }

    public function calendar()
    {
        return view('admin.admin-sidebar.calendar');
    }

    public function playersTeams(Request $request)
    {
        $query = Team::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('acronym', 'like', '%' . $request->search . '%')
                ->orWhere('sport_category', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sport')) {
            $query->where('sport_category', $request->sport);
        }
        if ($request->has('team')) {
            $query->where('acronym', $request->team);
        }
        /*if ($request->has('status')) {
            $query->where('status', $request->status);
        }*/

        $teams = $query->paginate(10);

        return view('admin.admin-sidebar.players-teams', compact('teams'));
    }


    public function usersManagement()
    {
        try {
            $users = User::select('users.first_name', 'users.last_name', 'users.email', 'users.is_active', 'users.created_at', 'users.role')
                ->get();

            $admins = Admin::all(); // Fetch all teams if needed

            Log::info('Fetched users: ', $users->toArray());

            $data = [
                'users' => $users,
                'admins' => $admins,
            ];

            return view('admin.admin-sidebar.user-management', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error fetching coach approval data: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }
    }

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

    public function showteam($id)
    {
        $team = Team::find($id);
        $coach = User::find($team->coach_id);
        $players = Player::where('team_id', $id)->get();

        return response()->json([
            'team' => $team,
            'coach' => $coach,
            'players' => $players
        ]);
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