<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index()
    {
        $userRole = Auth::user()->role;

        $announcements = Announcement::with(['poster', 'sport', 'section']) // ✅ eager load
            ->where('removed', 0)
            ->when($userRole !== 'SuperAdmin', function ($query) use ($userRole) {
                $query->where(function ($q) use ($userRole) {
                    $q->where('target', $userRole)
                    ->orWhere('target', 'All');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }
    /**
     * Store a newly created announcement.
     */
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'target' => 'required|in:All,Athletes,Coaches,Staff',
            'sport_id' => 'nullable|exists:sports,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        // Create announcement
        Announcement::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'target' => $validated['target'],
            'sport_id' => $validated['sport_id'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'posted_by' => Auth::id(), // current logged-in user
            'removed' => 0,
        ]);

        return redirect()->route('announcements.index')->with('success', 'Announcement added successfully.');
    }
}
