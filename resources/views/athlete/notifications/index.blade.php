@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Notifications</h1>

    @if($notifications->count())
        <div class="space-y-4">
            @foreach($notifications as $notification)
                <div class="p-4 border rounded {{ $notification->read ? 'bg-gray-100' : 'bg-yellow-50' }}">
                    <h2 class="font-semibold text-lg">{{ $notification->title }}</h2>
                    <p class="text-sm text-gray-700">{{ $notification->message }}</p>
                    <span class="text-xs text-gray-500">📅 {{ $notification->created_at->diffForHumans() }}</span>

                    @if(!$notification->read && $notification->user_id === auth()->id())
                        <form action="{{ route('athlete.notifications.read', $notification->notification_id) }}" method="POST" class="mt-2">
                            @csrf
                            <button class="bg-blue-600 text-white px-3 py-1 rounded">Mark as Read</button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @else
        <p class="text-gray-600">No notifications found.</p>
    @endif
</div>
@endsection
