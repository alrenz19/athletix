<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\Attendance;
use App\Models\Performance;

class StaffDashboardController extends Controller
{
    public function index()
    {
        // Metric counts
        $notificationsCount = Announcement::where('removed', 0)->count();
        $eventsCount = Event::where('removed', 0)->count();
        $athleteCount = Athlete::where('removed', 0)->count();
        
        $performanceCount = Performance::where('removed', 0)->count();
        $PendingRegistrationCount = Athlete::where('removed', 0)
            ->whereIn('status', ['pending', 'in review'])->count();

        // Donut / Bar chart data
        $donutData = [
            'athletes' => Athlete::where('removed', 0)->count(),
            'announcement' => Announcement::where('removed', 0)->count(),
            'events' => Event::where('removed', 0)->count(),
            'pendings' => Athlete::where('removed', 0)
            ->whereIn('status', ['pending', 'in review'])->count()
        ];

        $barData = $donutData; // You can customize differently if needed

        return view('staff.dashboard', compact(
            'notificationsCount',
            'performanceCount',
            'athleteCount',
            'eventsCount',
            'PendingRegistrationCount',
            'donutData',
            'barData'
        ));
    }
}
