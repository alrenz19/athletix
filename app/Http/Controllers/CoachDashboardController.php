<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\TrainingNote;
use App\Models\Event;

class CoachDashboardController extends Controller
{

    public function index()
    {
        $coach = auth()->user()->coach;

        if (!$coach) {
            // No coach profile → just show empty stats
            $athletesCount = 0;
            $performanceCount = 0;
            $eventsCount = 0;
            $attendanceCount = 0;

        
        } else {

            $athletesCount = Athlete::where('sport_id', $coach->sport_id)->count();
            $performanceCount = TrainingNote::whereHas('athlete', fn($q) => $q->where('sport_id', $coach->sport_id))->count();
            $eventsCount = Event::where('sport_id', $coach->sport_id)->count();
            $attendanceCount = 0;
            
        }

        $donutData = [
            'athletes' => $athletesCount,
            'performance' => $performanceCount,
            'events' => $eventsCount,
            'attendance' => $attendanceCount
        ];

        $barData = $donutData;

        return view('coach.dashboard', compact(
            'athletesCount', 'performanceCount', 'eventsCount', 'attendanceCount', 'donutData', 'barData'
        ));
    }
}
