<?php

namespace App\Http\Controllers;



use App\Models\Team;
use App\Models\User;
use App\Models\Admin;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
         // Fetch all teams
         $teams = Team::all();

         // Count the total number of teams
         $totalTeams = $teams->count();

         dd(compact('totalTeams'));
 
         return view('admin.dashboard', compact('totalTeams'));
    }
    //
   
                                                                                                        

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
            $admins = Admin::select('name', 'email', 'is_active', 'created_at', 'role')
                ->get();

            Log::info('Fetched admins: ', $admins->toArray());

            $data = [
                'admins' => $admins,
            ];

            return view('admin.admin-sidebar.user-management', compact('data'));
    
}
    // added for storing user
    public function storeUser(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birth_date' => 'required|date',
            'gender' => 'required|string|max:10',
            'school_name' => 'required', 'string',
            'role' => 'required|string|max:50',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_name' => $request->school_name,
            'role' => $request->role,
            
        ]);
        Log::info($request->all()); 
        // Fetch the school name from the user model
        $schoolName = $user->school_name;

        // Define the path for the school folder
        $schoolFolderPath = "public/{$schoolName}";

        // Check if the folder already exists
        if (!Storage::exists($schoolFolderPath)) {
            // Create the folder
            Storage::makeDirectory($schoolFolderPath);
        }

        return response()->json(['message' => 'User added successfully', 'user' => $user], 200);
    }
    // added for storing admin accounts
    public function storeAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins',
            'role' => 'required|string|max:50',
            'password' => 'required|string|min:8|confirmed',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        // Create a new admin
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),

        ]);
    
        return response()->json(['message' => 'Admin added successfully', 'user' => $admin], 200);
    }
    
    //update admins 'email' => 'required|string|email|max:255|unique:admins,email,' . $request->input('adminid'),
    public function updateAdmin(Request $request)
    {
        
        $request->validate([
            'adminid' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email',
            'role' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin = Admin::findOrFail($request->adminid);
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->role = $request->input('role');

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        $admin->save();

        return response()->json(['message' => 'Admin updated successfully.']);
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
