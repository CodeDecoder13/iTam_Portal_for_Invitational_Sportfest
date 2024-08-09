<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player; // Make sure to import your Player model

class PlayerController extends Controller
{
    // Method to show the player list
    public function index()
    {
        $players = Player::all(); // Fetch all players
        return view('players.index', compact('players')); // Adjust path as necessary
    }

    // Method to handle adding a new player
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'jersey_no' => 'required|integer|min:1|max:99',
            'team_id' => 'required|integer|exists:teams,id',
            // 'coach_id' validation removed as it will be dynamically assigned
        ]);
    
        // Create a new player with the logged-in user's ID as the coach_id
        Player::create([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthday' => $request->input('birthday'),
            'gender' => $request->input('gender'),
            'jersey_no' => $request->input('jersey_no'),
            'team_id' => $request->input('team_id'), // Use the input team_id
            'coach_id' => auth()->user()->id, // Set coach_id to the ID of the logged-in user
            'status' => 'For Review', // Default status
        ]);
    
        return redirect()->back()->with('success', 'Player added successfully!');
    }
    
    // Method to show the edit form for a specific player
    public function edit($id)
    {
        $player = Player::findOrFail($id);
        return view('players.edit', compact('player')); // Use a separate view for editing
    }

    // Method to handle updating a player
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'birthday' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'jersey_no' => 'required|integer|min:1|max:99',
            'team_id' => 'required|integer|exists:teams,id',
            'status' => 'nullable|string|max:255',
        ]);

        $player = Player::findOrFail($id);
        $player->update([
            'first_name' => $request->input('first_name'),
            'middle_name' => $request->input('middle_name'),
            'last_name' => $request->input('last_name'),
            'birthday' => $request->input('birthday'),
            'gender' => $request->input('gender'),
            'jersey_no' => $request->input('jersey_no'),
            'team_id' => $request->input('team_id'),
            'status' => $request->input('status', 'For Review'), // Default to 'For Review' if not provided
        ]);

        return redirect()->back()->with('success', 'Player updated successfully!');
    }

    // Method to handle deleting a player
    public function destroy($id)
    {
        $player = Player::findOrFail($id);
        $player->delete();

        return redirect()->back()->with('success', 'Player deleted successfully!');
    }
}
