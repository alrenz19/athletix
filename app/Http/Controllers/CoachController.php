<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sport;
use App\Models\Athlete;
use App\Models\Attendance;
use App\Models\Performance;
use App\Models\EventRegistration;
use App\Models\AthleteTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AthleteNotification;
use App\Models\Coach;

class CoachController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get events based on user role
        if ($user->role === 'coach') {
            $coach = Coach::where('user_id', $user->id)->first();
            
            if (!$coach) {
                return view('coach.events.index', ['events' => collect()]);
            }
            
            // Get TryOut events for the coach's sport only
            $events = Event::with('sport')
                ->where('removed', 0)
                ->where('event_type', 'TryOut')
                ->where('sport_id', $coach->sport_id)
                ->get();
        } else {
            // For superAdmin, show all TryOut events
            $events = Event::with('sport')
                ->where('removed', 0)
                ->where('event_type', 'TryOut')
                ->get();
        }
        
        return view('coach.events.index', compact('events'));
    }

    public function create()
    {
        $user = auth()->user();
        
        if ($user->role === 'coach') {
            $coach = $user->coach;
            // Coaches can only create events for their sport
            $sports = Sport::where('removed', 0)
                ->where('sport_id', $coach->sport_id)
                ->get();
        } else {
            // superAdmin can create events for any sport
            $sports = Sport::where('removed', 0)->get();
        }
        
        return view('coach.events.create', compact('sports'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'coach') {
            // Validate that coach is creating event for their sport
            $coach = $user->coach;
            if ($coach && $request->sport_id != $coach->sport_id) {
                return back()->withErrors(['sport_id' => 'You can only create events for your assigned sport.']);
            }
        }
        
        $event = Event::create($request->all());
        return redirect()->route('coach.events.index')->with('success', 'Event created successfully.');
    }

    public function attendance(Event $event)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized access to event from another sport.');
            }
        }
        
        $athletes = Athlete::whereHas('eventRegistrations', function($q) use ($event) {
            $q->where('event_id', $event->event_id)->where('status', 'approved');
        })->get();

        return view('coach.events.attendance', compact('event', 'athletes'));
    }

    public function updateAttendance(Request $request, Event $event)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        foreach ($request->attendance as $athleteId => $status) {
            Attendance::updateOrCreate(
                ['event_id' => $event->event_id, 'athlete_id' => $athleteId],
                ['status' => $status]
            );
        }
        return back()->with('success', 'Attendance updated.');
    }

    public function performance(Event $event)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized access to event from another sport.');
            }
        }
        
        $athletes = Athlete::whereHas('eventRegistrations', function($q) use ($event) {
            $q->where('event_id', $event->event_id)->where('status', 'approved');
        })->get();

        return view('coach.events.performance', compact('event', 'athletes'));
    }

    public function updatePerformance(Request $request, Event $event)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        foreach ($request->performance as $athleteId => $data) {
            Performance::updateOrCreate(
                ['event_id' => $event->event_id, 'athlete_id' => $athleteId],
                ['score' => $data['score'], 'remarks' => $data['remarks']]
            );
        }
        return back()->with('success', 'Performance updated.');
    }

    public function registrations(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'coach') {
            $coach = $user->coach;
            
            // If no coach record is found, return empty
            if (!$coach) {
                return view('coach.events.registrations', [
                    'registrations' => collect(),
                    'sports' => collect(),
                    'heading' => 'No Sports Assigned'
                ]);
            }
            
            // Get the coach's sport only
            $sports = Sport::where('removed', 0)
                ->where('sport_id', $coach->sport_id)
                ->get();
            
            $heading = "All Sports Events";
            
            // Base query: only registrations under coach's sport
            $query = EventRegistration::with(['athlete', 'event.sport'])
                ->whereHas('event', function ($q) use ($coach) {
                    $q->where('sport_id', $coach->sport_id);
                });
        } else {
            // For superAdmin, show all sports
            $sports = Sport::where('removed', 0)->get();
            $heading = "All Sports Events";
            
            // Base query: all registrations
            $query = EventRegistration::with(['athlete', 'event.sport']);
        }
        
        // Apply sport filter if selected
        if ($request->filled('sport') && $request->sport !== 'all') {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('sport_id', $request->sport);
            });
            
            $sportName = Sport::find($request->sport)?->sport_name;
            $heading = $sportName ? $sportName . " Events" : $heading;
        }
        
        $registrations = $query->get();
        
        return view('coach.events.registrations', compact('registrations', 'sports', 'heading'));
    }

    public function approve(Event $event, Athlete $athlete)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
            
            // Additional check: athlete must be from coach's sport
            if ($athlete->sport_id !== $coach->sport_id) {
                abort(403, 'Cannot approve athlete from another sport.');
            }
        }
        
        EventRegistration::where('event_id', $event->event_id)
            ->where('athlete_id', $athlete->athlete_id)
            ->update(['status' => 'approved']);
        
        // Check if athlete is already in this team
        $existingTeam = AthleteTeam::where('athlete_id', $athlete->athlete_id)
            ->where('team_id', $event->sport_id)
            ->first();
            
        if (!$existingTeam) {
            AthleteTeam::create([
                'athlete_id' => $athlete->athlete_id,
                'team_id' => $event->sport_id
            ]);
        }
        
        // Send notification/email
        $email = filter_var($athlete->user->username, FILTER_VALIDATE_EMAIL)
            ? $athlete->user->username
            : 'default@yourdomain.com';
       // Mail::to($email)->send(new AthleteNotification($athlete, 'approved'));
        Mail::to($email)->send(new AthleteNotification(
            'Athlete Registration Status', // Title
            'approved' // Message content
        ));
        
        return back()->with('success', 'Athlete approved' . ($existingTeam ? '' : ' and added to team.'));
    }

    public function reject(Event $event, Athlete $athlete)
    {
        $user = auth()->user();
        
        // Authorization check for coaches
        if ($user->role === 'coach') {
            $coach = $user->coach;
            if ($coach && $coach->sport_id && $event->sport_id !== $coach->sport_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        EventRegistration::where('event_id', $event->event_id)
            ->where('athlete_id', $athlete->athlete_id)
            ->update(['status' => 'rejected']);
        
        $email = filter_var($athlete->user->username, FILTER_VALIDATE_EMAIL)
            ? $athlete->user->username
            : 'default@yourdomain.com';
        
        //Mail::to($email)->send(new AthleteNotification($athlete, 'rejected'));
        Mail::to($email)->send(new AthleteNotification(
            'Athlete Registration Status', // Title
            'rejected' // Message content
        ));        
        return back()->with('success', 'Athlete rejected.');
    }
}