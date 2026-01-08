<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\AuditLog;

class AttendanceController extends Controller
{
    // Display attendance records
    public function index()
    {
        $attendances = Attendance::with(['athlete', 'event'])
            ->where('removed', 0)
            ->latest()
            ->get();

        return view('attendance.index', compact('attendances'));
    }

    // Show form to add attendance
    public function create()
    {
        $athletes = Athlete::all();
        $events = Event::all();

        return view('attendance.create', compact('athletes', 'events'));
    }

    // Store new attendance
    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:' . (new Athlete)->getTable() . ',athlete_id',
            'event_id'   => 'required|exists:' . (new Event)->getTable() . ',event_id',
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);

        $attendance = Attendance::create($request->all());

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Add Attendance',
            'module' => 'Attendance',
            'description' => "Added attendance for athlete ID {$request->athlete_id} (Status: {$request->status})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('attendance')->with('success', 'Attendance added successfully.');
    }

    // Edit attendance
    public function edit(Attendance $attendance)
    {
        $athletes = Athlete::all();
        $events = Event::all();

        return view('attendance.edit', compact('attendance', 'athletes', 'events'));
    }

    // Update attendance
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);

        $attendance->update($request->only('status'));

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Attendance',
            'module' => 'Attendance',
            'description' => "Updated attendance ID {$attendance->attendance_id} (Status: {$request->status})",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('attendance')->with('success', 'Attendance updated successfully.');
    }

    // Soft delete attendance
    public function destroy(Attendance $attendance)
    {
        $attendance->update(['removed' => 1]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete Attendance',
            'module' => 'Attendance',
            'description' => "Deleted attendance ID {$attendance->attendance_id}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('attendance')->with('success', 'Attendance removed.');
    }
}
