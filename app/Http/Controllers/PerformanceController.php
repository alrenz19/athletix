<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Performance;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\AuditLog;

class PerformanceController extends Controller
{
    // Display athlete performances
    public function index()
    {
        $performances = Performance::with(['athlete', 'event'])
            ->where('removed', 0)
            ->latest()
            ->get();

        return view('performance.index', compact('performances'));
    }

    // Show form to add performance
    public function create()
    {
        $athletes = Athlete::all();
        $events = Event::all();

        return view('performance.create', compact('athletes', 'events'));
    }

    // Store new performance
    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|exists:' . (new Athlete)->getTable() . ',athlete_id',
            'event_id'   => 'required|exists:' . (new Event)->getTable() . ',event_id',
            'score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);
        
        $performance = Performance::create($request->all());

        // Audit log
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Add Performance',
            'module' => 'Performance',
            'description' => "Added performance for athlete ID {$request->athlete_id}",
            'ip_address' => $request->ip(),
        ]);

      return redirect()->route('performance.index')->with('success', 'Performance added successfully.');
    }

    // Edit performance
    public function edit(Performance $performance)
    {
        $athletes = Athlete::all();
        $events = Event::all();

        return view('performance.edit', compact('performance', 'athletes', 'events'));
    }

    // Update performance
    public function update(Request $request, Performance $performance)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string',
        ]);

        $performance->update($request->only('score', 'remarks'));

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update Performance',
            'module' => 'Performance',
            'description' => "Updated performance ID {$performance->performance_id}",
            'ip_address' => $request->ip(),
        ]);

       return redirect()->route('performance.index')->with('success', 'Performance updated successfully.');
    }

    // Soft delete performance
    public function destroy(Performance $performance)
    {
        $performance->update(['removed' => 1]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete Performance',
            'module' => 'Performance',
            'description' => "Deleted performance ID {$performance->performance_id}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('performance.index')->with('success', 'Performance removed.');
    }
}
