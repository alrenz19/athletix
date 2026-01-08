<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class StaffNotificationController extends Controller
{
    // Show notifications for the logged-in staff
    public function index()
    {
        $staff = Auth::user();

        $notifications = Notification::whereHas('user', function($q) {
                $q->where('role', 'Staff');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // ✅ use paginate instead of get()

        return view('staff.notifications.index', compact('notifications'));
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('notification_id', $id)
            ->firstOrFail();

        $notification->update(['read' => 1]);

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    // Mark all as read
    public function markAllAsRead()
    {
        Notification::where('user_id', Auth::id())->update(['read' => 1]);

        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
}
