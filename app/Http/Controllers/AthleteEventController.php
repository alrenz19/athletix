<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Athlete;
use App\Models\EventRegistration;
use App\Models\AthleteEvent;
use App\Models\Sport;
use Illuminate\Support\Facades\DB;

class AthleteEventController extends Controller
{
    // Show all eligible upcoming events
    public function index(Request $request)
    {
        $athleteId = Auth::id(); // Logged in user id

        $search = $request->input('search');
        $sportId = $request->input('sport');

        $query = Event::with(['sport', 'athleteEvents'])
            ->where('removed', 0)
            ->whereDate('event_date', '>=', now());

        if ($search) {
            $query->where('event_name', 'like', '%' . $search . '%');
        }

        if ($sportId) {
            $query->where('sport_id', $sportId);
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(10);

        $sports = Sport::all();

        return view('athlete.events.index', compact('events', 'sports', 'search', 'sportId', 'athleteId'));
    }



    // Show participation history
    public function history(Request $request)
    {
        $userId = Auth::id();
        $athlete = Athlete::where('user_id', $userId)->firstOrFail();
        $athleteId = $athlete->athlete_id;

        $search = $request->input('search');
        $sportId = $request->input('sport');

        $query = AthleteEvent::with(['event.sport'])
            ->where('athlete_id', $athleteId);

        if ($search) {
            $query->whereHas('event', function ($eventQuery) use ($search) {
                $eventQuery->where('event_name', 'like', "%{$search}%");
            });
        }

        if ($sportId) {
            $query->whereHas('event', function ($eventQuery) use ($sportId) {
                $eventQuery->where('sport_id', $sportId);
            });
        }

        $participations = $query->orderByDesc('created_at')->paginate(10);

        // Get all sports for dropdown
        $sports = Sport::all();

        return view('athlete.events.history', compact('participations', 'sports', 'search', 'sportId'));
    }

      public function register($eventId)
    {
        $userId = Auth::id();
        $athlete = Athlete::where('user_id', $userId)->firstOrFail();
        $athleteId = $athlete->athlete_id;

        $event = Event::findOrFail($eventId);

        // Ensure athlete_event exists
        AthleteEvent::firstOrCreate([
            'athlete_id' => $athleteId,
            'event_id'   => $eventId,
        ]);

        // If TryOut → also add in event_registrations
        if (strtolower($event->event_type) === 'tryout') {
            EventRegistration::firstOrCreate([
                'athlete_id' => $athleteId,
                'event_id'   => $eventId,
            ]);
        }

        return back()->with('success', 'Registered successfully.');
    }

    public function unregister($eventId)
    {
        $userId = Auth::id();
        $athlete = Athlete::where('user_id', $userId)->firstOrFail();
        $athleteId = $athlete->athlete_id;

        $event = Event::findOrFail($eventId);

        AthleteEvent::where('athlete_id', $athleteId)
            ->where('event_id', $eventId)
            ->delete();

        if (strtolower($event->event_type) === 'tryout') {
            EventRegistration::where('athlete_id', $athleteId)
                ->where('event_id', $eventId)
                ->delete();
        }

        return back()->with('success', 'Unregistered successfully.');
    }
}
