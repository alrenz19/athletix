<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\TrainingNote;
use App\Models\Event;
use App\Models\Coach;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoachAthleteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Start with base query
        $query = Athlete::with('sport')->where('status', 'approved');
        
        // If user is a coach (not superAdmin), filter by their sport
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id) {
                $query->where('sport_id', $coach->sport_id);
            }
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('year_level', 'like', "%{$search}%")
                  ->orWhereHas('sport', function($sportQuery) use ($search) {
                      $sportQuery->where('sport_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('conditions', $request->status);
        }
        
        // Apply year level filter
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }
        
        // Default sorting
        $query->orderBy('full_name');
        
        $athletes = $query->paginate(15);
        
        // Get statistics for quick overview
        $stats = [];
        if ($user->role === 'coach' && $coach && $coach->sport_id) {
            $stats = Athlete::where('sport_id', $coach->sport_id)
                ->selectRaw('conditions, COUNT(*) as count')
                ->groupBy('conditions')
                ->pluck('count', 'conditions')
                ->toArray();
        } elseif ($user->role === 'superAdmin') {
            $stats = Athlete::selectRaw('conditions, COUNT(*) as count')
                ->groupBy('conditions')
                ->pluck('count', 'conditions')
                ->toArray();
        }
        
        return view('coach.athletes.index', compact('athletes', 'stats'));
    }

public function show(Athlete $athlete)
{
    $user = auth()->user();
    
    // Authorization check for coaches
    if ($user->role === 'coach') {
        $coach = $user->coach;
        if ($coach && $coach->sport_id && $athlete->sport_id !== $coach->sport_id) {
            abort(403, 'Unauthorized access to athlete from another sport.');
        }
    }
    
    $athlete->load([
        'sport', 
        'teams', 
        'performances' => function($q) {
            $q->latest()->with('event'); // Load event with each performance
        },
        'attendances' => function($q) {
            $q->latest()->with('event'); // Load event with each attendance
        },
        'awards' => function($q) {
            $q->latest()->with('event'); // Load event with each award
        },
        'trainingNotes' => function($q) {
            $q->latest();
        }
    ]);

    // Get events based on user role
    if ($user->role === 'coach' && $user->coach && $user->coach->sport_id) {
        $events = Event::where('sport_id', $user->coach->sport_id)->get();
    } else {
        // For superAdmin, show events from the athlete's sport
        $events = Event::where('sport_id', $athlete->sport_id)->get();
    }

    return view('coach.athletes.show', compact('athlete', 'events'));
}

    public function storeNote(Request $request, Athlete $athlete)
    {
        $user = auth()->user();
        
        // Authorization check
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $athlete->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $request->validate(['note' => 'required|string']);

        // Smart coach selection logic
        $coachId = null;
        
        if ($user->role === 'coach') {
            // Coach is adding note for their own athlete
            $coachId = $user->coach->coach_id;
        } else {
            // superAdmin - find coach for this sport
            $coachForSport = Coach::where('sport_id', $athlete->sport_id)->first();
            $coachId = $coachForSport ? $coachForSport->coach_id : $user->coach->coach_id;
        }

        TrainingNote::create([
            'coach_id' => $coachId ? $coachId : 1,
            'athlete_id' => $athlete->athlete_id,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Training note added.');
    }

    public function assignEvent(Request $request, Athlete $athlete)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $athlete->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        $request->validate(['event_id' => 'required|exists:events,event_id']);
        
        // Additional validation: ensure event belongs to the same sport
        $event = Event::find($request->event_id);
        
        if ($user->role === 'coach' && $user->coach && $user->coach->sport_id) {
            // For coaches, verify event belongs to their sport
            if ($event->sport_id !== $user->coach->sport_id) {
                abort(403, 'Cannot assign athlete to event from another sport.');
            }
        } else {
            // For superAdmin, verify event belongs to athlete's sport
            if ($event->sport_id !== $athlete->sport_id) {
                return back()->withErrors(['event_id' => 'Event must be from the same sport as the athlete.']);
            }
        }

        $athlete->events()->attach($request->event_id);

        return back()->with('success', 'Athlete assigned to event.');
    }

        public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,injured,graduate'
        ]);
        
        $athlete = Athlete::findOrFail($id);
        
        // Authorization check - coach can only update athletes in their sport
        $user = auth()->user();
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $athlete->sport_id !== $coach->sport_id) {
                abort(403, 'You are not authorized to update this athlete.');
            }
        }
        
        $oldStatus = $athlete->conditions;
        $newStatus = $request->status;
        
        $athlete->update([
            'conditions' => $newStatus
        ]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Status Change',
            'module' => 'User Security',
            'description' => "Athlete: {$athlete->full_name} Status changed from {$oldStatus} to {$newStatus}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()->with('success', "Athlete status updated to {$newStatus}.");
    }
}