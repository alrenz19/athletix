@extends('layouts.app')

@section('title', 'Manage Event Registrations')

@section('content')
<div class="space-y-6">
    <h2 class="text-2xl font-bold mb-4">
        Manage Registrations: {{ $event->event_name }}
    </h2>

    @if($event->registrations->count())
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Athlete</th>
                    <th class="px-4 py-2">Year Level</th>
                    <th class="px-4 py-2">Medical</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($event->registrations as $registration)
                <tr>
                    <td class="border px-4 py-2">{{ $registration->athlete->full_name }}</td>
                    <td class="border px-4 py-2">{{ $registration->athlete->year_level }}</td>
                    <td class="border px-4 py-2">{{ $registration->athlete->conditions ?? 'OK' }}</td>
                    <td class="border px-4 py-2">{{ ucfirst($registration->status) }}</td>
                    <td class="border px-4 py-2">
                        @if($registration->status === 'pending')
                            <form action="{{ route('coach.updateRegistration', $registration->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button class="bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                            </form>
                            <form action="{{ route('coach.updateRegistration', $registration->id) }}" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button class="bg-red-600 text-white px-3 py-1 rounded">Reject</button>
                            </form>
                        @else
                            <span class="text-gray-600">Decision made</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No registrations yet for this event.</p>
    @endif
</div>
@endsection
