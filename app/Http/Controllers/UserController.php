<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Athlete;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function controlPanel()
    {
        // Fetch users with athlete relation, only non-removed
        $users = User::with('athlete')
            ->where('removed', 0)
            ->get();

        return view('controlPanel', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username'   => 'required|string|unique:users,username',
            'password'   => 'required|string|min:6',
            'full_name'  => 'required|string',
            'role'       => 'required|in:SuperAdmin,Admin,Coach,Staff,Athlete',
            'section_id' => 'nullable|integer', // for athletes
            'sport_id'   => 'nullable|integer', // for athletes
        ]);

        // Create new user
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'removed'  => 0,
        ]);

        // If role is Athlete, create athlete record
        if ($request->role === 'Athlete') {
            Athlete::create([
                'user_id'    => $user->user_id,
                'full_name'  => $request->full_name,
                'section_id' => $request->section_id,
                'sport_id'   => $request->sport_id,
                'year_level' => '1st Year', // default
                'removed'    => 0,
            ]);
        }

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username'   => 'required|string|max:100|unique:users,username,' . $id . ',user_id',
            'full_name'  => 'required|string',
            'role'       => 'required|in:SuperAdmin,Admin,Coach,Staff,Athlete',
            'section_id' => 'nullable|integer', // for athletes
            'sport_id'   => 'nullable|integer', // for athletes
        ]);

        $user = User::with('athlete')->findOrFail($id);

        $user->update([
            'username' => $request->username,
            'role'     => $request->role,
        ]);

        // Update athlete record if exists
        if ($user->athlete) {
            $user->athlete->update([
                'full_name'  => $request->full_name,
                'section_id' => $request->section_id,
                'sport_id'   => $request->sport_id,
            ]);
        }

        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::with('athlete')->findOrFail($id);

        // Mark user as removed
        $user->update(['removed' => 1]);

        // Mark athlete as removed if exists
        if ($user->athlete) {
            $user->athlete->update(['removed' => 1]);
        }

        return redirect()->back()->with('success', 'User has been deactivated successfully.');
    }

    public function create()
    {
        return view('users.create'); // form to add user
    }

    public function edit($id)
    {
        $user = User::with('athlete')->findOrFail($id);
        return view('users.edit', compact('user'));
    }

}
