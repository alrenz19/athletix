<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\User;
use App\Models\Athlete;
use App\Models\Coach;
use App\Models\Staff;
use App\Models\Sport;
use App\Models\Section;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $userRole = Auth::user()->role;

        $announcements = Announcement::with(['poster', 'sport', 'section']) // ✅ eager load
            ->where('removed', 0)
            ->when($userRole !== 'SuperAdmin', function ($query) use ($userRole) {
                $query->where(function ($q) use ($userRole) {
                    $q->where('target', $userRole)
                    ->orWhere('target', 'All');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:All,Athletes,Coaches,Staff',
            'sport_id' => 'nullable|exists:sports,sport_id',
            'section_id' => 'nullable|exists:sections,section_id',
        ]);

        // Create announcement
        $announcement = Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target' => $validated['target'],
            'sport_id' => $validated['sport_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'posted_by' => Auth::id(), // current logged-in user
            'removed' => 0,
        ]);

        // Send notifications based on target
        $this->sendAnnouncementNotifications($announcement);

        return redirect()->route('announcements.index')->with('success', 'Announcement added successfully.');
    }

    /**
     * Send notifications for announcement based on target audience
     */
    private function sendAnnouncementNotifications(Announcement $announcement)
    {
        try {
            Log::info('Starting announcement notifications for announcement ID: ' . $announcement->id);

            $title = "New Announcement: " . $announcement->title;
            $message = substr($announcement->message, 0, 200) . (strlen($announcement->message) > 200 ? '...' : '');
            
            $notifications = [];

            // Determine who to notify based on target
            switch ($announcement->target) {
                case 'All':
                    $this->notifyAllUsers($notifications, $title, $message, $announcement);
                    break;
                
                case 'Athletes':
                    $this->notifyAthletes($notifications, $title, $message, $announcement);
                    break;
                
                case 'Coaches':
                    $this->notifyCoaches($notifications, $title, $message, $announcement);
                    break;
                
                case 'Staff':
                    $this->notifyStaff($notifications, $title, $message, $announcement);
                    break;
            }

            // Always notify SuperAdmins about new announcements
            $this->notifySuperAdmins($notifications, $title, $message, $announcement);

            // Bulk insert notifications
            if (!empty($notifications)) {
                $this->bulkInsertNotifications($notifications);
            }

            Log::info('Announcement notification process completed for announcement: ' . $announcement->id);

        } catch (\Exception $e) {
            Log::error('Failed to send announcement notifications: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
        }
    }

    /**
     * Notify all users (All target)
     */
    private function notifyAllUsers(&$notifications, $title, $message, $announcement)
    {
        try {
            // Get all active users
            $users = User::where('removed', 0)
                        ->whereIn('role', ['Athlete', 'Coach', 'Staff'])
                        ->get();

            foreach ($users as $user) {
                // Apply sport filter if specified
                if ($announcement->sport_id) {
                    $userSportId = $this->getUserSportId($user);
                    if ($userSportId && $userSportId != $announcement->sport_id) {
                        continue; // Skip users not in the specified sport
                    }
                }

                // Apply section filter if specified (for athletes only)
                if ($announcement->section_id && $user->role === 'Athlete') {
                    $userSectionId = $this->getUserSectionId($user);
                    if ($userSectionId && $userSectionId != $announcement->section_id) {
                        continue; // Skip athletes not in the specified section
                    }
                }

                $notifications[] = [
                    'title' => $title,
                    'message' => $message,
                    'type' => 'info',
                    'read' => false,
                    'user_id' => $user->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Log::info('Prepared notifications for all users: ' . count($users));

        } catch (\Exception $e) {
            Log::error('Error notifying all users: ' . $e->getMessage());
        }
    }

    /**
     * Notify all athletes
     */
    private function notifyAthletes(&$notifications, $title, $message, $announcement)
    {
        try {
            // Get all athletes
            $athletes = Athlete::where('removed', 0)
                            ->with('user')
                            ->get();

            foreach ($athletes as $athlete) {
                // Apply sport filter if specified
                if ($announcement->sport_id && $athlete->sport_id != $announcement->sport_id) {
                    continue;
                }

                // Apply section filter if specified
                if ($announcement->section_id && $athlete->section_id != $announcement->section_id) {
                    continue;
                }

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
                }
            }

            Log::info('Prepared notifications for athletes: ' . count($athletes));

        } catch (\Exception $e) {
            Log::error('Error notifying athletes: ' . $e->getMessage());
        }
    }

    /**
     * Notify all coaches
     */
    private function notifyCoaches(&$notifications, $title, $message, $announcement)
    {
        try {
            // Get all coaches
            $coaches = Coach::where('removed', 0)
                          ->with('user')
                          ->get();

            foreach ($coaches as $coach) {
                // Apply sport filter if specified
                if ($announcement->sport_id && $coach->sport_id != $announcement->sport_id) {
                    continue;
                }

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
                }
            }

            Log::info('Prepared notifications for coaches: ' . count($coaches));

        } catch (\Exception $e) {
            Log::error('Error notifying coaches: ' . $e->getMessage());
        }
    }

    /**
     * Notify all staff
     */
    private function notifyStaff(&$notifications, $title, $message, $announcement)
    {
        try {
            // Get all staff users
            $staffUsers = User::where('role', 'Staff')
                            ->where('removed', 0)
                            ->get();

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
            }

            Log::info('Prepared notifications for staff: ' . count($staffUsers));

        } catch (\Exception $e) {
            Log::error('Error notifying staff: ' . $e->getMessage());
        }
    }

    /**
     * Notify all super admins
     */
    private function notifySuperAdmins(&$notifications, $title, $message, $announcement)
    {
        try {
            // Get all super admin users
            $superAdmins = User::where('role', 'super_admin')
                             ->where('removed', 0)
                             ->get();

            foreach ($superAdmins as $user) {
                $notifications[] = [
                    'title' => "📢 " . $title,
                    'message' => "New announcement posted: " . $message,
                    'type' => 'info',
                    'read' => false,
                    'user_id' => $user->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Log::info('Prepared notifications for super admins: ' . count($superAdmins));

        } catch (\Exception $e) {
            Log::error('Error notifying super admins: ' . $e->getMessage());
        }
    }

    /**
     * Get user's sport ID based on their role
     */
    private function getUserSportId(User $user)
    {
        switch ($user->role) {
            case 'Athlete':
                $athlete = Athlete::where('user_id', $user->user_id)->first();
                return $athlete ? $athlete->sport_id : null;
            
            case 'Coach':
                $coach = Coach::where('user_id', $user->user_id)->first();
                return $coach ? $coach->sport_id : null;
            
            default:
                return null;
        }
    }

    /**
     * Get user's section ID (for athletes only)
     */
    private function getUserSectionId(User $user)
    {
        if ($user->role === 'Athlete') {
            $athlete = Athlete::where('user_id', $user->user_id)->first();
            return $athlete ? $athlete->section_id : null;
        }
        return null;
    }

    /**
     * Bulk insert notifications with fallback to single inserts
     */
    private function bulkInsertNotifications($notifications)
    {
        try {
            Notification::insert($notifications);
            Log::info('Successfully bulk inserted ' . count($notifications) . ' announcement notifications');
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
                    Log::error('Failed to create announcement notification for user ' . $notification['user_id'] . ': ' . $singleError->getMessage());
                }
            }
            Log::info('Single inserts completed: ' . $successCount . ' successful, ' . $errorCount . ' failed');
        }
    }
}