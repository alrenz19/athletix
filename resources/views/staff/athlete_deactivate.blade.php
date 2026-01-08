@extends('layouts.app')

@section('content')
<div class="p-6">

    <h2 class="text-xl font-semibold mb-4">Deactivate Athletes</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search & Filter Form -->
    <form method="GET" action="{{ route('staff.athlete.deactivate') }}" class="mb-4 flex flex-wrap gap-3 items-center">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search by name"
            class="border rounded px-3 py-2">

        <select name="team" class="border rounded px-3 py-1">
            <option value="">All Teams</option>
            @foreach($teams as $team)
                <option value="{{ $team->team_id }}" {{ request('team') == $team->team_id ? 'selected' : '' }}>
                    {{ $team->team_name }}
                </option>
            @endforeach
        </select>

        <select name="gender" class="border rounded px-3 py-2">
            <option value="">All Genders</option>
            @foreach(['Male','Female','Other'] as $gender)
                <option value="{{ $gender }}" {{ request('gender')==$gender ? 'selected' : '' }}>
                    {{ $gender }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        <a href="{{ route('staff.athlete.deactivate') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Reset</a>
    </form>



    <!-- Athlete Table -->
    <table class="min-w-full bg-white border rounded shadow">
        <thead>
            <tr>
                <th class="px-1 py-1 border">Name</th>
                <th class="px-1 py-1 border">Team</th>
                <th class="px-1 py-1 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($athletes as $athlete)
            <tr>
                <td class="px-4 py-2 border">{{ $athlete->full_name }}</td>
                <td class="px-4 py-2 border"> {{ $athlete->teams->pluck('team_name')->join(', ') }}</td>
                <td class="px-4 py-2 border">
                <form action="{{ route('staff.athlete.deactivate.submit', $athlete->athlete_id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                            Deactivate
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-4 py-2 border text-center text-gray-500">No athletes found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection
