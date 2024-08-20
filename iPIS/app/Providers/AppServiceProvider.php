<?php

namespace App\Providers;

use App\Models\Team;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.sidebar', function ($view) {
            $coachId = Auth::check() ? Auth::user()->id : null;
            $teams = Team::where('coach_id', $coachId)->get();
            $view->with('teams', $teams);
        });
    }
}
