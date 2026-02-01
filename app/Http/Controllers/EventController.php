<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\Sport;
use App\Models\Team;
use App\Models\Athlete;
use App\Models\AthleteTeam;
use App\Models\Notification;
use App\Models\User;

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

        // Create the event
        $event = Event::create($request->only([
            'sport_id', 'event_name', 'event_date', 'event_type', 'location'
        ]));

        // Send notifications if event type is Training
        if ($event->event_type === 'Training') {
            $this->sendTrainingNotification($event, 'created');
        }

        return redirect()->back()->with('success', 'Event added successfully.');
    }

    // Update event
    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Check if event type is changing to/from Training
        $wasTraining = $event->event_type === 'Training';

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

        // Update the event
        $event->sport_id   = $request->sport_id;
        $event->event_name = $request->event_name;
        $event->event_date = $request->event_date;
        $event->event_type = $request->event_type;
        $event->location   = $request->location;
        $event->save();

        // Check if we need to send notifications
        $isTraining = $event->event_type === 'Training';
        
        // Send notifications if:
        // 1. Event type changed from non-training to training (new training event)
        // 2. Event type was and still is training (training event updated)
        if ($isTraining) {
            $action = $wasTraining ? 'updated' : 'created';
            $this->sendTrainingNotification($event, $action);
        }

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

    /**
     * Send training notifications to all athletes in the sport
     */
       private function sendTrainingNotification(Event $event, string $action = 'created')
    {
        try {
            Log::info('Starting training notification for event: ' . $event->event_id);

            // Get the sport name
            $sport = Sport::find($event->sport_id);
            if (!$sport) {
                Log::warning('Sport not found for event: ' . $event->event_id);
                return;
            }

            // Get all teams for this sport
            $teams = Team::where('sport_id', $event->sport_id)
                        ->where('removed', 0)
                        ->get();

            Log::info('Found ' . $teams->count() . ' teams for sport: ' . $sport->sport_name);

            if ($teams->isEmpty()) {
                Log::info('No teams found for sport: ' . $sport->sport_name);
                return;
            }

            // Get all athlete IDs from these teams
            $teamIds = $teams->pluck('team_id')->toArray();
            $athleteTeamEntries = AthleteTeam::whereIn('team_id', $teamIds)->get();
            
            Log::info('Found ' . $athleteTeamEntries->count() . ' athlete-team entries');

            if ($athleteTeamEntries->isEmpty()) {
                Log::info('No athletes found in teams for sport: ' . $sport->sport_name);
                return;
            }

            // Get athlete user IDs
            $athleteIds = $athleteTeamEntries->pluck('athlete_id')->unique()->toArray();
            $athletes = Athlete::whereIn('athlete_id', $athleteIds)
                            ->with('user')
                            ->get();

            Log::info('Found ' . $athletes->count() . ' athletes with user accounts');

            // Prepare notification data
            $formattedDate = date('F j, Y', strtotime($event->event_date));
            $title = "New Training Scheduled";
            
            // Better formatted message
            $message = "A training session has been {$action} for {$sport->sport_name}. ";
            $message .= "Date: {$formattedDate}. ";
            $message .= "Location: {$event->location}. ";
            $message .= "Event: {$event->event_name}";

            // Send notification to each athlete
            $notifications = [];
            foreach ($athletes as $athlete) {
                if ($athlete->user && $athlete->user->user_id) {
                    $notifications[] = [
                        'title' => $title,
                        'message' => $message,
                        'type' => 'info', // Use 'info' for training notifications (ENUM value)
                        'read' => false,
                        'user_id' => $athlete->user->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            Log::info('Prepared ' . count($notifications) . ' notifications for insertion');

            // Bulk insert notifications for better performance
            if (!empty($notifications)) {
                // Try bulk insert first
                try {
                    Notification::insert($notifications);
                    Log::info('Successfully inserted ' . count($notifications) . ' notifications');
                } catch (\Exception $e) {
                    // If bulk insert fails, try single inserts
                    Log::warning('Bulk insert failed, trying single inserts: ' . $e->getMessage());
                    foreach ($notifications as $notification) {
                        try {
                            Notification::create($notification);
                            Log::info('Notification created for user: ' . $notification['user_id']);
                        } catch (\Exception $singleError) {
                            Log::error('Failed to create notification for user ' . $notification['user_id'] . ': ' . $singleError->getMessage());
                        }
                    }
                }
            }

            Log::info('Training notification process completed for event: ' . $event->event_id);

        } catch (\Exception $e) {
            Log::error('Failed to send training notifications for event ' . $event->event_id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }
}