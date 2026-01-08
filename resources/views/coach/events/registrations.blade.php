@extends('layouts.app')

@section('title', 'Event Registrations Management')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Registrations - {{ $heading }}</h1>

    <!-- Filter by Sport -->
    <form method="GET" class="mb-4">
        <select name="sport" onchange="this.form.submit()" class="border rounded p-2">
            <option value="all" {{ request('sport') === 'all' ? 'selected' : '' }}>All Sports</option>
            @foreach($sports as $sport)
                <option value="{{ $sport->sport_id }}" {{ request('sport') == $sport->sport_id ? 'selected' : '' }}>
                    {{ $sport->sport_name }}
                </option>
            @endforeach
        </select>
    </form>

    <table class="min-w-full bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2">Athlete</th>
                <th class="px-4 py-2">Event</th>
                <th class="px-4 py-2">Status</th>
                <th class="px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $reg)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $reg->athlete->full_name }}</td>
                <td class="px-4 py-2">{{ $reg->event->event_name }}</td>
                <td class="px-4 py-2">{{ ucfirst($reg->status) }}</td>
                <td class="px-4 py-2 space-x-2">
                    @if($reg->status == 'pending')
                    <form action="{{ route('coach.events.registrations.approve', [$reg->event, $reg->athlete]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded">Approve</button>
                    </form>

                    <form action="{{ route('coach.events.registrations.reject', [$reg->event, $reg->athlete]) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded">Reject</button>
                    </form>
                    @else
                        <span class="text-gray-500">No action</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
