<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Sport;

class CoachProfileController extends Controller
{
    // Show edit form
    public function edit()
    {
        $user = Auth::user();
        $coach = $user->coach; // User hasOne Coach
        $sports = Sport::where('removed', 0)->get();

        return view('coach.profile.edit', compact('user', 'coach', 'sports'));
    }

    // Update profile
    public function update(Request $request)
    {
        $user = Auth::user();
        $coach = $user->coach;

        $validated = $request->validate([
            // Account Information
            'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6|confirmed',
            
            // Personal Information
            'full_name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:20|max:70',
            'gender' => 'nullable|in:Male,Female,Other',
            
            // Sports & Employment
            'sport_id' => 'nullable|exists:sports,sport_id',
            'specialization' => 'nullable|string|max:255',
            'current_position_title' => 'nullable|string|max:255',
            'sports_program_position' => 'nullable|string|max:255',
            'employment_status' => 'nullable|in:Permanent,Contractual,Part-time,Volunteer',
            'monthly_salary' => 'nullable|numeric|min:0',
            'years_experience' => 'nullable|integer|min:0|max:50',
            
            // Athletic Background
            'was_previous_athlete' => 'nullable|boolean',
            'highest_competition_level' => 'nullable|in:Intramurals,University,Local,Regional,National,International',
            'highest_accomplishment_athlete' => 'nullable|string',
            'international_competition_athlete' => 'nullable|string|max:255',
            
            // Coaching Accomplishments
            'highest_accomplishment_coach' => 'nullable|string',
            'international_competition_coach' => 'nullable|string|max:255',
            
            // Professional Memberships
            'regional_membership' => 'nullable|boolean',
            'national_membership' => 'nullable|boolean',
            'international_membership' => 'nullable|boolean',
            'international_membership_name' => 'nullable|string|max:255',
            
            // Educational Background
            'highest_degree' => 'nullable|in:High School,Bachelor,Master,Doctorate',
            'bachelor_degree' => 'nullable|string|max:255',
            'master_degree' => 'nullable|string|max:255',
            'doctorate_degree' => 'nullable|string|max:255',
        ]);

        // Update user credentials
        $user->username = $validated['username'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // Prepare coach data
        $coachData = [
            'full_name' => $validated['full_name'],
            'age' => $validated['age'],
            'gender' => $validated['gender'],
            'specialization' => $validated['specialization'],
            'sport_id' => $validated['sport_id'],
            'current_position_title' => $validated['current_position_title'],
            'sports_program_position' => $validated['sports_program_position'],
            'employment_status' => $validated['employment_status'],
            'monthly_salary' => $validated['monthly_salary'],
            'years_experience' => $validated['years_experience'],
            'was_previous_athlete' => $validated['was_previous_athlete'] ?? false,
            'highest_competition_level' => $validated['highest_competition_level'],
            'highest_accomplishment_athlete' => $validated['highest_accomplishment_athlete'],
            'international_competition_athlete' => $validated['international_competition_athlete'],
            'highest_accomplishment_coach' => $validated['highest_accomplishment_coach'],
            'international_competition_coach' => $validated['international_competition_coach'],
            'regional_membership' => $validated['regional_membership'] ?? false,
            'national_membership' => $validated['national_membership'] ?? false,
            'international_membership' => $validated['international_membership'] ?? false,
            'international_membership_name' => $validated['international_membership_name'],
            'highest_degree' => $validated['highest_degree'],
            'bachelor_degree' => $validated['bachelor_degree'],
            'master_degree' => $validated['master_degree'],
            'doctorate_degree' => $validated['doctorate_degree'],
        ];

        // Update or create coach profile
        \App\Models\Coach::updateOrCreate(
            ['user_id' => $user->user_id],
            $coachData
        );

        return redirect()->route('coach.profile.edit')->with('success', 'Profile updated successfully.');
    }
}