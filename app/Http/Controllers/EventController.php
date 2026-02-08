<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Sport;
use App\Models\Team;
use App\Models\Athlete;
use App\Models\AthleteTeam;
use App\Models\Notification;
use App\Models\User;
use App\Models\Coach;
use App\Models\Staff;

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
                        ->orderBy('event_date', 'asc')
                        ->get();

            $sports = Sport::where('removed', 0)
                        ->where('sport_id', $coach->sport_id)
                        ->get();
        } else {
            // SuperAdmin & Staff see all
            $events = Event::where('removed', 0)
                        ->with('sport')
                        ->orderBy('event_date', 'asc')
                        ->get();
            $sports = Sport::where('removed', 0)->get();
        }

        return view('events', compact('events', 'sports'));
    }

    // Store new event
    public function storeEvent(Request $request)
    {
        $user = Auth::user();

        // Validate both date and time separately
        $request->validate([
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
        ]);

        // Combine date and time into a single timestamp
        $eventDateTime = $request->event_date . ' ' . $request->event_time;

        if ($user->role == 'Coach') {
            $coach = $user->coach;

            $request->validate([
                'sport_id'   => 'required|in:' . $coach->sport_id,
                'event_name' => 'required|string|max:255',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255',
            ]);
        } else {
            $request->validate([
                'sport_id'   => 'required|exists:sports,sport_id',
                'event_name' => 'required|string|max:255',
                'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
                'location'   => 'required|string|max:255',
            ]);
        }

        // Create the event with combined datetime
        $eventData = [
            'sport_id'   => $request->sport_id,
            'event_name' => $request->event_name,
            'event_date' => $eventDateTime,
            'event_type' => $request->event_type,
            'location'   => $request->location,
        ];

        $event = Event::create($eventData);

        // Send notifications for all event types
        $this->sendEventNotification($event, 'created');

        return redirect()->back()->with('success', 'Event added successfully.');
    }

    // Update event
    public function updateEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $user = Auth::user();

        // Validate both date and time separately
        $request->validate([
            'event_date' => 'required|date',
            'event_time' => 'required|date_format:H:i',
            'event_name' => 'required|string|max:255',
            'event_type' => 'required|in:Training,Competition,Meeting,TryOut',
            'location'   => 'required|string|max:255',
        ]);

        // Combine date and time into a single timestamp
        $eventDateTime = $request->event_date . ' ' . $request->event_time;

        // Check coach permissions
        if ($user->role == 'Coach') {
            $coach = $user->coach;
            if ($event->sport_id != $coach->sport_id) {
                return redirect()->back()->with('error', 'You cannot edit events outside your sport.');
            }
            
            $request->validate([
                'sport_id' => 'required|in:' . $coach->sport_id,
            ]);
        } else {
            $request->validate([
                'sport_id' => 'required|exists:sports,sport_id',
            ]);
        }

        // Update the event with combined datetime
        $event->sport_id   = $request->sport_id;
        $event->event_name = $request->event_name;
        $event->event_date = $eventDateTime;
        $event->event_type = $request->event_type;
        $event->location   = $request->location;
        $event->save();

        // Send notifications for all event types
        $this->sendEventNotification($event, 'updated');

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
     * Send event notifications to:
     * 1. All athletes in the sport (via athlete_team)
     * 2. Coaches in the sport
     * 3. All staff users
     * 4. All super_admin users
     */
    private function sendEventNotification(Event $event, string $action = 'created')
    {
        try {
            Log::info('Starting event notification for event: ' . $event->event_id . ', type: ' . $event->event_type);

            // Get the sport
            $sport = Sport::find($event->sport_id);
            if (!$sport) {
                Log::warning('Sport not found for event: ' . $event->event_id);
                return;
            }

            // Prepare notification data with formatted date and time
            $carbonDate = Carbon::parse($event->event_date);
            $formattedDateTime = $carbonDate->format('F j, Y \a\t h:i A');
            $title = "New {$event->event_type} Scheduled";
            $message = "A {$event->event_type} event has been {$action} for {$sport->sport_name}. ";
            $message .= "Date & Time: {$formattedDateTime}. ";
            $message .= "Location: {$event->location}. ";
            $message .= "Event: {$event->event_name}";

            $notifications = [];

            // 1. Notify athletes in the sport (via athlete_team)
            $this->notifyAthletesInSport($event->sport_id, $notifications, $title, $message);

            // 2. Notify coaches in the sport
            $this->notifyCoachesInSport($event->sport_id, $notifications, $title, $message);

            // 3. Notify all staff users
            $this->notifyAllStaff($notifications, $title, $message);

            // 4. Notify all super_admin users
            $this->notifyAllSuperAdmins($notifications, $title, $message);

            // Bulk insert notifications
            if (!empty($notifications)) {
                $this->bulkInsertNotifications($notifications);
            }

            Log::info('Event notification process completed for event: ' . $event->event_id);

        } catch (\Exception $e) {
            Log::error('Failed to send event notifications for event ' . $event->event_id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Send training-specific notifications (with additional recipients)
     * This maintains backward compatibility with existing code
     */
    private function sendTrainingNotification(Event $event, string $action = 'created')
    {
        try {
            Log::info('Starting training notification for event: ' . $event->event_id);

            // Get the sport
            $sport = Sport::find($event->sport_id);
            if (!$sport) {
                Log::warning('Sport not found for event: ' . $event->event_id);
                return;
            }

            // Prepare notification data with formatted date and time
            $carbonDate = Carbon::parse($event->event_date);
            $formattedDateTime = $carbonDate->format('F j, Y \a\t h:i A');
            $title = "New Training Scheduled";
            $message = "A training session has been {$action} for {$sport->sport_name}. ";
            $message .= "Date & Time: {$formattedDateTime}. ";
            $message .= "Location: {$event->location}. ";
            $message .= "Event: {$event->event_name}";

            $notifications = [];

            // 1. Notify athletes in the sport (via athlete_team)
            $this->notifyAthletesInSport($event->sport_id, $notifications, $title, $message);

            // 2. Notify coaches in the sport
            $this->notifyCoachesInSport($event->sport_id, $notifications, $title, $message);

            // 3. Notify all staff users
            $this->notifyAllStaff($notifications, $title, $message);

            // 4. Notify all super_admin users
            $this->notifyAllSuperAdmins($notifications, $title, $message);

            // Bulk insert notifications
            if (!empty($notifications)) {
                $this->bulkInsertNotifications($notifications);
            }

            Log::info('Training notification process completed for event: ' . $event->event_id);

        } catch (\Exception $e) {
            Log::error('Failed to send training notifications for event ' . $event->event_id . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Notify athletes in a specific sport via athlete_team relationship
     */
    private function notifyAthletesInSport($sportId, &$notifications, $title, $message)
    {
        try {
            // Get all teams for this sport
            $teams = Team::where('sport_id', $sportId)
                        ->where('removed', 0)
                        ->get();

            if ($teams->isEmpty()) {
                Log::info('No teams found for sport ID: ' . $sportId);
                return;
            }

            // Get all athlete IDs from these teams
            $teamIds = $teams->pluck('team_id')->toArray();
            $athleteTeamEntries = AthleteTeam::whereIn('team_id', $teamIds)->get();

            if ($athleteTeamEntries->isEmpty()) {
                Log::info('No athletes found in teams for sport ID: ' . $sportId);
                return;
            }

            // Get athlete user IDs
            $athleteIds = $athleteTeamEntries->pluck('athlete_id')->unique()->toArray();
            $athletes = Athlete::whereIn('athlete_id', $athleteIds)
                            ->with('user')
                            ->get();

            $athleteCount = 0;
            foreach ($athletes as $athlete) {
                if ($athlete->user && $athlete->user->user_id) {
                    $notifications[] = [
                        'title' => $title,
                        'message' => $message,
                        'type' => 'info',
                        'read' => false,
                        'user_id' => $athlete->user->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $athleteCount++;
                }
            }

            Log::info('Prepared ' . $athleteCount . ' athlete notifications for sport ID: ' . $sportId);

        } catch (\Exception $e) {
            Log::error('Error notifying athletes in sport ' . $sportId . ': ' . $e->getMessage());
        }
    }

    /**
     * Notify coaches in a specific sport
     */
    private function notifyCoachesInSport($sportId, &$notifications, $title, $message)
    {
        try {
            $coaches = Coach::where('sport_id', $sportId)
                          ->where('removed', 0)
                          ->with('user')
                          ->get();

            $coachCount = 0;
            foreach ($coaches as $coach) {
                if ($coach->user && $coach->user->user_id) {
                    $notifications[] = [
                        'title' => $title,
                        'message' => $message,
                        'type' => 'info',
                        'read' => false,
                        'user_id' => $coach->user->user_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $coachCount++;
                }
            }

            Log::info('Prepared ' . $coachCount . ' coach notifications for sport ID: ' . $sportId);

        } catch (\Exception $e) {
            Log::error('Error notifying coaches in sport ' . $sportId . ': ' . $e->getMessage());
        }
    }

    /**
     * Notify all staff users
     */
    private function notifyAllStaff(&$notifications, $title, $message)
    {
        try {
            // Get all staff users
            $staffUsers = User::where('role', 'Staff')
                            ->where('removed', 0)
                            ->get();

            $staffCount = 0;
            foreach ($staffUsers as $user) {
                $notifications[] = [
                    'title' => $title,
                    'message' => $message,
                    'type' => 'info',
                    'read' => false,
                    'user_id' => $user->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $staffCount++;
            }

            Log::info('Prepared ' . $staffCount . ' staff notifications');

        } catch (\Exception $e) {
            Log::error('Error notifying staff users: ' . $e->getMessage());
        }
    }

    /**
     * Notify all super_admin users
     */
    private function notifyAllSuperAdmins(&$notifications, $title, $message)
    {
        try {
            // Get all super_admin users
            $superAdmins = User::where('role', 'SuperAdmin')
                             ->where('removed', 0)
                             ->get();

            $superAdminCount = 0;
            foreach ($superAdmins as $user) {
                $notifications[] = [
                    'title' => $title,
                    'message' => $message,
                    'type' => 'info',
                    'read' => false,
                    'user_id' => $user->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $superAdminCount++;
            }

            Log::info('Prepared ' . $superAdminCount . ' super_admin notifications');

        } catch (\Exception $e) {
            Log::error('Error notifying super_admin users: ' . $e->getMessage());
        }
    }

    /**
     * Bulk insert notifications with fallback to single inserts
     */
    private function bulkInsertNotifications($notifications)
    {
        try {
            Notification::insert($notifications);
            Log::info('Successfully bulk inserted ' . count($notifications) . ' notifications');
        } catch (\Exception $e) {
            // If bulk insert fails, try single inserts
            Log::warning('Bulk insert failed, trying single inserts: ' . $e->getMessage());
            $successCount = 0;
            $errorCount = 0;
            foreach ($notifications as $notification) {
                try {
                    Notification::create($notification);
                    $successCount++;
                } catch (\Exception $singleError) {
                    $errorCount++;
                    Log::error('Failed to create notification for user ' . $notification['user_id'] . ': ' . $singleError->getMessage());
                }
            }
            Log::info('Single inserts completed: ' . $successCount . ' successful, ' . $errorCount . ' failed');
        }
    }
}