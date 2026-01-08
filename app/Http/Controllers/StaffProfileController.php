<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $staff = $user->staff;

        return view('staff.profile.edit', compact('user', 'staff'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $staff = $user->staff;

        $request->validate([
            'username'   => 'required|string|max:100|unique:users,username,' . $user->user_id . ',user_id',
            'password'   => 'nullable|confirmed|min:6',
            'full_name'  => 'required|string|max:255',
            'position'   => 'nullable|string|max:255',
        ]);

        // ✅ Update users table
        $user->username = $request->username;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // ✅ Update staff table
        $staff->full_name = $request->full_name;
        $staff->position  = $request->position;
        $staff->save();

        return redirect()->route('staff.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
