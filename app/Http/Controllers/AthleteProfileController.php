<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Athlete;
use App\Models\Section;
use App\Models\Sport;

class AthleteProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $athlete = $user->athlete; // relationship: user hasOne athlete
        $sections = Section::with('course')->where('removed', 0)->get();
        $sports = Sport::where('removed', 0)->get();

        return view('athlete.profile.edit', compact('user', 'athlete', 'sections', 'sports'));
    }

   public function update(Request $request)
{
    $user = Auth::user();
    $athlete = $user->athlete;

    $validated = $request->validate([
        // Account Information
        'username' => 'required|string|max:255|unique:users,username,' . $user->user_id . ',user_id',
        'password' => 'nullable|string|min:6|confirmed',

        // Personal Information
        'full_name' => 'required|string|max:255',
        'birthdate' => 'nullable|date',
        'age' => 'nullable|integer|min:15|max:40',
        'gender' => 'nullable|in:Male,Female,Other',
        'school_id' => 'required|string|max:255',
        'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

        // Academic Information
        'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,Alumni',
        'academic_course' => 'nullable|string|max:255',
        'section_id' => 'required|exists:sections,section_id',

        // Sports Information
        'sport_id' => 'required|exists:sports,sport_id',
        'highest_competition_level' => 'nullable|in:Intramurals,University,Local,Regional,National,International',
        'highest_accomplishment' => 'nullable|string',
        'international_competition_name' => 'nullable|string|max:255',

        // Training Information
        'training_seminars_regional' => 'nullable|boolean',
        'training_seminars_national' => 'nullable|boolean',
        'training_seminars_international' => 'nullable|boolean',
        'training_frequency_days' => 'nullable|integer|min:1|max:7',
        'training_hours_per_day' => 'nullable|numeric|min:0.5|max:8',

        // Scholarship & Benefits
        'scholarship_status' => 'nullable|in:Full Scholarship,Partial Scholarship,Non-scholar',
        'monthly_living_allowance' => 'nullable|numeric|min:0',
        'board_lodging_support' => 'nullable|boolean',
        'medical_insurance_support' => 'nullable|boolean',
        'training_uniforms_support' => 'nullable|boolean',
        'average_tournament_allowance' => 'nullable|numeric|min:0',
        'playing_uniforms_sponsorship' => 'nullable|boolean',
        'playing_gears_sponsorship' => 'nullable|boolean',

        // Academic Support
        'excused_from_academic_obligations' => 'nullable|boolean',
        'flexible_academic_schedule' => 'nullable|boolean',
        'academic_tutorials_support' => 'nullable|boolean',
    ]);

    // ✅ Update user info
    $user->username = $validated['username'];
    if (!empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }
    $user->save();

    // ✅ Prepare athlete data
    $athleteData = [
        'full_name' => $validated['full_name'],
        'birthdate' => $validated['birthdate'] ?? null,
        'age' => $validated['age'] ?? null,
        'gender' => $validated['gender'] ?? null,
        'school_id' => $validated['school_id'],
        'year_level' => $validated['year_level'],
        'academic_course' => $validated['academic_course'] ?? null,
        'section_id' => $validated['section_id'],
        'sport_id' => $validated['sport_id'],
        'highest_competition_level' => $validated['highest_competition_level'] ?? null,
        'highest_accomplishment' => $validated['highest_accomplishment'] ?? null,
        'international_competition_name' => $validated['international_competition_name'] ?? null,
        'training_seminars_regional' => $validated['training_seminars_regional'] ?? false,
        'training_seminars_national' => $validated['training_seminars_national'] ?? false,
        'training_seminars_international' => $validated['training_seminars_international'] ?? false,
        'training_frequency_days' => $validated['training_frequency_days'] ?? null,
        'training_hours_per_day' => $validated['training_hours_per_day'] ?? null,
        'scholarship_status' => $validated['scholarship_status'] ?? 'Non-scholar',
        'monthly_living_allowance' => $validated['monthly_living_allowance'] ?? 0,
        'board_lodging_support' => $validated['board_lodging_support'] ?? false,
        'medical_insurance_support' => $validated['medical_insurance_support'] ?? false,
        'training_uniforms_support' => $validated['training_uniforms_support'] ?? false,
        'average_tournament_allowance' => $validated['average_tournament_allowance'] ?? 0,
        'playing_uniforms_sponsorship' => $validated['playing_uniforms_sponsorship'] ?? false,
        'playing_gears_sponsorship' => $validated['playing_gears_sponsorship'] ?? false,
        'excused_from_academic_obligations' => $validated['excused_from_academic_obligations'] ?? false,
        'flexible_academic_schedule' => $validated['flexible_academic_schedule'] ?? false,
        'academic_tutorials_support' => $validated['academic_tutorials_support'] ?? false,
    ];

    // ✅ Handle image upload and replacement
    if ($request->hasFile('profile_image')) {
        // Delete old file if it exists and is local
        if ($athlete && $athlete->getRawOriginal('profile_url') && \Storage::disk('public')->exists($athlete->getRawOriginal('profile_url'))) {
            \Storage::disk('public')->delete($athlete->getRawOriginal('profile_url'));
        }

        // Store new image
        $path = $request->file('profile_image')->store('athletes', 'public');
        $athleteData['profile_url'] = $path;
    }

    // ✅ Update or create athlete profile
    Athlete::updateOrCreate(
        ['user_id' => $user->user_id],
        $athleteData
    );

    return redirect()->route('athlete.profile.edit')->with('success', 'Profile updated successfully.');
}

}