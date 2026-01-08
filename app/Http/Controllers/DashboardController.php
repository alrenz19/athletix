<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Performance;

class DashboardController extends Controller
{
    public function index()
    {
        // Metric counts
        $notificationsCount = Announcement::where('removed', 0)->count();
        $performanceCount = Performance::where('removed', 0)->count();
        $eventsCount = Event::where('removed', 0)->count();
        $attendanceCount = Attendance::where('removed', 0)->count();

        // Donut / Bar chart data
        $donutData = [
            'athletes' => Athlete::where('removed', 0)->count(),
            'performance' => Performance::where('removed', 0)->count(),
            'events' => Event::where('removed', 0)->count(),
            'attendance' => Attendance::where('removed', 0)->count(),
        ];

        $barData = $donutData; // You can customize differently if needed

        return view('dashboard', compact(
            'notificationsCount',
            'performanceCount',
            'eventsCount',
            'attendanceCount',
            'donutData',
            'barData'
        ));
    }
}
