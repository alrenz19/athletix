@extends('layouts.app')

@section('title', 'Add Award')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Add Award</h1>

    <form action="{{ route('coach.awards.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold">Athlete</label>
            <select name="athlete_id" class="w-full border rounded p-2">
                @foreach($athletes as $athlete)
                    <option value="{{ $athlete->athlete_id }}">{{ $athlete->full_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Event</label>
            <select name="event_id" class="w-full border rounded p-2">
                @foreach($events as $event)
                    <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Title</label>
            <input type="text" name="title" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded p-2"></textarea>
        </div>

        <button type="submit" 
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
            Save
        </button>
    </form>
</div>
@endsection
