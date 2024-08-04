<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

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
        $users = User::all();
        return view('admin.admin-sidebar.user-management', compact('users'));
    }

    public function coachApproval()
    {
        return view('admin.admin-sidebar.coach-approval');
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
}
