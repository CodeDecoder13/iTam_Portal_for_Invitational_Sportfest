<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $upcomingGames = Game::orderBy('game_date', 'asc')->paginate(5);
        // ... other code ...

        return view('dashboard', compact('upcomingGames', 'activities', 'teams'));
    }
} 