@extends('layouts.app')
@section('title', 'Attendance & Performance')

@section('content')
<div class="p-6 bg-white rounded-lg shadow-lg">

    {{-- Search & Filter Form --}}
    <form method="GET" action="{{ route('coach.attendance.index') }}" class="mb-4 flex flex-wrap gap-3 items-center">

        {{-- Search by athlete name --}}
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search by athlete name"
               class="border rounded px-3 py-2">

        {{-- Filter Dropdown --}}
        <select name="event_id" class="border rounded px-3 py-2">
            <option value="">All Events</option>
            @foreach($allEvents as $eventOption)
                <option value="{{ $eventOption->event_id }}" {{ $selectedEvent == $eventOption->event_id ? 'selected' : '' }}>
                    {{ $eventOption->event_name }} ({{ $eventOption->event_date }})
                </option>
            @endforeach
        </select>
        {{-- Buttons --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('coach.attendance.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Reset</a>
    </form>

    @if(session('success'))
        <div class="mb-4 p-2 bg-green-200 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Attendance & Performance Table --}}
    <table class="w-full border mb-4">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Athlete</th>
                <th class="p-2 border">Event</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Score</th>
                <th class="p-2 border">Remarks</th>
                <th class="p-2 border">Action</th>
            </tr>
        </thead>
        <tbody class="alig-center">
            @forelse($events as $event)
                        @foreach($athletes as $athlete)
                            @php
                                $attendance = $athlete->attendances->firstWhere('event_id', $event->event_id);
                                $performance = $athlete->performances->firstWhere('event_id', $event->event_id);
                            @endphp

                            @if($athlete->events->contains($event->event_id))
                            <tr>
                                <td>{{ $athlete->full_name }}</td>
                                <td>{{ $event->event_name }} ({{ $event->event_date }})</td>
                                <form method="POST" action="{{ route('coach.attendance.store') }}">
                                    @csrf
                                    <input type="hidden" name="athlete_id" value="{{ $athlete->athlete_id }}">
                                    <input type="hidden" name="event_id" value="{{ $event->event_id }}">
                                    <td>
                                        <select name="status">
                                            @foreach(['Present','Absent','Late','Excused'] as $status)
                                                <option value="{{ $status }}" {{ $attendance?->status === $status ? 'selected' : '' }}>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="score" value="{{ $performance?->score ?? '' }}" min="0" max="100">
                                    </td>
                                    <td>
                                        <input type="text" name="remarks" value="{{ $performance?->remarks ?? '' }}">
                                    </td>
                                    <td class="p-2 border">
                                        <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Save</button>
                                    </td>
                                </form>
                            </tr>
                            @endif
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No athletes found for the selected event(s).</td>
                    </tr>
                    @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $athletes->links() }}
</div>
@endsection
