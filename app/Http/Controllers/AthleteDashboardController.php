<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AthleteDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        $athleteId = $user->id;
        $role = $user->role; // assuming you have a "role" column in your users table

        // ✅ Registration status
        $registration = EventRegistration::where('athlete_id', $athleteId)
            ->latest()
            ->first();

        // ✅ Upcoming events (next 5)
        $upcomingEvents = Event::where('removed', 0)
            ->whereDate('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // ✅ Announcements filtered by target role or All
        $announcements = Announcement::with(['poster', 'sport', 'section'])
            ->where('removed', 0)
            ->where(function($query) use ($role) {
                $query->where('target', $role)
                    ->orWhere('target', 'All');
            })
            ->latest()
            ->get();

        return view('athlete.dashboard', compact('registration', 'upcomingEvents', 'announcements'));
    }

}
