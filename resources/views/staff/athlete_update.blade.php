@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-white rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Update Staff Profile</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('staff.athlete.update.submit') }}" method="POST">
        @csrf
        @method('PATCH')

        <!-- Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" value="{{ old('name', $staff->name) }}" 
                   class="w-full border rounded px-3 py-2" required>
            @error('name') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Department -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <input type="text" name="department" value="{{ old('department', $staff->department) }}" 
                   class="w-full border rounded px-3 py-2">
            @error('department') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $staff->email) }}" 
                   class="w-full border rounded px-3 py-2" required>
            @error('email') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">New Password (optional)</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
            @error('password') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Save Changes
        </button>
    </form>
</div>
@endsection
