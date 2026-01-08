@extends('layouts.app')
@section('title', 'Participation History')

@section('content')
<div class="container mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h3 class="text-xl font-semibold mb-4">Participation History</h3>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('athlete.events.history') }}" class="mb-4 flex gap-2">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="Search event..." 
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

    {{-- History Table --}}
    @if($participations->isEmpty())
        <p>No participation history found.</p>
    @else
        <table class="table-auto w-full text-left border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Event</th>
                    <th class="p-3 border">Date</th>
                    <th class="p-3 border">Sport</th>
                    <th class="p-3 border">Award</th>
                    <th class="p-3 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($participations as $p)
                <tr>
                    <td class="p-3 border">{{ $p->event->event_name ?? 'N/A' }}</td>
                    <td class="p-3 border">{{ \Carbon\Carbon::parse($p->event->event_date)->format('M d, Y') }}</td>
                    <td class="p-3 border">{{ $p->event->sport->sport_name ?? 'N/A' }}</td>
                    <td class="p-3 border">
                        @if($p->title)
                            <strong>{{ $p->title }}</strong><br>
                            <small>{{ $p->description }}</small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="p-3 border">
                        <button 
                            onclick="openModal({{ $p->id }})" 
                            class="px-3 py-1 bg-indigo-600 text-white rounded">
                            View
                        </button>
                    </td>
                </tr>

                {{-- Modal --}}
                <div id="modal-{{ $p->id }}" 
                     class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg shadow-lg w-96 p-6 relative">
                        <button 
                            onclick="closeModal({{ $p->id }})" 
                            class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">✖</button>

                        <h3 class="text-lg font-semibold mb-4">{{ $p->event->event_name ?? 'N/A' }}</h3>
                        
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($p->event->event_date)->format('M d, Y') }}</p>
                        <p><strong>Location:</strong> {{ $p->event->location ?? 'TBA' }}</p>
                        <p><strong>Sport:</strong> {{ $p->event->sport->sport_name ?? 'N/A' }}</p>
                        <p><strong>Award:</strong> 
                            @if($p->title)
                                {{ $p->title }} – {{ $p->description }}
                            @else
                                None
                            @endif
                        </p>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $participations->appends(request()->query())->links() }}
        </div>
    @endif
</div>

{{-- Modal Script --}}
<script>
function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    document.getElementById('modal-' + id).classList.add('flex');
}
function closeModal(id) {
    document.getElementById('modal-' + id).classList.add('hidden');
    document.getElementById('modal-' + id).classList.remove('flex');
}
</script>
@endsection
