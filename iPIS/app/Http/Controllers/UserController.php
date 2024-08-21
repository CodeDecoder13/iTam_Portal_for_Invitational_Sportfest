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
        $player = Player::findOrFail($playerId);

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

        $documentUploaded = false;

        // Handle the upload of the birth certificate
        if ($request->hasFile('birth_certificate')) {
            $birthCertificate = $request->file('birth_certificate');
            $birthCertificateName = 'birth_certificate.' . $birthCertificate->getClientOriginalExtension();
            $birthCertificate->storeAs($playerFolderPath, $birthCertificateName);
            $player->birth_certificate = $birthCertificateName;
            $player->has_birth_certificate = true; // Mark that the player has a birth certificate
            $documentUploaded = true; // Flag that a document has been uploaded
        }

        // Handle the upload of the parental consent
        if ($request->hasFile('parental_consent')) {
            $parentalConsent = $request->file('parental_consent');
            $parentalConsentName = 'parental_consent.' . $parentalConsent->getClientOriginalExtension();
            $parentalConsent->storeAs($playerFolderPath, $parentalConsentName);
            $player->parental_consent = $parentalConsentName;
            $player->has_parental_consent = true; // Mark that the player has parental consent
            $documentUploaded = true; // Flag that a document has been uploaded
        }

        // If any document has been uploaded, update the status to "For Review"
        if ($documentUploaded) {
            $player->status = 'For Review';
        } else {
            // If no document is uploaded, set a message or handle accordingly
            $player->status = 'No File Attached';
        }

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

            Player::updateOrCreate(
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

    public function myPlayers()
    {
        $players = Player::all();
        $coachId = Auth::user()->id;

        $teams = Team::where('coach_id', $coachId)->get();
        return view('user-sidebar.my-players', compact('players', 'teams'));
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
