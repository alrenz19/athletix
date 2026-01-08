<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Award;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Coach;

class AwardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'Coach') {
            // Get the coach record to access sport_id
            $coach = Coach::where('user_id', $user->user_id)->first();
            
            if (!$coach || !$coach->sport_id) {
                return redirect()->back()->with('error', 'Coach sport information not found.');
            }

            // Get awards for athletes in the coach's sport
            $awards = Award::with(['athlete', 'event'])
                ->whereHas('athlete', function ($query) use ($coach) {
                    $query->where('sport_id', $coach->sport_id);
                })
                ->orderBy('created_at', 'desc')
                ->get();

        } else {
            // For other roles (Admin, SuperAdmin), show all awards
            $awards = Award::with(['athlete', 'event'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('coach.awards.index', compact('awards'));
    }

    public function create()
    {
        $user = Auth::user();
        
        if ($user->role === 'Coach') {
            $coach = Coach::where('user_id', $user->user_id)->first();
            
            if (!$coach || !$coach->sport_id) {
                return redirect()->back()->with('error', 'Coach sport information not found.');
            }

            $athletes = Athlete::where('sport_id', $coach->sport_id)
                ->where('status', 'approved') // Only show approved athletes
                ->get();
                
            $events = Event::where('sport_id', $coach->sport_id)->get();
        } else {
            // For admins, show all athletes and events
            $athletes = Athlete::where('status', 'approved')->get();
            $events = Event::all();
        }
        
        return view('coach.awards.create', compact('athletes', 'events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,athlete_id',
            'event_id' => 'required|exists:events,event_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Verify the athlete belongs to coach's sport if user is coach
        $user = Auth::user();
        if ($user->role === 'Coach') {
            $coach = Coach::where('user_id', $user->user_id)->first();
            $athlete = Athlete::find($request->athlete_id);
            
            if ($athlete->sport_id !== $coach->sport_id) {
                return redirect()->back()->with('error', 'Invalid athlete selection.');
            }
        }

        Award::create($request->all());

        return redirect()->route('coach.awards.index')->with('success', 'Award created successfully.');
    }

    public function edit(Award $award)
    {
        $user = Auth::user();
        
        if ($user->role === 'Coach') {
            $coach = Coach::where('user_id', $user->user_id)->first();
            $athlete = Athlete::find($award->athlete_id);
            
            if ($athlete->sport_id !== $coach->sport_id) {
                return redirect()->back()->with('error', 'Unauthorized access to this award.');
            }

            $athletes = Athlete::where('sport_id', $coach->sport_id)
                ->where('status', 'approved')
                ->get();
            $events = Event::where('sport_id', $coach->sport_id)->get();
        } else {
            $athletes = Athlete::where('status', 'approved')->get();
            $events = Event::all();
        }

        return view('coach.awards.edit', compact('award', 'athletes', 'events'));
    }

    public function update(Request $request, Award $award)
    {
        $request->validate([
            'athlete_id' => 'required|exists:athletes,athlete_id',
            'event_id' => 'required|exists:events,event_id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Verify authorization for coach
        $user = Auth::user();
        if ($user->role === 'Coach') {
            $coach = Coach::where('user_id', $user->user_id)->first();
            $athlete = Athlete::find($request->athlete_id);
            
            if ($athlete->sport_id !== $coach->sport_id) {
                return redirect()->back()->with('error', 'Invalid athlete selection.');
            }
        }

        $award->update($request->all());

        return redirect()->route('coach.awards.index')->with('success', 'Award updated successfully.');
    }

    public function destroy(Award $award)
    {
        // Verify authorization for coach
        $user = Auth::user();
        if ($user->role === 'Coach') {
            $coach = Coach::where('user_id', $user->user_id)->first();
            $athlete = Athlete::find($award->athlete_id);
            
            if ($athlete->sport_id !== $coach->sport_id) {
                return redirect()->back()->with('error', 'Unauthorized to delete this award.');
            }
        }

        $award->delete();
        return redirect()->route('coach.awards.index')->with('success', 'Award deleted successfully.');
    }
}