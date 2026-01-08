<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Performance;
use App\Exports\AttendanceExport;
use App\Exports\PerformanceExport;
use Maatwebsite\Excel\Facades\Excel;

class CoachReportController extends Controller
{
    public function attendance()
    {
        $coach = auth()->user()->coach;

        $athletes = Athlete::where('sport_id', $coach->sport_id)
            ->where('removed', 0)
            ->get();

        $events = Event::where('sport_id', $coach->sport_id)
            ->where('removed', 0)
            ->get();

        return view('coach.reports.attendance', compact('athletes', 'events'));
    }

    public function performance()
    {
        $coach = auth()->user()->coach;

        $athletes = Athlete::where('sport_id', $coach->sport_id)
            ->where('removed', 0)
            ->get();

        $events = Event::where('sport_id', $coach->sport_id)
            ->where('removed', 0)
            ->get();

        return view('coach.reports.performance', compact('athletes', 'events'));
    }

    public function filter(Request $request)
    {
        $coach = auth()->user()->coach;

        if ($request->report_type === 'attendance') {
            $query = Attendance::with(['athlete', 'event'])
                ->whereHas('athlete', fn($q) => $q->where('sport_id', $coach->sport_id))
                ->whereHas('event', fn($q) => $q->where('sport_id', $coach->sport_id));
        } elseif ($request->report_type === 'performance') {
            $query = Performance::with(['athlete', 'event', 'awards'])
                ->whereHas('athlete', fn($q) => $q->where('sport_id', $coach->sport_id))
                ->whereHas('event', fn($q) => $q->where('sport_id', $coach->sport_id));
        } else {
            return response()->json([]);
        }

        if ($request->filled('athlete_id')) {
            $query->where('athlete_id', $request->athlete_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // Recognition filter
        if ($request->report_type === 'performance' && $request->filled('recognition')) {
            $query->whereHas('awards', function ($q) use ($request) {
                $q->where('title', $request->recognition);
            });
        }

        return response()->json($query->orderBy('created_at', 'desc')->get());
    }

    public function exportPerformanceXLSX(Request $request)
    {
        $coach = auth()->user()->coach;

        $query = Performance::with(['athlete', 'event'])
            ->whereHas('athlete', fn($q) => $q->where('sport_id', $coach->sport_id))
            ->whereHas('event', fn($q) => $q->where('sport_id', $coach->sport_id));

        if ($request->filled('athlete_id')) {
            $query->where('athlete_id', $request->athlete_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('recognition')) {
            $query->whereHas('awards', function ($q) use ($request) {
                $q->where('title', $request->recognition);
            });
        }

        $performances = $query->orderBy('created_at', 'desc')->get()->toArray();
        
        return Excel::download(new PerformanceExport($performances), 'performance_report.xlsx');
    }

    public function exportAttendanceXLSX(Request $request)
    {
        $coach = auth()->user()->coach;

        $query = Attendance::with(['athlete', 'event'])
            ->whereHas('athlete', fn($q) => $q->where('sport_id', $coach->sport_id))
            ->whereHas('event', fn($q) => $q->where('sport_id', $coach->sport_id));

        if ($request->filled('athlete_id')) {
            $query->where('athlete_id', $request->athlete_id);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $attendances = $query->orderBy('created_at', 'desc')->get()->toArray();
        
        return Excel::download(new AttendanceExport($attendances), 'attendance_report.xlsx');
    }
}