<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\EventRegistration;

class AthleteStatusController extends Controller
{
    public function index(Request $request)
    {
        $athleteId = Auth::user()->athlete->athlete_id; // safer to use athlete_id

        $search = $request->input('search');
        $filterStatus = $request->input('status');

        $query = EventRegistration::with('event.sport')
            ->where('athlete_id', $athleteId);

        // Search by event name
        if ($search) {
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('event_name', 'like', '%' . $search . '%');
            });
        }

        // Filter by status
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }

        $statuses = $query->orderByDesc('created_at')->paginate(10);

        return view('athlete.status', compact('statuses', 'search', 'filterStatus'));
    }
}
