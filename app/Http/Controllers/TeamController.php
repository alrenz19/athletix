<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Athlete;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with(['athletes'])->where('removed', 0)->get();
        $athletes = Athlete::where('removed', 0)->get();
        return view('teams.index', compact('teams', 'athletes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name'  => 'required|string|unique:teams,team_name',
            'sport_id'   => 'nullable|integer',
            'athlete_ids'=> 'nullable|array',
        ]);

        $team = Team::create([
            'team_name' => $request->team_name,
            'sport_id'  => $request->sport_id,
            'removed'   => 0,
        ]);

        if ($request->athlete_ids) {
            $team->athletes()->sync($request->athlete_ids);
        }

        return redirect()->back()->with('success', 'Team created successfully!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'team_name'  => 'required|string|unique:teams,team_name,' . $id . ',team_id',
            'sport_id'   => 'nullable|integer',
            'athlete_ids'=> 'nullable|array',
        ]);

        $team = Team::with('athletes')->findOrFail($id);
        $team->update([
            'team_name' => $request->team_name,
            'sport_id'  => $request->sport_id,
        ]);

        if ($request->athlete_ids) {
            $team->athletes()->sync($request->athlete_ids);
        }

        return redirect()->back()->with('success', 'Team updated successfully!');
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->update(['removed' => 1]);
        return redirect()->back()->with('success', 'Team has been removed.');
    }
}
