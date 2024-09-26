<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Admin;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Fetch the total number of teams and players grouped by sport category
        $registrations = Team::withCount('players')->get();

        $totalRegistrations = $registrations->sum('players_count');

        // Count for each specific category (change according to your needs)
        $categories = [
            'Boys Basketball Developmental' => 0,
            'Boys Basketball Competitive' => 0,
            'Girls Basketball Developmental' => 0,
            'Girls Basketball Competitive' => 0,
            'Boys Volleyball Developmental' => 0,
            'Boys Volleyball Competitive' => 0,
            'Girls Volleyball Developmental' => 0,
            'Girls Volleyball Competitive' => 0,
        ];

        foreach ($registrations as $team) {
            if (isset($categories[$team->sport_category])) {
                $categories[$team->sport_category] += $team->players_count;
            }
        }

        $incompleteDocuments = Player::where('birth_certificate_status', '!=', 2)
            ->orWhere('parental_consent_status', '!=', 2)
            ->count();

        // Pass this data to the view
        return view('admin.dashboard', compact('totalRegistrations', 'categories', 'incompleteDocuments'));
    }
    // added for documents
    public function documents()
    {
        // Fetch all players (adjust this query according to your needs)
        $players = Player::with('team')->get();

        // Group players by sport_category and team name to avoid repetition
        $groupedPlayers = $players->groupBy(function ($player) {
            return $player->team->sport_category . '|' . $player->team->name;
        });

        return view('admin.admin-sidebar.documents', compact('groupedPlayers'));
    }
    // added for documents summary of players
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
    public function schoolManagement(Request $request)
    {
        $query = User::query(); // Start with all users

        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%')
                ->orWhere('school_name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10);

        // Debugging: Log the users data
        \Log::info("Fetched users for school management:", $users->toArray());

        return view('admin.admin-sidebar.school-management', compact('users'));
    }
        


    public function usersManagement()
    {
        $admins = Admin::select('id', 'name', 'email', 'is_active', 'created_at', 'role')
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
            'school_name' => 'required',
            'string',
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
        // Check if the adminid is being received
        if (!$request->has('adminid')) {
            return response()->json(['error' => 'Admin ID is missing'], 400);
        }

        // Validate the request
        $request->validate([
            'adminid' => 'required|exists:admins,id',
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($request->adminid)],
            'role' => 'required|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Find the admin by ID and update fields
        $admin = Admin::findOrFail($request->adminid);
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->role = $request->input('role');

        // Update password only if it's provided
        if ($request->filled('password')) {
            $admin->password = Hash::make($request->input('password'));
        }

        // Save the updated admin
        $admin->save();

        return response()->json(['message' => 'Admin updated successfully.']);
    }

    public function deleteAdmin(Request $request)
    {
        try {
            $admin = Admin::find($request->adminid); // Use the 'adminid' from the request

            if ($admin && $admin->delete()) {
                return response()->json(['status' => 200, 'message' => 'Admin deleted successfully.']);
            } else {
                return response()->json(['status' => 400, 'message' => 'Failed to delete admin.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


    // Update user details
    public function updateUser(Request $request)
    {
        try {
            // Validate incoming data
            $request->validate([
                'id' => 'required|integer', // The ID of the user to update
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'role' => 'required|string',
                'school_name' => 'required|string|max:255',
                'password' => 'nullable|string|min:8|confirmed', // Password confirmation required only when changing
            ]);

            // Find the user by ID
            $user = User::findOrFail($request->id);

            // Update user details
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->school_name = $request->school_name;

            // Update password only if provided
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }

            // Save updated data
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'User updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating user: ' . $e->getMessage(),
            ]);
        }
    }









    //public function coachApproval() {
    //   return view('admin.admin-sidebar.coach-approval');
    // }
    public function coachApproval(Request $request)
    {
        try {
            // Select all users with their corresponding team info
            $users = User::select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.school_name', 'users.role', 'users.is_active', 'users.created_at')
                ->leftJoin('teams', 'teams.coach_id', '=', 'users.id') // Joining teams table on coach_id
                ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.school_name', 'users.role', 'users.is_active', 'users.created_at')
                ->get();


            // Fetch all teams
            $teams = Team::select('teams.id', 'teams.name', 'teams.sport_category', 'teams.created_at', 'teams.coach_id')
                ->get();

            // Logging data to ensure it's being fetched correctly
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


    // AdminController.php
    public function deleteCoach(Request $request)
{
    try {
        Log::info('Attempting to delete user with ID: ' . $request->id);
        
        // Find the user by the ID from the request
        $user = User::find($request->id);

        if ($user) {
            Log::info('User found: ', $user->toArray());
            if ($user->delete()) {
                Log::info('User deleted successfully');
                return response()->json([
                    'status' => 200,
                    'message' => 'User deleted successfully.'
                ]);
            } else {
                Log::error('Failed to delete user');
                return response()->json([
                    'status' => 400,
                    'message' => 'Failed to delete user.'
                ]);
            }
        } else {
            Log::error('User not found with ID: ' . $request->id);
            return response()->json([
                'status' => 404,
                'message' => 'User not found.'
            ]);
        }
    } catch (\Exception $e) {
        Log::error('Error deleting user: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Error: ' . $e->getMessage()
        ]);
    }
}
    //card school management
    public function cardSchoolManagement($id)
    {
        $user = User::findOrFail($id);
        
        // Fetch team and player information for the specific user
        $team = Team::where('coach_id', $id)->first();
        $players = $team ? Player::where('team_id', $team->id)->get() : collect();
        
    

        return view('admin.admin-sidebar.sub-school-management.card-school-management', compact('user', 'team', 'players'));
    }
    // added player management 
    public function playerManagement($id)
    {
        $user = User::findOrFail($id);
        
        // Fetch all teams coached by this user
        $teams = Team::where('coach_id', $id)->get();
        
        // Fetch all players for these teams
        $players = Player::whereIn('team_id', $teams->pluck('id'))->get();
        
 
        return view('admin.admin-sidebar.sub-school-management.player-management', compact('user', 'teams', 'players'));
    }
    //team management
    public function teamManagement($id)
    {
        $user = User::findOrFail($id);
        
        // Fetch all teams coached by this user
        $teams = Team::where('coach_id', $id)->get();
        
        // Fetch all players for these teams
        $players = Player::whereIn('team_id', $teams->pluck('id'))->get();
        
        

        return view('admin.admin-sidebar.sub-school-management.team-management', compact('user', 'teams', 'players'));
    }

    //store teams
    public function storeTeam(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'sport' => 'required|string',
            'team_name' => 'required|string|max:255',
            'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:25600',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the user (coach) by ID
        $coach = User::findOrFail($id);

        // Handle the team logo upload
        if ($request->hasFile('team_logo')) {
            $teamLogoPath = $request->file('team_logo')->store('public/team_logos');
            $teamLogoPath = str_replace('public/', '', $teamLogoPath);
        } else {
            $teamLogoPath = null;
        }

        // Create or update the team
        $team = Team::updateOrCreate(
            ['name' => $request->input('team_name'), 'coach_id' => $coach->id],
            [
                'sport_category' => $request->input('sport'),
                'logo_path' => $teamLogoPath,
            ]
        );

        // Define the path for the sport category folder
        $teamFolderPath = "public/{$coach->school_name}/{$team->sport_category}";

        // Check if the folder already exists
        if (!Storage::exists($teamFolderPath)) {
            // Create the folder
            Storage::makeDirectory($teamFolderPath);
        }

        // Return a response
        return response()->json(['message' => 'Team saved successfully!', 'team' => $team]);
    }
    //delete team
    public function deleteTeam($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->delete();

            return response()->json(['message' => 'Team deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting team', 'error' => $e->getMessage()], 500);
        }
    }
    //logs management
    public function logsManagement()
    {
        return view('admin.admin-sidebar.sub-school-management.logs-management');
    }
     //document management
     public function documentManagement()
     {
         return view('admin.admin-sidebar.sub-school-management.document-management');
     }
      
}
