<?php

namespace App\Http\Controllers;


use App\Models\Team;
use App\Models\User;
use App\Models\Player;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Helpers\TeamHelper;

class UserController extends Controller
{
    public function dashboard()
    {
        $coachId = Auth::user()->id;
        $teams = Team::where('coach_id', $coachId)->get();
        return view('dashboard', compact('teams'));
       // return view('dashboard');
    }

    public function myDocuments()
{
    $coachId = Auth::user()->id;

    // Fetch players associated with the logged-in coach and eager load the 'team' relationship
    $players = Player::where('coach_id', $coachId)
        ->with('team')
        ->get();

    // Group players by sport_category and team name to avoid repetition
    $groupedPlayers = $players->groupBy(function ($player) {
        return $player->team->sport_category . '|' . $player->team->name;
    });

    return view('user-sidebar.my-documents', compact('groupedPlayers'));
}


    public function selectTeam()
    {
        $coachId = Auth::user()->id;
        $teams = Team::where('coach_id', $coachId)->get();
        return view('layouts.sidebar', compact('teams'));
    }


    public function myDocuments_sub($type, $sport_category, $name)
    {
        $coachId = Auth::user()->id;

        // Fetch the team based on sport_category and team name
        $team = Team::where('sport_category', $sport_category)
                    ->where('name', $name)
                    ->first();

        // If the team doesn't exist, redirect back with an error message
        if (!$team) {
            return redirect()->route('my-documents')->with('error', 'Team not found.');
        }

        // Fetch only the players associated with the current coach and the specific team
        $players = Player::where('coach_id', $coachId)
                        ->where('team_id', $team->id)
                        ->with('team')
                        ->get();

        // Handle different document types
        switch ($type) {
            case 'SummaryOfPlayers':
                return view('user-sidebar.my-documents.SummaryOfPlayers', compact('players'));
            default:
                return redirect()->route('my-documents')->with('error', 'Invalid document type.');
        }
    }



    //added for base line


    // Method to create a folder for a newly created player
    public function createPlayerFolder(Player $player)
    {
        // Fetch the school name, team ID, and player ID
        $team = $player->team;
        $coach = $team->coach;
        $schoolName = $coach->school_name;
        $sportCategory = $team->sport_category;
        $teamId = $team->id;
        $playerId = $player->id;

        // Define the path for the player's folder using school_name, team_id, and player_id
        $playerFolderPath = "public/{$schoolName}/{$sportCategory}/{$teamId}/{$playerId}";

        // Check if the folder already exists
        if (!Storage::exists($playerFolderPath)) {
            // Create the folder
            Storage::makeDirectory($playerFolderPath);
        }
    }


    public function uploadPlayerDocuments(Request $request, $playerId)
    {
        $player = Player::findOrFail($playerId);

        $team = $player->team;
        $coach = $team->coach;
        $schoolName = $coach->school_name;
        $sportCategory = $team->sport_category;
        $teamId = $team->id;

        // Define the path for the player's folder
        $playerFolderPath = "public/$schoolName/$sportCategory/$teamId/$playerId";

        // Check if the folder already exists
        if (!Storage::exists($playerFolderPath)) {
            // Create the folder
            Storage::makeDirectory($playerFolderPath);
        }

        $documentUploaded = false;

        // Handle the upload of the birth certificate
        if ($request->hasFile('birth_certificate')) {
            $birthCertificate = $request->file('birth_certificate');
            $birthCertificateName = 'birth_certificate.' . $birthCertificate->getClientOriginalExtension();
            $birthCertificate->storeAs($playerFolderPath, $birthCertificateName);
            $player->birth_certificate = $birthCertificateName;
            $player->birth_certificate_status = 1; // Set status to "For Review"
            $documentUploaded = true;
        }

        // Handle the upload of the parental consent
        if ($request->hasFile('parental_consent')) {
            $parentalConsent = $request->file('parental_consent');
            $parentalConsentName = 'parental_consent.' . $parentalConsent->getClientOriginalExtension();
            $parentalConsent->storeAs($playerFolderPath, $parentalConsentName);
            $player->parental_consent = $parentalConsentName;
            $player->parental_consent_status = 1; // Set status to "For Review"
            $documentUploaded = true;
        }

        // If any document has been uploaded, update the status to "For Review"

        // Save the player's updated information
        $player->save();

        return redirect()->back()->with('success', 'Documents uploaded successfully and status updated to "For Review".');
    }


