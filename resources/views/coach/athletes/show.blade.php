@extends('layouts.app')
@section('title', $athlete->full_name)

@section('content')
<div x-data="{ open: false }" class="mb-6">
    {{-- Profile Picture with Status Badge --}}
    <div class="relative">
        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-300 cursor-pointer" @click="open = true">
            <img src="{{ $athlete->profile_url }}" alt="{{ $athlete->full_name }}" class="w-full h-full object-cover">
        </div>
        
        {{-- Status Badge --}}
        @if($athlete->conditions)
            @php
                $statusColors = [
                    'active' => 'bg-green-500',
                    'injured' => 'bg-red-500',
                    'graduate' => 'bg-blue-500'
                ];
                $statusText = [
                    'active' => 'Active',
                    'injured' => 'Injured',
                    'graduate' => 'Graduate'
                ];
                $color = $statusColors[$athlete->conditions] ?? 'bg-gray-500';
                $text = $statusText[$athlete->conditions] ?? ucfirst($athlete->conditions);
            @endphp
            <span class="absolute top-0 left-0 mt-1 ml-1 px-2 py-1 text-xs text-white font-semibold rounded-full {{ $color }}">
                {{ $text }}
            </span>
        @endif
    </div>

    {{-- Name --}}
    <h2 class="text-2xl font-bold mt-2">{{ $athlete->full_name }}</h2>

    {{-- Modal --}}
    <div x-show="open" x-transition.opacity
         style="display: none;"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-4 max-w-lg w-full relative">
            <button @click="open = false" class="absolute top-2 right-2 text-gray-700 text-xl">&times;</button>
            <img src="{{ $athlete->profile_url }}" alt="{{ $athlete->full_name }}" class="w-full h-auto object-contain rounded">
        </div>
    </div>
</div>

{{-- Athlete Info + Teams --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold mb-2">Athlete Info</h3>
        <p><strong>Sport:</strong> {{ $athlete->sport->sport_name }}</p>
        <p><strong>Year Level:</strong> {{ $athlete->year_level }}</p>
        <p><strong>Gender:</strong> {{ $athlete->gender }}</p>
        
        {{-- Display Status in Athlete Info section as well --}}
        <p><strong>Status:</strong> 
            @if($athlete->status)
                @php
                    $statusText = [
                        'active' => 'Active',
                        'injured' => 'Injured',
                        'graduate' => 'Graduated'
                    ];
                    $text = $statusText[$athlete->status] ?? ucfirst($athlete->status);
                @endphp
                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    @if($athlete->status === 'active') bg-green-100 text-green-800
                    @elseif($athlete->status === 'injured') bg-red-100 text-red-800
                    @elseif($athlete->status === 'graduate') bg-blue-100 text-blue-800
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ $text }}
                </span>
            @else
                <span class="text-gray-500">Not specified</span>
            @endif
        </p>
        
        <p><strong>Conditions:</strong> {{ $athlete->conditions ?? 'Not Provided' }}</p>
        <p><strong>Eligibility:</strong> {{ $athlete->eligibility_status ?? 'Not Provided' }}</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="font-bold mb-2">Teams</h3>
        @forelse($athlete->teams as $team)
            <p>{{ $team->team_name }}</p>
        @empty
            <p>No team assignments yet.</p>
        @endforelse
    </div>
</div>

{{-- Additional status-based content or warnings --}}
@if($athlete->status === 'injured')
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold text-red-700">Athlete is currently injured</span>
        </div>
        <p class="text-red-600 text-sm mt-1">Please consider this status when assigning to events or training activities.</p>
    </div>
@elseif($athlete->status === 'graduate')
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span class="font-semibold text-blue-700">Athlete has graduated</span>
        </div>
        <p class="text-blue-600 text-sm mt-1">This athlete is no longer actively participating.</p>
    </div>
@endif

{{-- Awards Section --}}
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="font-bold">Awards & Achievements</h3>
        @if(in_array(auth()->user()->role, ['coach', 'superAdmin']))
            <button @click="showAwardForm = !showAwardForm" 
                    class="px-3 py-1 bg-blue-600 text-white rounded text-sm">
                + Add Award
            </button>
        @endif
    </div>

    {{-- Add Award Form (Initially Hidden) --}}
    @if(in_array(auth()->user()->role, ['coach', 'superAdmin']))
        <div x-data="{ showAwardForm: false }">
            <div x-show="showAwardForm" x-transition class="mb-4 p-4 border rounded bg-gray-50">
                <form action="{{ route('coach.athletes.awards.store', $athlete->athlete_id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Event</label>
                        <select name="event_id" class="w-full border rounded p-2">
                            <option value="">Select Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Award Title</label>
                        <input type="text" name="title" class="w-full border rounded p-2" placeholder="e.g., Gold Medal, MVP" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded p-2" rows="3" placeholder="Describe the award/achievement"></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save Award</button>
                        <button type="button" @click="showAwardForm = false" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Awards List --}}
    @if($athlete->awards->isEmpty())
        <p class="text-gray-500 italic">No awards or achievements recorded yet.</p>
    @else
        <div class="space-y-4">
            @foreach($athlete->awards as $award)
                <div class="border rounded p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-bold text-lg text-yellow-700">{{ $award->title }}</h4>
                            @if($award->event)
                                <p class="text-sm text-gray-600">Event: {{ $award->event->event_name }}</p>
                            @endif
                            @if($award->description)
                                <p class="mt-2">{{ $award->description }}</p>
                            @endif
                        </div>
                        @if(in_array(auth()->user()->role, ['coach', 'superAdmin']))
                            <form action="{{ route('coach.athletes.awards.destroy', [$athlete->athlete_id, $award->id]) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Delete this award?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">×</button>
                            </form>
                        @endif
                    </div>
                    <div class="mt-2 text-sm text-gray-500">
                        Added on {{ $award->created_at->format('M d, Y') }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Participation History --}}
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="font-bold mb-4">Participation History</h3>
    @if($athlete->events->isEmpty())
        <p>No participation history yet.</p>
    @else
        <ul class="list-disc list-inside">
            @foreach($athlete->events as $event)
                <li>
                    <strong>{{ $event->event_name }}</strong> – {{ $event->event_date->format('M d, Y') }} 
                    @if($event->status) ({{ $event->status }}) @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>

{{-- Training Notes --}}
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <h3 class="font-bold mb-4">Training Notes</h3>
    
    @foreach($athlete->trainingNotes as $note)
        <div class="mb-2 p-3 border rounded">
            <p>{{ $note->note }}</p>
            @if($note->coach)
                <small class="text-gray-500">By {{ $note->coach->full_name }} on {{ $note->created_at->format('M d, Y') }}</small>
            @else
                <small class="text-gray-500">By System on {{ $note->created_at->format('M d, Y') }}</small>
            @endif
        </div>
    @endforeach

    {{-- Only show add note form to coaches and superAdmin --}}
    @if(in_array(auth()->user()->role, ['Coach', 'SuperAdmin']))
        <form action="{{ route('coach.athletes.notes.store', $athlete->athlete_id) }}" method="POST" class="mt-4">
            @csrf
            <textarea name="note" class="w-full border rounded p-2" rows="3" placeholder="Add a training note" required></textarea>
            <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded">Save Note</button>
        </form>
    @elseif(auth()->user()->role === 'Staff' || auth()->user()->role === 'Admin')
        <p class="text-gray-500 italic mt-4">Only coaches can add training notes.</p>
    @endif
</div>
@endsection