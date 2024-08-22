<?php

namespace App\Http\Controllers;



use App\Models\Team;
use App\Models\User;
use App\Models\Admin;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

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


    public function documentCheckerFilter(Request $request)

    {
        $players = Player::all();
        $teams = Team::all();
        $users = User::all();

        $query = Player::query();

        // Filtering based on search input
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%');
            });
        }

        // Filtering by sport
        if ($request->filled('sport_category')) {
            $query->whereHas('team', function ($q) use ($request) {
                $q->where('sport_category', $request->input('sport_category'));
            });
        }

        // Filtering by team
        if ($request->filled('team')) {
            $query->where('team_id', $request->team);
        }

        // Filtering by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $players = $query->get();
       
        return view('admin.admin-sidebar.team-documents.SummaryOfPlayers_suggested', compact('players', 'teams', 'users'));
    }
     //return view('admin.admin-sidebar.team-documents.SummaryOfPlayers', compact('players', 'teams', 'users'));


    public function calendar()
    {
        return view('admin.admin-sidebar.calendar');
    }

    public function playersTeams(Request $request)
    {
        $query = Team::query();


        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('sport_category', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sport')) {
            $query->where('sport_category', $request->sport);
        }
        if ($request->has('team_name')) {
            $query->where('name', $request->name);
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
            $users = User::select('first_name', 'last_name', 'email', 'is_active', 'created_at', 'role')
                ->get();

            $admins = Admin::select('name', 'email', 'is_active', 'created_at', 'role')
                ->get();

            Log::info('Fetched users: ', $users->toArray());
            Log::info('Fetched admins: ', $admins->toArray());

            $data = [
                'users' => $users,
                'admins' => $admins,
            ];

            return view('admin.admin-sidebar.user-management', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error fetching user management data: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }
    }




    //public function coachApproval() {
    //   return view('admin.admin-sidebar.coach-approval');
    // }
    public function coachApproval(Request $request)
    {
        try {
            // Select all users with their corresponding team info (if available)
            $users = User::select('users.id', 'users.first_name', 'users.last_name', 'users.school_name', 'users.role', 'users.is_active', 'users.created_at')
                ->leftJoin('teams', 'users.id', '=', 'teams.coach_id')
                ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.school_name', 'users.role', 'users.is_active', 'users.created_at')
                ->get();

            // Fetch teams
            $teams = Team::select('teams.id', 'teams.name', 'teams.sport_category', 'teams.created_at', 'teams.coach_id')
                ->get();

            Log::info('Fetched users: ', $users->toArray());
            Log::info('Fetched teams: ', $teams->toArray());

            $data = [
                'users' => $users,
                'teams' => $teams
            ];

            return view('admin.admin-sidebar.coach-approval', compact('data'));
        } catch (\Exception $e) {
            Log::error('Error fetching coach approval data: ' . $e->getMessage());
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
    public function teamdocuments()
    {
        return view('admin.admin-sidebar.players-team-documents');
    }
}