    // Method to view and download PSA Birth Certificate
    public function viewBirthCertificate($id)
    {
        $player = Player::findOrFail($id);

        if ($player->birth_certificate) {
            $filePath = 'public/birth_certificates/' . $player->birth_certificate;

            // Check if the file exists in the storage
            if (Storage::exists($filePath)) {
                $mimeType = Storage::mimeType($filePath);
                return Storage::download($filePath, $player->birth_certificate, [
                    'Content-Type' => $mimeType,
                ]);
            } else {
                return redirect()->back()->with('error', 'Birth Certificate file not found.');
            }
        } else {
            return redirect()->back()->with('error', 'No Birth Certificate uploaded for this player.');
        }
    }

    // Method to view and download Parental Consent
    public function viewParentalConsent($id)
    {
        $player = Player::findOrFail($id);

        if ($player->parental_consent) {
            $filePath = 'public/parental_consents/' . $player->parental_consent;

            // Check if the file exists in the storage
            if (Storage::exists($filePath)) {
                $mimeType = Storage::mimeType($filePath);
                return Storage::download($filePath, $player->parental_consent, [
                    'Content-Type' => $mimeType,
                ]);
            } else {
                return redirect()->back()->with('error', 'Parental Consent file not found.');
            }
        } else {
            return redirect()->back()->with('error', 'No Parental Consent uploaded for this player.');
        }
    }



    // Method to delete PSA Birth Certificate
    public function deleteBirthCertificate($id)
    {
        $player = Player::findOrFail($id);

        // Delete the file if exists
        if ($player->birth_certificate) {
            Storage::delete('public/birth_certificates/' . $player->birth_certificate);
            $player->birth_certificate = null;
            $player->has_birth_certificate = false;
            $player->save();
        }

        return redirect()->back()->with('success', 'PSA Birth Certificate deleted successfully.');
    }
    // Method to delete Parental Consent
    public function deleteParentalConsent($id)
    {
        $player = Player::findOrFail($id);

        // Delete the file if exists
        if ($player->parental_consent) {
            Storage::delete('public/parental_consents/' . $player->parental_consent);
            $player->parental_consent = null;
            $player->has_parental_consent = false;
            $player->save();
        }

        return redirect()->back()->with('success', 'Parental Consent deleted successfully.');
    }

    public function downloadDocument($playerId)
    {
        $player = Player::findOrFail($playerId);
        $type = request('type');
        $filePath = $type === 'birth_certificate'
            ? "public/birth_certificates/{$player->birth_certificate}"
            : "public/parental_consents/{$player->parental_consent}";

        return Storage::download($filePath);
    }




    public function addPlayers()
    {
        $coachId = Auth::user()->id;

        // Fetch players associated with the logged-in coach
        $teams = Team::where('coach_id', $coachId)->get();
        return view('user-sidebar.add-players', compact('teams'));
    }

    public function storePlayers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'teamNumber' => 'required|integer|exists:teams,id',
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|string|in:Male,Female',
                'jersey_no' => 'required|integer|min:1|max:99',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $player = Player::updateOrCreate(
                [
                    'team_id' => $request->input('teamNumber'),
                    'first_name' => $request->input('firstName'),
                    'last_name' => $request->input('lastName'),
                    'coach_id' => Auth::user()->id
                ],
                [
                    'middle_name' => $request->input('middleName'),
                    'birthday' => $request->input('birthday'),
                    'gender' => $request->input('gender'),
                    'jersey_no' => $request->input('jersey_no')
                ]
            );
            // Call the createPlayerFolder method to create the player's folder
            $this->createPlayerFolder($player);

