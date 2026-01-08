@extends('layouts.app')

@section('title', 'Notifications Management')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">System Notifications</h2>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <table class="w-full border-collapse border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-3 py-2">Date</th>
                <th class="border px-3 py-2">Title</th>
                <th class="border px-3 py-2">Message</th>
                <th class="border px-3 py-2">Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse($notifications as $note)
            <tr class="{{ $note->read ? 'bg-gray-100' : 'bg-white' }}">
                <td class="border px-3 py-2">{{ $note->created_at->format('Y-m-d H:i') }}</td>
                <td class="border px-3 py-2">{{ $note->title }}</td>
                <td class="border px-3 py-2">{{ $note->message }}</td>
                <td class="border px-3 py-2">{{ ucfirst($note->type) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4">No notifications found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
