<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function documents() {
        return view('admin.admin-sidebar.documents');
    }

    public function calendar() {
        return view('admin.admin-sidebar.calendar');
    }

    public function playersTeams() {
        return view('admin.admin-sidebar.players-teams');
    }

    public function usersManagement() {
        return view('admin.admin-sidebar.user-management');
    }

    public function coachApproval() {
        return view('admin.admin-sidebar.coach-approval');
    }
}
