@extends('layouts.app')

@section('title', 'Edit Award')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Award</h1>

    <form action="{{ route('coach.awards.update', $award->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block font-semibold">Athlete</label>
            <select name="athlete_id" class="w-full border rounded p-2">
                @foreach($athletes as $athlete)
                    <option value="{{ $athlete->athlete_id }}" 
                        {{ $award->athlete_id == $athlete->athlete_id ? 'selected' : '' }}>
                        {{ $athlete->full_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Event</label>
            <select name="event_id" class="w-full border rounded p-2">
                @foreach($events as $event)
                    <option value="{{ $event->event_id }}" 
                        {{ $award->event_id == $event->event_id ? 'selected' : '' }}>
                        {{ $event->event_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Title</label>
            <input type="text" name="title" value="{{ $award->title }}" class="w-full border rounded p-2" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded p-2">{{ $award->description }}</textarea>
        </div>

        <button type="submit" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            Update
        </button>
    </form>
</div>
@endsection
