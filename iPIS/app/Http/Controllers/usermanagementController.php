<?php
// app/Http/Controllers/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;

class usermanagementController extends Controller
{
    public function index()
    {
        // Fetch all users and admins
        $users = User::all();
        $admins = Admin::all();

        // Combine the collections
        $allUsers = $users->concat($admins);

        // Debugging: Check if data is fetched correctly
        // dd($allUsers);

        return view('user-management', ['allUsers' => $allUsers]);
    }
    // Show the edit form for a specific user
    public function edit($id)
    {
        $user = User::find($id);
        return view('edit-user', compact('user'));
    }

    // Update the user details
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->update($request->all());
        return redirect()->route('user-management');
    }

    // Activate a user
    public function activate($id)
    {
        $user = User::find($id);
        $user->status = 'active';
        $user->save();
        return redirect()->route('user-management');
    }

    // Deactivate a user
    public function deactivate($id)
    {
        $user = User::find($id);
        $user->status = 'deactivated';
        $user->save();
        return redirect()->route('user-management');
    }
}