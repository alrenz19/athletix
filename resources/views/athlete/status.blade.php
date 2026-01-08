@extends('layouts.app')
@section('title', 'Registration Status')

@section('content')
<div class="overflow-x-auto bg-white rounded-lg shadow-lg p-6">

    {{-- Search + Filter Form --}}
    <form method="GET" action="{{ route('athlete.status') }}" class="mb-4 flex gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search events..." 
            class="border rounded p-2 flex-1"
        />

        <select name="status" class="border rounded p-2">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filter</button>
    </form>

    @if($statuses->isEmpty())
        <p>No registrations found.</p>
    @else
        <table class="w-full text-left border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Event</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Sport</th>
                    <th class="p-3 border">Location</th>
                    <th class="p-3 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($statuses as $registration)
                <tr>
                    <td class="p-3 border">{{ $registration->event->event_name ?? 'N/A' }}</td>
                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($registration->event->event_date)->format('M d, Y') }}
                    </td>
                    <td class="p-3 border">{{ $registration->event->sport->sport_name ?? 'N/A' }}</td>
                    <td class="p-3 border">{{ $registration->event->location ?? 'TBA' }}</td>
                    <td class="p-3 border">
                        @if($registration->status === 'approved')
                            <span class="px-2 py-1 text-green-700 bg-green-100 rounded">Approved</span>
                        @elseif($registration->status === 'pending')
                            <span class="px-2 py-1 text-yellow-700 bg-yellow-100 rounded">Pending</span>
                        @elseif($registration->status === 'rejected')
                            <span class="px-2 py-1 text-red-700 bg-red-100 rounded">Rejected</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $statuses->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
