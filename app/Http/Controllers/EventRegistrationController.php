<?php 

namespace App\Http\Controllers;
use App\Models\EventRegistration;

class EventRegistrationController extends Controller
{

    public function requestJoin($eventId)
    {
        $athlete = auth()->user()->athlete;

        EventRegistration::firstOrCreate([
            'event_id'   => $eventId,
            'athlete_id' => $athlete->athlete_id,
        ]);

        return back()->with('success', 'Request sent. Awaiting coach approval.');
    }

    public function updateRegistration(Request $request, $id)
    {
        $registration = EventRegistration::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $registration->status = $request->status;
        $registration->save();

        // Optional: notify athlete here

        return back()->with('success', 'Athlete registration updated.');
    }



}