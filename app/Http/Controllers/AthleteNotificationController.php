<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AthleteNotificationController extends Controller
{
    // Show all notifications for the logged-in athlete
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('athlete.notifications.index', compact('notifications'));
    }

    // Mark a specific notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('notification_id', $id)
            ->where('user_id', Auth::id()) // ensure athlete can only mark their own
            ->firstOrFail();

        $notification->update(['read' => 1]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }
}
