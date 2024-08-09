<?php

namespace App\Http\Controllers;

use App\Models\Team;
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
        
   
    public function NotActiveUser()
    {
        if (!Auth::user()->is_active) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['Your account is not active yet.']);
        }

        // Your logic for the dashboard goes here
        return view('dashboard');
    }
    public function myDocuments()
    {
        return view('user-sidebar.my-documents');
    }


    public function myDocuments_sub($type)
    {
        $players = Player::all();

        switch ($type) {
            case 'CertificateOfRegistration':
                return view('user-sidebar.my-documents.CertificateOfRegistration');
            case 'GalleryOfCoaches':
                return view('user-sidebar.my-documents.GalleryOfCoaches');
            case 'GalleryOfPlayers':
                return view('user-sidebar.my-documents.GalleryOfPlayers');
            case 'ParentalConsent':
                return view('user-sidebar.my-documents.ParentalConsent');
            case 'SummaryOfPlayers':
                return view('user-sidebar.my-documents.SummaryOfPlayers', compact('players'));
            case 'PhotocopyOfVaccineCard':
                return view('user-sidebar.my-documents.PhotocopyOfVaccineCard');
            case 'PhotocopyOfSchoolID':
                return view('user-sidebar.my-documents.PhotocopyOfSchoolID');
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

            
            $teamId = auth()->user()->id;

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
    
}
