<?php

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
   

    public function dashboard()
    {
        // Your user dashboard logic
        return view('user.dashboard');
    }

    public function showLoginForm()
    {
        return view('auth.user-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'user';

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/user/dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials or role']);
    }
}
