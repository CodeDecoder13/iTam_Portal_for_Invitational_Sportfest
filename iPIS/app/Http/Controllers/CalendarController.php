<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Game;


class CalendarController extends Controller
{
    public function calendar()
    {
        return view('admin.admin-sidebar.calendar');
    }
    

    public function getSchool(Request $request)
    {
        $teamId = $request->input('team_id');
        $team = Team::findOrFail($teamId);
        $coach = User::findOrFail($team->coach_id);
        return response()->json(['school' => $coach->school_name]);
    }

    public function storeGame(Request $request)
    {
        // Validate the request data
        $request->validate([
            'sport_category' => 'required|string',
            'team1_id' => 'required|exists:teams,id',
            'team2_id' => 'required|exists:teams,id',
            'game_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        // Create the game in the database
        Game::create([
            'sport_category' => $request->input('sport_category'),
            'team1_id' => $request->input('team1_id'),
            'team2_id' => $request->input('team2_id'),
            'game_date' => $request->input('game_date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        return response()->json(['message' => 'Game successfully added!'], 200);
    }

    public function getGames(Request $request)
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

    public function getTeams(Request $request)
    {
        // Fetch the category from the request
        $category = $request->input('category');

        // Use eager loading to fetch teams and their related coach (user) in one query
        $teams = Team::where('sport_category', $category)
            ->with('coach')  // Assuming there's a relationship set between Team and User (coach)
            ->get(['id', 'name', 'coach_id']);

        // Map teams with their respective school names
        $teamsWithSchool = $teams->map(function ($team) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'school' => $team->coach ? $team->coach->school_name : 'No School',  // Handle null if coach not found
            ];
        });

        return response()->json($teamsWithSchool);
    }

    public function fetchEventsGames($id)
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


    
}