            return response()->json(['message' => 'Players saved successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while saving players.',
                'error' => $e->getMessage()
            ], 500);
        }
    }




    public function updatePlayers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'playerid' => 'required|integer|exists:players,id',
                'teamNumber' => 'required|integer|exists:teams,id',
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|string|in:Male,Female',
                'jersey_no' => 'required|integer|min:1|max:99',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Player::updateOrCreate(
                [
                    'id' => $request->input('playerid'),
                ],
                [
                    'team_id' => $request->input('teamNumber'),
                    'first_name' => $request->input('firstName'),
                    'last_name' => $request->input('lastName'),
                    'coach_id' => Auth::user()->id,
                    'middle_name' => $request->input('middleName'),
                    'birthday' => $request->input('birthday'),
                    'gender' => $request->input('gender'),
                    'jersey_no' => $request->input('jersey_no')
                ]
            );

            return response()->json(['message' => 'Players saved successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred while saving players.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function myCalendar()
    {
        return view('user-sidebar.my-calendar');
    }
    public function getGamesUser(Request $request)
    {
        // Fetch games from the database
        $games = Game::select('id', 'sport_category', 'team1_id', 'team2_id', 'game_date', 'start_time', 'end_time')
            ->with(['team1', 'team2']) // Assuming you have relationships for team1 and team2 in Game model
            ->get();

        // Map the data to the format FullCalendar expects
        $events = $games->map(function ($game) {
            return [
                'id' => $game->id,
                'title' => $game->team1->name . ' - ' . $game->team2->name,  // Display teams names in the title
                'start' => $game->game_date . 'T' . $game->start_time,  // Combine date and start time
                'end' => $game->game_date . 'T' . $game->end_time,      // Combine date and end time
                'extendedProps' => [
                    'sport_category' => $game->sport_category,
                    'team1_name' => $game->team1->name,
                    'team2_name' => $game->team2->name,
                ],
            ];
        });

        return response()->json($events);
    }
    public function fetchEventsGamesUser($id)
    {
        // Fetch the game data by ID
        $game = Game::with(['team1', 'team2', 'comments', 'team1.coach', 'team2.coach']) // Assuming you have relationships defined
            ->findOrFail($id);

        // Return the game data as JSON
        return response()->json([
            'team1' => [
                'name' => $game->team1->name,
                'score' => $game->team1_score,
                'school' => $game->team1->coach ? $game->team1->coach->school_name : 'No School',  // Fetch school name from the user model
            ],
            'team2' => [
                'name' => $game->team2->name,
                'score' => $game->team2_score,
                'school' => $game->team2->coach ? $game->team2->coach->school_name : 'No School',  // Fetch school name from the user model
            ],
            'game_date' => $game->game_date,
            'sport_category' => $game->sport_category,
            'comments' => $game->comments, 
        ]);
    }

    public function myPlayers()
    {
        $coachId = Auth::user()->id;

        // Fetch only the players associated with the current coach
        $players = Player::where('coach_id', $coachId)->get();

        // Fetch the teams associated with the current coach
        $teams = Team::where('coach_id', $coachId)->get();

        return view('user-sidebar.my-players', compact('players', 'teams'));
    }

    public function addTeams()
    {
        return view('user-sidebar.add-teams');
    }
    public function storeTeam(Request $request)
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

        // Handle the team logo upload
        if ($request->hasFile('team_logo')) {
            $teamLogoPath = $request->file('team_logo')->store('public/team_logos');
            $teamLogoPath = str_replace('public/', '', $teamLogoPath);
        } else {
            $teamLogoPath = null;
        }

        // Get the currently signed-in user's ID
        $coachId = auth()->user()->id;

        // Create or update the team
        $team = Team::updateOrCreate(
            ['name' => $request->input('team_name')],
            [
                'sport_category' => $request->input('sport'),
                'coach_id' => $coachId,
                'logo_path' => $teamLogoPath,
            ]
        );

        // Fetch the school name and sport category from the team model
        $coach = $team->coach;
        $schoolName = $coach->school_name;
        $sportCategory = $team->sport_category;

        // Define the path for the sport category folder
        $teamFolderPath = "public/{$schoolName}/{$sportCategory}";

        // Check if the folder already exists
        if (!Storage::exists($teamFolderPath)) {
            // Create the folder
            Storage::makeDirectory($teamFolderPath);
        }
         // Log the activity
         ActivityLogHelper::logActivity(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->role,
            $user->school_name,
            
            'team_registered',
            'A new team is registered.'
        );

        // Return a response
        return response()->json(['message' => 'Team saved successfully!', 'team' => $team]);
    }
   
    public function deletePlayer(Request $request)
    {
        try {
            // Find the player by ID
            $player = Player::findOrFail($request->id);

            // Delete the player
            if ($player->delete()) {
                return response()->json(['status' => 200, 'message' => 'Player deleted successfully.']);
            } else {
                return response()->json(['status' => 400, 'message' => 'Failed to delete player.']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    // added for sidebar my team
    public function myTeam(Request $request)
    {
        $coachId = Auth::id();
        
        $query = Team::where('coach_id', $coachId);

        // Handle search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sport_category', 'like', "%{$search}%");
            });
        }

        // Handle filters
        if ($request->has('sport') && $request->input('sport') !== '') {
            $query->where('sport_category', $request->input('sport'));
        }

        if ($request->has('status') && $request->input('status') !== '') {
            $query->where('is_active', $request->input('status') === 'active');
        }

        $teams = $query->paginate(10);

        // Get unique sport categories for the filter dropdown
        $sportCategories = Team::where('coach_id', $coachId)
                               ->distinct()
                               ->pluck('sport_category');

        return view('user-sidebar.my-team', compact('teams', 'sportCategories'));
    }
    public function storeMyTeam(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sport' => 'required|string',
            'team_name' => 'required|string|max:255',
            'team_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:25600',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teamLogoPath = null;
        if ($request->hasFile('team_logo')) {
            $teamLogoPath = $request->file('team_logo')->store('team_logos', 'public');
        }

        $team = Team::create([
            'name' => $request->team_name,
            'sport_category' => $request->sport,
            'coach_id' => Auth::id(),
            'logo_path' => $teamLogoPath,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'team' => $team]);
    }
    //individual team management
    public function teamManagement($id)
    {
        $team = Team::with(['players', 'coach'])->findOrFail($id);
        $user = $team->coach; // This assumes the coach is stored in the 'coach' relationship

        // Get the count of active and inactive players
        $activePlayers = $team->players->where('is_active', true)->count();
        $inactivePlayers = $team->players->where('is_active', false)->count();

    

        return view('user-sidebar.sub-team-management.team-management', compact('team', 'user', 'activePlayers', 'inactivePlayers'));
    }
    // added for sub player management
    public function subPlayerManagement($id)
    {
        $user = Auth::user();
        $team = Team::where('coach_id', $user->id)
                    ->where('id', $id)
                    ->firstOrFail();
        
        $players = $team->players; 

        return view('user-sidebar.sub-team-management.sub-player-management', compact('team', 'players'));
    }
    
    // added for sub documents management
    public function subDocumentsManagement($id)
{
    $user = Auth::user();
    $team = Team::where('coach_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();
    
    $players = $team->players()->with('team')->get();

    return view('user-sidebar.sub-team-management.sub-documents-management', compact('team', 'players'));
}
 // added for sub players management
 public function storeSubPlayers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'team_id' => 'required|integer|exists:teams,id',
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|string|in:Male,Female',
                'jersey_no' => 'required|integer|min:1|max:99',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $player = Player::create([
                'team_id' => $request->input('team_id'),
                'first_name' => $request->input('firstName'),
                'middle_name' => $request->input('middleName'),
                'last_name' => $request->input('lastName'),
                'birthday' => $request->input('birthday'),
                'gender' => $request->input('gender'),
                'jersey_no' => $request->input('jersey_no'),
                'coach_id' => Auth::user()->id
            ]);

            // Call the createPlayerFolder method to create the player's folder
            $this->createPlayerFolder($player);

            return response()->json(['success' => true, 'message' => 'Player added successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving player.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    // added for update player sub player management
    public function updateSubPlayers(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'player_id' => 'required|integer|exists:players,id',
                'team_id' => 'required|integer|exists:teams,id',
                'firstName' => 'required|string|max:255',
                'middleName' => 'nullable|string|max:255',
                'lastName' => 'required|string|max:255',
                'birthday' => 'required|date',
                'gender' => 'required|string|in:Male,Female',
                'jersey_no' => 'required|integer|min:1|max:99',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $player = Player::findOrFail($request->input('player_id'));
            $player->update([
                'team_id' => $request->input('team_id'),
                'first_name' => $request->input('firstName'),
                'middle_name' => $request->input('middleName'),
                'last_name' => $request->input('lastName'),
                'birthday' => $request->input('birthday'),
                'gender' => $request->input('gender'),
                'jersey_no' => $request->input('jersey_no')
            ]);

            return response()->json(['success' => true, 'message' => 'Player updated successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating player.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
