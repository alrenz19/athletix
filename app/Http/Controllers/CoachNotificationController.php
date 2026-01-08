<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class CoachNotificationController extends Controller
{
    // Show all notifications for logged-in user
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('coach.notifications.index', compact('notifications'));
    }

    // Mark a specific notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', auth()->id()) // prevent marking others’ notifications
            ->firstOrFail();

        $notification->update(['read' => 1]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }
}
