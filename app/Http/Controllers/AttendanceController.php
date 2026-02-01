<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Sport;
use App\Models\AuditLog;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    // Display attendance table view with filters
    public function index(Request $request)
    {
        $events = Event::where('removed', false)
            ->orderBy('event_date', 'desc')
            ->get();
        
        $sports = Sport::where('removed', false)->get();
        
        // Build query with filters
        $query = Attendance::with(['athlete', 'event', 'athlete.sport'])
            ->where('removed', 0);
        
        // Apply date filter
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }
        
        // Apply event filter
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }
        
        // Apply sport filter (through athlete)
        if ($request->filled('sport_id')) {
            $query->whereHas('athlete', function($q) use ($request) {
                $q->where('sport_id', $request->sport_id);
            });
        }
        
        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->latest()->get();
        
        return view('attendance.index', compact('attendances', 'events', 'sports'));
    }

    // Show form to add attendance
    public function create()
    {
        $athletes = Athlete::where('removed', false)->get();
        $events = Event::where('removed', false)->get();

        return view('attendance.create', compact('athletes', 'events'));
    }

    // Store new attendance
    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:' . (new Athlete)->getTable() . ',athlete_id',
            'event_id'   => 'required|exists:' . (new Event)->getTable() . ',event_id',
            'status' => 'required|in:Present,Absent,Late,Excused',
            'event_date' => 'required|date',
        ]);
        
        // Check if date is editable (within 1 week or SuperAdmin)
        $attendanceDate = Carbon::parse($request->event_date);
        $oneWeekAgo = Carbon::now()->subWeek();
        
        if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
            return redirect()->back()->with('error', 'Cannot edit attendance for dates older than one week');
        }
        
        $attendance = Attendance::create([
            'athlete_id' => $request->athlete_id,
            'event_id' => $request->event_id,
            'status' => $request->status,
            'event_date' => $request->event_date,
            'removed' => false
        ]);

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
        $athletes = Athlete::where('removed', false)->get();
        $events = Event::where('removed', false)->get();

        return view('attendance.edit', compact('attendance', 'athletes', 'events'));
    }

    // Update attendance
    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);
        
        // Check if date is editable
        $attendanceDate = Carbon::parse($attendance->event_date);
        $oneWeekAgo = Carbon::now()->subWeek();
        
        if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
            return redirect()->back()->with('error', 'Cannot edit attendance for dates older than one week');
        }
        
        $oldStatus = $attendance->status;
        $attendance->update(['status' => $request->status]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Attendance',
            'module' => 'Attendance',
            'description' => "Updated attendance ID {$attendance->attendance_id} from {$oldStatus} to {$request->status}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('attendance')->with('success', 'Attendance updated successfully.');
    }

    // AJAX Update attendance status
    public function updateStatus(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Check if date is editable
        $attendanceDate = Carbon::parse($attendance->event_date);
        $oneWeekAgo = Carbon::now()->subWeek();
        
        if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit attendance for dates older than one week'
            ], 403);
        }
        
        $request->validate([
            'status' => 'required|in:Present,Absent,Late,Excused',
        ]);
        
        $oldStatus = $attendance->status;
        $attendance->update(['status' => $request->status]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Attendance',
            'module' => 'Attendance',
            'description' => "Updated attendance ID {$attendance->attendance_id} from {$oldStatus} to {$request->status}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully.',
            'status' => $request->status
        ]);
    }

    // Bulk update attendance
    public function bulkUpdate(Request $request)
    {
        $attendances = $request->input('attendances', []);
        $updatedCount = 0;
        
        foreach ($attendances as $attendanceData) {
            if (!isset($attendanceData['attendance_id']) || !isset($attendanceData['status'])) {
                continue;
            }
            
            $attendance = Attendance::find($attendanceData['attendance_id']);
            
            if (!$attendance) {
                continue;
            }
            
            $attendanceDate = Carbon::parse($attendance->event_date);
            $oneWeekAgo = Carbon::now()->subWeek();
            
            // Skip if date is not editable (unless SuperAdmin)
            if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
                continue;
            }
            
            $attendance->update(['status' => $attendanceData['status']]);
            $updatedCount++;
        }
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Bulk Update Attendance',
            'module' => 'Attendance',
            'description' => "Bulk updated {$updatedCount} attendance records",
            'ip_address' => $request->ip(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => "{$updatedCount} attendance records updated successfully."
        ]);
    }

    // Soft delete attendance
    public function destroy(Attendance $attendance)
    {
        // Check if date is editable
        $attendanceDate = Carbon::parse($attendance->event_date);
        $oneWeekAgo = Carbon::now()->subWeek();
        
        if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
            return redirect()->back()->with('error', 'Cannot delete attendance for dates older than one week');
        }
        
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

    // AJAX Delete attendance
    public function deleteAjax($id)
    {
        $attendance = Attendance::findOrFail($id);
        
        // Check if date is editable
        $attendanceDate = Carbon::parse($attendance->event_date);
        $oneWeekAgo = Carbon::now()->subWeek();
        
        if ($attendanceDate < $oneWeekAgo && auth()->user()->role !== 'SuperAdmin') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete attendance for dates older than one week'
            ], 403);
        }
        
        $attendance->update(['removed' => 1]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete Attendance',
            'module' => 'Attendance',
            'description' => "Deleted attendance ID {$attendance->attendance_id}",
            'ip_address' => request()->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully.'
        ]);
    }
}