<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Athlete;
use App\Models\Team;
use App\Models\AuditLog;

class AthleteController extends Controller
{
    public function index(Request $request)
    {
        // Start the query
        $query = Athlete::with('user', 'sport')->where('status', 'approved');
        
        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('year_level', 'like', "%{$search}%")
                  ->orWhereHas('sport', function($sportQuery) use ($search) {
                      $sportQuery->where('sport_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply status filter (conditions field)
        if ($request->filled('status')) {
            $query->where('conditions', $request->status);
        }
        
        // Apply year level filter
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }
        
        // Default sorting
        $query->orderBy('full_name');
        
        // Get paginated results
        $athletes = $query->paginate(15);
        
        // Get statistics for quick overview
        $stats = Athlete::selectRaw('conditions as status, COUNT(*) as count')
            ->groupBy('conditions')
            ->pluck('count', 'status')
            ->toArray();
        
        return view('staff.athletes.index', compact('athletes', 'stats'));
    }
     public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,injured,graduate'
        ]);
        
        $athlete = Athlete::findOrFail($id);
        
        // Authorization check - coach can only update athletes in their sport
        $user = auth()->user();       
        $oldStatus = $athlete->conditions;
        $newStatus = $request->status;
        
        $athlete->update([
            'conditions' => $newStatus
        ]);
        
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Status Change',
            'module' => 'User Security',
            'description' => "Athlete: {$athlete->full_name} Status changed from {$oldStatus} to {$newStatus}",
            'ip_address' => $request->ip(),
        ]);
        
        return redirect()->back()->with('success', "Athlete status updated to {$newStatus}.");
    }

    public function deactivateIndex(Request $request)
    {
        $query = Athlete::query()->where('status', 'approved'); // Only active athletes

        // Search by name
        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%');
        }

        // Filter by team
        if ($request->filled('team')) {
            $query->whereHas('teams', function ($q) use ($request) {
                $q->where('id', $request->team);
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $athletes = $query->with('teams')->get();

        // Get all teams for filter dropdown
        $teams = Team::all();

        return view('staff.athlete_deactivate', compact('athletes', 'teams'));
    }


    public function deactivate(Request $request, $id)
    {
        $athlete = Athlete::findOrFail($id);
        $athlete->status = 'inactive'; // Archive/deactivate logic
        $athlete->save();
        

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deactivate User',
            'module' => 'User Security',
            'description' => "Deactivated user: {$athlete->full_name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('staff.athlete.deactivate')
                         ->with('success', 'Athlete deactivated successfully.');
    }

        // Edit athlete data
    public function edit($id)
    {
        $athlete = Athlete::findOrFail($id);
        return view('staff.athletes.edit', compact('athlete'));
    }

    // Update athlete data
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'year_level' => 'required|in:1st Year,2nd Year,3rd Year,4th Year,Alumni',
            'status' => 'required|in:pending,in review,approved,reject,inactive',
            'conditions' => 'required|in:active,injured,graduate',
            'password' => 'nullable|string|min:6|confirmed', // password confirmation
        ]);

        $athlete = Athlete::findOrFail($id);
        $athlete->update($request->only(['full_name', 'birthdate', 'gender', 'year_level', 'status', 'conditions']));

        // update linked user (for password)
        if ($athlete->user && $request->filled('password')) {
            $athlete->user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Information',
            'module' => 'Athlete',
            'description' => "Updated user: {$athlete->full_name}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('staff.athletes.index')->with('success', 'Athlete updated successfully.');
    }


    public function requestJoin($eventId)
    {
        $athlete = auth()->user()->athlete;

        EventRegistration::firstOrCreate([
            'event_id'   => $eventId,
            'athlete_id' => $athlete->athlete_id,
        ]);

        return back()->with('success', 'Request sent. Awaiting coach approval.');
    }
}
