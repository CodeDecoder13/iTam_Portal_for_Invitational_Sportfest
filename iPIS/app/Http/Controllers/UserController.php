<?php

namespace App\Http\Controllers;


use App\Models\Team;
use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('dashboard');
    }   
        
    public function myDocuments()
    {
        $coachId = Auth::user()->id;

        // Fetch players associated with the logged-in coach
        $players = Player::where('coach_id', $coachId)->get();

        // Get the most recent updated_at value
        $lastUpdated = $players->max('last_update');

        // Determine the overall status
        $status = $players->isNotEmpty() ? 'Approved' : 'No File Attached'; // Default status

        return view('user-sidebar.my-documents', compact('lastUpdated', 'status'));
    }

    public function selectTeam()
    {
        $coachId = Auth::user()->id;
        $teams = Team::where('coach_id', $coachId)->get();
        return view('layouts.sidebar', compact('teams'));
    }


    // In your UserController

public function myDocuments_sub($type)
{
    $coachId = Auth::user()->id;
    $players = Player::all();
    $teams = Team::where('coach_id', $coachId)->get();

    foreach ($players as $player) {
        $player->has_birth_certificate = !is_null($player->birth_certificate);
        $player->has_parental_consent = !is_null($player->parental_consent);
    }

    switch ($type) {
        case 'SummaryOfPlayers':
            return view('user-sidebar.my-documents.SummaryOfPlayers', compact('players'));
        default:
            return redirect()->route('my-documents');
    }
}

//added for base line
   
  
    // Method to create a folder for a newly created player
    public function createPlayerFolder(Player $player)
    {
        // Fetch the school name, sport category, and player's name
        $team = $player->team;
        $coach = $team->coach;
        $schoolName = $coach->school_name;
        $sportCategory = $team->sport_category;
        $playerName = $player->first_name . ' ' . $player->last_name;

        // Define the path for the player's folder
        $playerFolderPath = "public/{$schoolName}/{$sportCategory}/{$playerName}";

        // Check if the folder already exists
        if (!Storage::exists($playerFolderPath)) {
            // Create the folder
            Storage::makeDirectory($playerFolderPath);
        }
    }

    public function uploadPlayerDocuments(Request $request, $playerId)
    {
        $player = Player::find($playerId);

        // Ensure the player's folder exists
        $this->createPlayerFolder($player);

        // Define the path for the player's folder
        $team = $player->team;
        $coach = $team->coach;
        $schoolName = $coach->school_name;
        $sportCategory = $team->sport_category;
        $playerName = $player->first_name . ' ' . $player->last_name;

        $playerFolderPath = "public/{$schoolName}/{$sportCategory}/{$playerName}";

        if ($request->hasFile('birth_certificate')) {
            $birthCertificate = $request->file('birth_certificate')->store("{$playerFolderPath}/birth_certificates", 'public');
            $player->birth_certificate = $birthCertificate;
        }

        if ($request->hasFile('parental_consent')) {
            $parentalConsent = $request->file('parental_consent')->store("{$playerFolderPath}/parental_consents", 'public');
            $player->parental_consent = $parentalConsent;
        }

        $player->save();

        return back()->with('success', 'Documents uploaded successfully.');
    }



   
    public function addPlayers()
    {
        return view('user-sidebar.add-players');
    }
    public function storePlayers(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'players' => 'array|required',
            'players.*.firstName' => 'required|string|max:255',
            'players.*.middleName' => 'nullable|string|max:255',
            'players.*.lastName' => 'required|string|max:255',
            'players.*.birthday' => 'required|date',
            'players.*.gender' => 'required|string|in:Male,Female',
            'players.*.jersey_no' => 'required|integer|min:1|max:99',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $teamId = auth()->user()->team_id;

        foreach ($request->input('players') as $playerData) {
            Player::updateOrCreate(
                [
                    'team_id' => $teamId,
                    'first_name' => $playerData['firstName'],
                    'last_name' => $playerData['lastName']
                ],
                [
                    'middle_name' => $playerData['middleName'] ?? null,
                    'birthday' => $playerData['birthday'] ?? null,
                    'gender' => $playerData['gender'] ?? null,
                    'jersey_no' => $playerData['jersey_no']
                ]
            );
        }

        return response()->json(['message' => 'Players saved successfully!']);

    } catch (\Exception $e) {
        return response()->json(['message' => 'An error occurred while saving players.'], 500);
    }
}

    public function myCalendar()
    {
        return view('user-sidebar.my-calendar');
    }

    public function myPlayers()
    {
        return view('user-sidebar.my-players');
    }
    public function addTeams()
    {
        return view('user-sidebar.add-Teams');
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
    
        // Return a response
        return response()->json(['message' => 'Team saved successfully!', 'team' => $team]);
    }
}
    
    

