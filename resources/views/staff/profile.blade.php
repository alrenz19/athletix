@extends('layouts.app')

@section('title', 'Staff Profile')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Your Profile</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('staff.profile.update') }}" method="POST">
        @csrf

        <!-- Full Name -->
        <div class="mb-4">
            <label for="full_name" class="block font-semibold">Full Name</label>
            <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $staff->full_name) }}" class="w-full border p-2 rounded" required>
            @error('full_name')<span class="text-red-600">{{ $message }}</span>@enderror
        </div>

        <!-- Position -->
        <div class="mb-4">
            <label for="position" class="block font-semibold">Position</label>
            <input type="text" name="position" id="position" value="{{ old('position', $staff->position) }}" class="w-full border p-2 rounded">
            @error('position')<span class="text-red-600">{{ $message }}</span>@enderror
        </div>

        <!-- Username -->
        <div class="mb-4">
            <label for="username" class="block font-semibold">Username</label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="w-full border p-2 rounded" required>
            @error('username')<span class="text-red-600">{{ $message }}</span>@enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block font-semibold">Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" class="w-full border p-2 rounded">
            @error('password')<span class="text-red-600">{{ $message }}</span>@enderror
        </div>

        <!-- Password Confirmation -->
        <div class="mb-4">
            <label for="password_confirmation" class="block font-semibold">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="w-full border p-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Update Profile
        </button>
    </form>
</div>
@endsection
