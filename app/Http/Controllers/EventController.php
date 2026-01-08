<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\Sport;

class EventController extends Controller
{
    // Display events
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'Coach') {
            $coach = $user->coach;

            $events = Event::where('removed', 0)
                        ->where('sport_id', $coach->sport_id)
                        ->with('sport')
                        ->get();

            $sports = Sport::where('removed', 0)
                        ->where('sport_id', $coach->sport_id)
                        ->get();
        } else {
            // SuperAdmin & Staff see all
            $events = Event::where('removed', 0)->with('sport')->get();
            $sports = Sport::where('removed', 0)->get();
        }

        return view('events', compact('events', 'sports'));
    }

    // Store new event
    public function storeEvent(Request $request)
    {
        $user = Auth::user();

        if ($user->role == 'Coach') {
            $coach = $user->coach;

            $request->validate([
                'sport_id'   => 'required|in:' . $coach->sport_id,
                'event_name' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255', // <-- Location validation
            ]);
        } else {
            $request->validate([
                'sport_id'   => 'required|exists:sports,sport_id',
                'event_name' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255', // <-- Location validation
            ]);
        }

        Event::create($request->only([
            'sport_id', 'event_name', 'event_date', 'event_type', 'location'
        ]));

        return redirect()->back()->with('success', 'Event added successfully.');
    }

    // Update event
    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        if ($user->role == 'Coach') {
            $coach = $user->coach;

            $request->validate([
                'sport_id'   => 'required|in:' . $coach->sport_id,
                'event_name' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255',
            ]);
        } else {
            $request->validate([
                'sport_id'   => 'required|exists:sports,sport_id',
                'event_name' => 'required|string|max:255',
                'event_date' => 'required|date',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255',
            ]);
        }

        // Explicitly assign fields to prevent silent failures
        $event->sport_id   = $request->sport_id;
        $event->event_name = $request->event_name;
        $event->event_date = $request->event_date;
        $event->event_type = $request->event_type;
        $event->location   = $request->location;

        $event->save(); // <- save explicitly

        return redirect()->back()->with('success', 'Event updated successfully.');
    }



    // Soft delete
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);

        // Coaches cannot delete events outside their sport
        $user = Auth::user();
        if ($user->role === 'Coach' && $event->sport_id != $user->coach->sport_id) {
            return redirect()->back()->with('error', 'You cannot delete events outside your sport.');
        }

        $event->update(['removed' => 1]);

        return redirect()->back()->with('success', 'Event removed successfully.');
    }
}
