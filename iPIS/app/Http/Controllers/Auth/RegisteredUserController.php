<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Helpers\ActivityLogHelper;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info($request->all());   
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'string'],
            'school_name' => ['required', 'string'],
            'role' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'school_name' => $request->school_name,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => false,
        ]);
        // Fetch the school name from the user model
        $schoolName = $user->school_name;

        // Define the path for the school folder
        $schoolFolderPath = "public/{$schoolName}";

        // Check if the folder already exists
        if (!Storage::exists($schoolFolderPath)) {
            // Create the folder
            Storage::makeDirectory($schoolFolderPath);
        }

       // After user is successfully created
        ActivityLogHelper::logActivity($user, 'user_registered', 'registered a new user');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
