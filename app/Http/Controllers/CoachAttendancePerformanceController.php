<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Performance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CoachAttendancePerformanceController extends Controller
{
    public function index(Request $request)
    {
        $coach = auth()->user()->coach;

        // All events under this coach for dropdown
        $allEvents = Event::where('sport_id', $coach->sport_id)
                         ->where('removed', 0)
                         ->orderBy('event_date', 'desc')
                         ->get();

        // Get event IDs for filtering
        $eventIds = $allEvents->pluck('event_id')->toArray();

        // Events to display in table with filtering
        $eventsQuery = Event::where('sport_id', $coach->sport_id)
                           ->where('removed', 0)
                           ->whereHas('athletes')
                           ->with(['athletes', 'attendances', 'performances']);

        // Apply filters
        if ($request->filled('event_id')) {
            $eventsQuery->where('events.event_id', $request->event_id); // Specify table
        }
        
        if ($request->filled('date')) {
            $eventsQuery->whereDate('event_date', $request->date);
        }

        $events = $eventsQuery->orderBy('event_date', 'desc')->get();

        // Athletes under this coach's sport
        $athletesQuery = Athlete::where('sport_id', $coach->sport_id)
                               ->where('removed', 0)
                               ->with(['attendances' => function($q) use ($eventIds) {
                                   $q->where('removed', 0)
                                     ->whereIn('event_id', $eventIds);
                               },
                               'performances' => function($q) use ($eventIds) {
                                   $q->where('removed', 0)
                                     ->whereIn('event_id', $eventIds);
                               },
                               'events' => function($q) use ($eventIds) {
                                   $q->whereIn('events.event_id', $eventIds); // Specify table
                               }]);

        if ($request->filled('search')) {
            $athletesQuery->where('full_name', 'like', '%' . $request->search . '%');
        }

        // Filter athletes by status if requested
        if ($request->filled('status') && $request->filled('event_id')) {
            $athletesQuery->whereHas('attendances', function($q) use ($request) {
                $q->where('attendances.event_id', $request->event_id) // Specify table
                  ->where('status', $request->status);
            });
        }

        $athletes = $athletesQuery->paginate(20)->withQueryString();

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
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            // Check if coach has permission for this athlete and event
            $coach = auth()->user()->coach;
            $athlete = Athlete::findOrFail($request->athlete_id);
            $event = Event::findOrFail($request->event_id);
            
            if ($athlete->sport_id !== $coach->sport_id || $event->sport_id !== $coach->sport_id) {
                throw new \Exception('You do not have permission to update this record.');
            }

            // Check if event is older than one week
            $eventDate = Carbon::parse($event->event_date);
            $oneWeekAgo = Carbon::now()->subWeek();
            if ($eventDate < $oneWeekAgo) {
                throw new \Exception('Cannot edit attendance for events older than one week.');
            }

            // Save Attendance
            Attendance::updateOrCreate(
                [
                    'athlete_id' => $request->athlete_id,
                    'event_id' => $request->event_id,
                ],
                [
                    'status' => $request->status,
                    'removed' => 0
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
                    'removed' => 0
                ]
            );

            // Return JSON for AJAX requests
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Attendance and performance saved successfully!'
                ]);
            }

            return back()->with('success', 'Attendance and Performance saved.');
            
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'changes' => 'required|array',
            'changes.*.athlete_id' => 'required|exists:athletes,athlete_id',
            'changes.*.event_id' => 'required|exists:events,event_id',
            'changes.*.status' => 'required|in:Present,Absent,Late,Excused',
            'changes.*.score' => 'nullable|numeric|min:0|max:100',
            'changes.*.remarks' => 'nullable|string|max:500',
        ]);
        
        try {
            $coach = auth()->user()->coach;
            $successCount = 0;
            $errorMessages = [];
            
            foreach ($validated['changes'] as $change) {
                try {
                    // Check if coach has permission for this athlete and event
                    $athlete = Athlete::findOrFail($change['athlete_id']);
                    $event = Event::findOrFail($change['event_id']);
                    
                    if ($athlete->sport_id !== $coach->sport_id || $event->sport_id !== $coach->sport_id) {
                        $errorMessages[] = "No permission for athlete {$athlete->full_name} in event {$event->event_name}";
                        continue;
                    }

                    // Check if event is older than one week
                    $eventDate = Carbon::parse($event->event_date);
                    $oneWeekAgo = Carbon::now()->subWeek();
                    if ($eventDate < $oneWeekAgo) {
                        $errorMessages[] = "Event {$event->event_name} is older than one week";
                        continue;
                    }

                    // Update Attendance
                    Attendance::updateOrCreate(
                        [
                            'athlete_id' => $change['athlete_id'],
                            'event_id' => $change['event_id'],
                        ],
                        [
                            'status' => $change['status'],
                            'removed' => 0
                        ]
                    );
                    
                    // Update Performance
                    Performance::updateOrCreate(
                        [
                            'athlete_id' => $change['athlete_id'],
                            'event_id' => $change['event_id'],
                        ],
                        [
                            'score' => $change['score'] ?? null,
                            'remarks' => $change['remarks'] ?? null,
                            'removed' => 0
                        ]
                    );
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errorMessages[] = $e->getMessage();
                }
            }
            
            if ($successCount > 0) {
                $message = "Successfully updated {$successCount} record(s).";
                if (!empty($errorMessages)) {
                    $message .= " Failed: " . implode(', ', array_slice($errorMessages, 0, 3));
                    if (count($errorMessages) > 3) {
                        $message .= " and " . (count($errorMessages) - 3) . " more";
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update any records: ' . implode(', ', $errorMessages)
                ], 400);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update records: ' . $e->getMessage()
            ], 500);
        }
    }
}