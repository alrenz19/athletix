<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    // Display all notifications for Super Admin
    public function index()
    {
        $notifications = Notification::whereHas('user', function($q){
            $q->where('role', 'SuperAdmin');
        })->orderBy('created_at', 'desc')->paginate(10);

        return view('notifications.index', compact('notifications'));
    }

    // Mark notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->read = true;
        $notification->save();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }

    // Store a system-generated notification
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'type' => 'required|in:warning,info,success,error',
            'user_id' => 'required|exists:users,user_id'
        ]);

        Notification::create($request->only('title','message','type','user_id'));

        return redirect()->back()->with('success', 'Notification sent.');
    }
}
