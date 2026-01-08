@extends('layouts.app')
@section('title', 'Upcoming Events')

@section('content')
<div class="overflow-x-auto bg-white rounded-lg shadow-lg p-6">

    {{-- Search + Filter Form --}}
    <form method="GET" action="{{ route('athlete.events.index') }}" class="mb-4 flex gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search events..." 
            class="border rounded p-2 flex-1"
        />

        <select name="sport" class="border rounded p-2">
            <option value="">All Sports</option>
            @foreach($sports as $sport)
                <option value="{{ $sport->sport_id }}" {{ request('sport') == $sport->sport_id ? 'selected' : '' }}>
                    {{ $sport->sport_name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
    </form>

    @if($events->isEmpty())
        <p>No upcoming events found.</p>
    @else
        <table class="w-full text-left border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Event</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Sport</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td class="p-3 border">{{ $event->event_name }}</td>
                    <td class="p-3 border">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                    <td class="p-3 border">{{ $event->sport->sport_name ?? 'N/A' }}</td>
                    <td class="p-3 border">{{ $event->location ?? 'TBA' }}</td>
                    <td class="p-3 border">
                        @php
                            $athlete = Auth::user()->athlete; // get logged-in athlete model
                            $alreadyRegistered = $event->athleteEvents
                                ->where('athlete_id', $athlete->athlete_id ?? null)
                                ->where('event_id', $event->event_id)
                                ->isNotEmpty();
                        @endphp

                        @if($alreadyRegistered)
                            {{-- Already registered → Unregister --}}
                            <form action="{{ route('athlete.events.unregister', $event->event_id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">
                                    Unregister
                                </button>
                            </form>
                        @else
                            {{-- Not registered → Register (only if TryOut event) --}}
                            @if($event->event_type === 'TryOut')
                                <form action="{{ route('athlete.events.register', $event->event_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded">
                                        Register
                                    </button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $events->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
