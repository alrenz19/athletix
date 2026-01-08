<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\AuditLog;

class RegistrationApprovalController extends Controller
{
    // Show all athletes with pending or in review status
    public function index(Request $request)
    {
        $query = Athlete::where('removed', 0)
            ->whereIn('status', ['pending', 'in review']);

        // Search by name or school_id
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                ->orWhere('school_id', 'like', "%{$search}%");
            });
        }

        // Filter by year level
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $pendingAthletes = $query->paginate(10);

        return view('staff.registration_approval', compact('pendingAthletes'));
    }

    // Approve athlete registration
    public function approve(Request $request, $id)
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->status = 'approved';
        $athlete->save();

                // Save a notification for the athlete
        Notification::create([
            'title' => 'Registration Approved',
            'message' => $request->reason ?? 'Your registration has been approved successfully.',
            'type' => 'success',
            'user_id' => $athlete->user_id,
        ]);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Athlete Approved',
            'module' => 'User Approved',
            'description' => "Approved Athlete: {$athlete->full_name}",
            'ip_address' => $request->ip(),
        ]);
        return redirect()->route('staff.approval.index')
            ->with('success', 'Athlete registration approved.');
    }

    // Reject athlete registration
   public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $athlete = Athlete::findOrFail($id);
        $athlete->status = 'reject';
        $athlete->removed = 1;
        $athlete->save();

        // Save a notification for the athlete
        Notification::create([
            'title' => 'Registration Rejected',
            'message' => $request->reason,
            'type' => 'error',
            'user_id' => $athlete->user_id, // 👈 notify athlete’s user account
        ]);

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Athlete Rejection',
            'module' => 'User Registration',
            'description' => "Reject Athlete: {$athlete->full_name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('staff.approval.index')
            ->with('error', 'Athlete registration rejected.');
    }


    // Optional: Move athlete to in review
    public function review($id)
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->status = 'in review';
        $athlete->save();

        return redirect()->route('staff.approval.index')
            ->with('info', 'Athlete registration moved to review.');
    }
}
