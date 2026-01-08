<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Performance;
use Illuminate\Http\Request;

class CoachAttendancePerformanceController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user()->coach;

        // All events under this coach for dropdown
        $allEvents = Event::where('sport_id', $coach->sport_id)->get();

        // Events to display in table: only events with at least one athlete
        $eventsQuery = Event::where('sport_id', $coach->sport_id)
                            ->whereHas('athletes'); // only events with >= 1 athlete

        // Apply filter if selected
        if ($request->event_id) {
            $eventsQuery->where('event_id', $request->event_id);
        }

        $events = $eventsQuery->get();

        // Athletes under this coach's sport
        $athletesQuery = Athlete::where('sport_id', $coach->sport_id)
                                ->with(['attendances' => fn($q) => $q->where('removed', 0),
                                        'performances' => fn($q) => $q->where('removed', 0),
                                        'events']);

        if ($request->search) {
            $athletesQuery->where('full_name', 'like', '%' . $request->search . '%');
        }

        $athletes = $athletesQuery->paginate(10)->withQueryString();

        return view('coach.attendance.index', compact('athletes', 'events', 'allEvents'))
            ->with('selectedEvent', $request->event_id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,athlete_id',
            'event_id' => 'required|exists:events,event_id',
            'status' => 'required|in:Present,Absent,Late,Excused',
            'score' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        // Save Attendance
        Attendance::updateOrCreate(
            [
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->event_id,
            ],
            [
                'status' => $request->status,
            ]
        );

        // Save Performance
        Performance::updateOrCreate(
            [
                'athlete_id' => $request->athlete_id,
                'event_id' => $request->event_id,
            ],
            [
                'score' => $request->score,
                'remarks' => $request->remarks,
            ]
        );

        return back()->with('success', 'Attendance and Performance saved.');
    }

}

