<?php

namespace App\Http\Controllers;


use App\Models\Team;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Player;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        
        return view('dashboard');
    }   
        
    public function myDocuments()
    {
        return view('user-sidebar.my-documents');
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
        //$players = Player::all();
        $teams = Team::where('coach_id', $coachId)->get();

        switch ($type) {
            case 'CertificateOfRegistration':
                return view('user-sidebar.my-documents.CerfiticateOfRegistration', compact('players'));
            case 'GalleryOfCoaches':
                return view('user-sidebar.my-documents.GalleryOfCoaches');
            case 'GalleryOfPlayers':
                return view('user-sidebar.my-documents.GalleryOfPlayers', compact('players'));
            case 'ParentalConsent':
                return view('user-sidebar.my-documents.ParentalConsent', compact('players'));
            case 'SummaryOfPlayers':
                return view('user-sidebar.my-documents.SummaryOfPlayers', compact('players'));
            case 'BirthCertificate':
                return view('user-sidebar.my-documents.BirthCertificate', compact('players'));
            case 'PhotocopyOfSchoolID':
                return view('user-sidebar.my-documents.PhotocopyOfSchoolID', compact('players'));
            default:
                return redirect()->route('my-documents');
                //return view('user-sidebar.my-documents.sub',compact('type'));
        }
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
            'team_name' => 'required|string|max:255',
            'team_acronym' => 'required|string|max:5',
            'sport' => 'required|string',
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

        
        // Retrieve the newly created or updated team
        //$team = Team::where('name', $request->input('team_name'))->firstOrFail();

        // Get the currently signed-in user's ID
        $coachId = auth()->user()->id;

        // Create or find the team
        $team = Team::updateOrCreate(
            ['name' => $request->input('team_name')],
            [
                'acronym' => $request->input('team_acronym'),
                'sport_category' => $request->input('sport'),
                'coach_id' => $coachId,
                'logo_path' => $teamLogoPath,
            ]
        );
        
        // Return a response
        //return response()->json(['message' => 'Team saved successfully!']);
        return response()->json(['message' => 'Team saved successfully!', 'team' => $team]);
    }
}
    
    

