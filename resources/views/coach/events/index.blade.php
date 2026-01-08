@extends('layouts.app')

@section('title', 'My Events')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold mb-4">Events I Manage</h2>

    @if($events->count())
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Event</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Location</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($events as $event)
                <tr>
                    <td class="border px-4 py-2">{{ $event->event_name }}</td>
                    <td class="border px-4 py-2">{{ $event->event_date }}</td>
                    <td class="border px-4 py-2">{{ $event->event_location }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('coach.events.manage', $event->id) }}"
                           class="bg-blue-600 text-white px-3 py-1 rounded">
                           Manage Registrations
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No events found under your supervision.</p>
    @endif
</div>
@endsection
