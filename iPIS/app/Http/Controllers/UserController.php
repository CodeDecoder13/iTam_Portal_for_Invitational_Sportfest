<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function myDocuments_sub($type)
    {
        return view('user-sidebar.my-documents.sub',compact('type'));
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
