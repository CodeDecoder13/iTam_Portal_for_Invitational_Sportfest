<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Models\Admin;
use App\Models\Player;
use App\Models\Standing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\ActivityLogHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AdminController extends Controller
{
       
      
    public function dashboard()
    {
        $coachId = Auth::user()->id;
    
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

            $activities = ActivityLog::select([
                'activity_logs.*',
                'users.first_name',
                'users.last_name',
                'users.school_name', 
                'users.role'
            ])
            ->join('users', 'activity_logs.user_id', '=', 'users.id')
            ->where('activity_type', '!=', 'Uploaded a document')
            ->orderBy('activity_logs.created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch recent document uploads with relationships
        $recentDocuments = ActivityLog::select([
            'activity_logs.*',
            'users.first_name',
            'users.last_name',
            'users.school_name', 
            'users.role',
            'teams.name as team_name'
        ])
        ->join('users', 'activity_logs.user_id', '=', 'users.id')
        ->join('teams', 'users.id', '=', 'teams.coach_id')  // Join using coach_id
        ->where('activity_type', 'Uploaded a document')
        ->orderBy('activity_logs.created_at', 'desc')
        ->limit(5)
        ->get();

        // Fetch standings data grouped by sport category, limit to top 3 per category
        $standings = Standing::with(['team.coach'])
        ->select('standings.*')
        ->orderBy('wins', 'desc')
        ->get()
        ->groupBy('sport_category')
        ->map(function ($categoryStandings) {
            return $categoryStandings->take(3);
        });

        // Pass this data to the view
        return view('admin.dashboard', compact('totalRegistrations', 'categories', 'incompleteDocuments', 'activities','recentDocuments','standings'));
    }
    public function standing(Request $request)
    {
        $sportCategories = Team::select('sport_category')
            ->distinct()
            ->pluck('sport_category');
        
        $standings = Standing::with(['team.coach'])->get();

        return view('admin.admin-sidebar.standing', compact('sportCategories', 'standings'));
    }
    public function logSystem()
    {
        return view ('admin.admin-sidebar.logs-system');
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
        $players = $query->get();

        //return view('admin.admin-sidebar.team-documents.SummaryOfPlayers', compact('players', 'teams', 'users'));
        return view('admin.admin-sidebar.team-documents.SummaryOfPlayers_suggested', compact('players', 'teams', 'users'));
    }
    //return view('admin.admin-sidebar.team-documents.SummaryOfPlayers', compact('players', 'teams', 'users'));


    public function deleteDocument($player, $filename, $type, $status)
{
    try {
        $player = Player::findOrFail($player);
        
        // Determine which field to update based on document type
        $field = str_contains(strtolower($type), 'consent') ? 'parental_consent' : 'birth_certificate';
        $statusField = $field . '_status';
        
        // Get file path
        $schoolName = Str::slug($player->team->user->school_name);
        $sportCategory = Str::slug($player->team->sport_category);
        $path = "teams/{$schoolName}/{$sportCategory}/{$player->team_id}/players/{$player->id}/{$filename}";

        // Delete file from storage if it exists
        if (Storage::exists($path)) {
            Storage::delete($path);
        }

        // Update database record
        $player->update([
            $field => null,
            $statusField => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);

    } catch (\Exception $e) {
        \Log::error('Document deletion error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error deleting document'
        ], 500);
    }
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

    // Fetch team logos for each user
    foreach ($users as $user) {
        $team = Team::where('coach_id', $user->id)->first();
        if ($team && $team->team_logo && Storage::disk('public')->exists($team->team_logo)) {
            $user->logo_url = Storage::url($team->team_logo);
        } else {
            $user->logo_url = asset('images/placeholder.png');
        }
    }

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
        
        $user = Auth::user();
        $schoolName = Str::slug($user->school_name);
        $sportCategory = Str::slug($request->input('sport'));

        // Build the storage path
        $teamFolderPath = "teams/{$schoolName}/{$sportCategory}";

        // Check if the folder already exists
        if (!Storage::exists($teamFolderPath)) {
            // Create the folder
            Storage::makeDirectory($teamFolderPath);
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
            $query = User::select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.school_name', 'users.role', 'users.gender', 'users.birth_date', 'users.is_active', 'users.created_at')
                ->leftJoin('teams', 'teams.coach_id', '=', 'users.id') // Joining teams table on coach_id
                ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.school_name', 'users.role', 'users.gender', 'users.birth_date', 'users.is_active', 'users.created_at');

            // Check if there is a search term
            if ($request->has('term') && $request->term !== '') {
                $searchTerm = $request->term;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('users.first_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('users.last_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE ?", '%' . $searchTerm . '%');
                });
            }

            $users = $query->get();

            // Fetch all teams
            $teams = Team::select('teams.id', 'teams.name', 'teams.sport_category', 'teams.created_at', 'teams.coach_id')->get();

            $data = [
                'users' => $users,
                'teams' => $teams
            ];

            return view('admin.admin-sidebar.coach-approval', compact('data')); // Render the view with the data
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }
    }
    public function searchCoaches(Request $request)
{
    try {
        $searchTerm = $request->input('term');
        
        // Search without role restriction first to debug
        $users = User::where(function($query) use ($searchTerm) {
            $query->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                  ->orWhereRaw('LOWER(school_name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
        });

        // Log query for debugging
        \Log::info('Search Query:', [
            'sql' => $users->toSql(),
            'bindings' => $users->getBindings(),
            'searchTerm' => $searchTerm,
            'results_count' => $users->count()
        ]);

        $results = $users->get();

        return response()->json([
            'status' => 200,
            'data' => $results,
            'count' => $results->count()
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function Searchmodule(Request $request)

    {
        return view('admin.admin-sidebar.searchmodule');
    }

public function search(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $results = User::where('first_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('school_name', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return response()->json($results);
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
        try {
            $user = User::findOrFail($id);
            
            // Fetch team and player information for the specific user
            $team = Team::where('coach_id', $id)->first();
            
            if ($team && $team->team_logo) {
                // Ensure the logo path exists in storage
                if (!Storage::disk('public')->exists($team->team_logo)) {
                    \Log::warning("Team logo not found: {$team->team_logo}");
                    $team->team_logo = null;
                }
            }
            
            $players = $team ? Player::where('team_id', $team->id)->get() : collect();
    
            return view('admin.admin-sidebar.sub-school-management.card-school-management', 
                compact('user', 'team', 'players')
            );
            
        } catch (\Exception $e) {
            \Log::error('Error in cardSchoolManagement: ' . $e->getMessage());
            return back()->with('error', 'Unable to load school management card.');
        }
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

        $user = Auth::user();
        $schoolName = Str::slug($user->school_name);
        $sportCategory = Str::slug($request->input('sport'));

        // Build the storage path
        $teamFolderPath = "teams/{$schoolName}/{$sportCategory}";

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
     public function documentManagement($id)
    {
        // Fetch the user (coach/admin) by ID
        $user = User::findOrFail($id);

        // Fetch all teams coached by this user
        $teams = Team::where('coach_id', $id)->get();

        // Fetch all players for these teams, including related data
        $players = Player::with(['team', 'user']) // Include relationships to avoid N+1 queries
            ->whereIn('team_id', $teams->pluck('id'))
            ->get();

        // Return the Blade view with compacted data
        return view('admin.admin-sidebar.sub-school-management.document-management', compact('players', 'teams', 'user'));
    }

      
    public function searchUsers(Request $request)
    {
        try {
            $searchTerm = $request->input('term');
            
            $users = User::where(function($query) use ($searchTerm) {
                $query->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($searchTerm) . '%'])
                      ->orWhereRaw('LOWER(school_name) LIKE ?', ['%' . strtolower($searchTerm) . '%']);
            })->get();

            return response()->json([
                'status' => 200,
                'data' => $users,
                'count' => $users->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    //added for settings page
    public function settings()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.admin-sidebar.setting', compact('admin'));
    }

    public function updateSettings(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            
            // Prevent changing role if admin is SysAdmin
            if ($admin->role === 'SysAdmin') {
                $request->merge(['role' => 'SysAdmin']);
            }
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
                'role' => 'required|string|max:50',
            ]);
    
            $admin->fill($validated);
            $admin->save();
    
            return response()->json([
                'status' => 200,
                'message' => 'Profile settings updated successfully',
                'data' => $admin
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $admin = Auth::guard('admin')->user();

            if (!Hash::check($request->current_password, $admin->password)) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Current password is incorrect'
                ]);
            }

            $admin->password = Hash::make($request->password);
            $admin->save();

            return response()->json([
                'status' => 200,
                'message' => 'Password updated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error updating admin password: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Error updating password: ' . $e->getMessage()
            ]);
        }
    }

    // Add method to get current password
    public function getCurrentPassword(Request $request)
    {
        try {
            $admin = Auth::guard('admin')->user();
            if (!$admin) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Admin not found'
                ]);
            }

            // Get the decrypted password from the database
            $password = $request->input('current_password');
            
            return response()->json([
                'status' => 200,
                'password' => $password,
                'message' => 'Password fetched successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error fetching password: ' . $e->getMessage()
            ]);
        }
    }

    // search module in school management
    public function searchModelUser(Request $request)
    {
        $searchTerm = $request->input('searchTerm');
        $searchResults = User::where('first_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('last_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('email', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return view('admin.admin-sidebar.sub-school-management.card-school-management', compact('searchResults'));
    }

    public function viewDocument($schoolName, $sportCategory, $teamId, $playerId, $filename)
    {
        // Construct the path to the document
        $path = storage_path("app/public/documents/{$schoolName}/{$sportCategory}/team_{$teamId}/player_{$playerId}/{$filename}");

        // Check if file exists
        if (!file_exists($path)) {
            abort(404, 'Document not found');
        }

        // Return the file response
        return response()->file($path);
    }
}
