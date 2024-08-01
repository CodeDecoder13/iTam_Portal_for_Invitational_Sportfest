<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 

class TestingController extends Controller
{
    public function test()
    {
        return view('test');
    }

    public function addteam(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'teamName' => 'required|string|max:255',
            'teamAcronym' => 'required|string|max:5',
            'sportCategory' => 'required|string',
            'players' => 'array',
            'players.*.firstName' => 'required|string|max:255', // first_name
            'players.*.middleName' => 'nullable|string|max:255', // middle_name
            'players.*.lastName' => 'required|string|max:255', // last_name
            'players.*.birthday' => 'required|date', // birthday
            'players.*.gender' => 'required|string|in:Male,Female' // gender
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Get the currently signed-in user's ID
        $coachId = auth()->user()->id;

        // Create or find the team
        $team = Team::updateOrCreate(
            ['name' => $request->input('teamName')],
            [
                'acronym' => $request->input('teamAcronym'),
                'sport_category' => $request->input('sportCategory'),
                'coach_id' => $coachId
            ]
        );

        // Save players
        if ($request->has('players')) {
            foreach ($request->input('players') as $playerData) {
                Player::updateOrCreate(
                    [
                        'team_id' => $team->id,
                        'first_name' => $playerData['firstName'],
                        'last_name' => $playerData['lastName']
                    ],
                    [
                        'middle_name' => $playerData['middleName'] ?? null,
                        'birthday' => $playerData['birthday'] ?? null,
                        'gender' => $playerData['gender'] ?? null
                    ]
                );
            }
        }

        // Return a response
        return response()->json(['message' => 'Team and players saved successfully!']);
    }
}
