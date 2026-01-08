@extends('layouts.app')
@section('title', 'Athlete Dashboard')

@section('content')
<div class="flex h-screen">

    <!-- Main Dashboard -->
    <main class="flex-1 p-8 bg-gray-100 ml-28"> <!-- offset for sidebar -->

        <!-- Application Status Alert -->
        @if(auth()->user()->athlete && auth()->user()->athlete->status === 'pending')
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-lg shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Application Status: Pending</strong> - Kindly update your details in the 
                        <a href="{{ route('athlete.profile.edit') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                            Settings
                        </a> 
                        to complete your registration and improve approval chances.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <!-- Welcome and Status Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Welcome Card -->
            <div class="bg-white shadow-md rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-2">👋 Welcome, {{ auth()->user()->username }}!</h3>
                <p class="text-gray-700 mb-4">Welcome to your athlete dashboard. Manage your profile, events, and registrations here.</p>
                <a href="{{ route('athlete.profile.edit') }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Update Profile
                </a>
            </div>

            <!-- Status Card -->
            <div class="bg-white shadow-md rounded-xl p-6">
                <h3 class="text-xl font-semibold mb-4">📋 Application Status</h3>
                @if(auth()->user()->athlete)
                    @php
                        $status = auth()->user()->athlete->status;
                        $statusConfig = [
                            'pending' => ['color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⏳'],
                            'approved' => ['color' => 'bg-green-100 text-green-800', 'icon' => '✅'],
                            'rejected' => ['color' => 'bg-red-100 text-red-800', 'icon' => '❌'],
                            'in review' => ['color' => 'bg-blue-100 text-blue-800', 'icon' => '🔍']
                        ];
                        $config = $statusConfig[$status] ?? ['color' => 'bg-gray-100 text-gray-800', 'icon' => '❓'];
                    @endphp
                    
                    <div class="flex items-center mb-3">
                        <span class="text-2xl mr-3">{{ $config['icon'] }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['color'] }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>
                    
                    @if($status === 'pending')
                    <p class="text-sm text-gray-600">
                        Your application is under review. Please ensure your profile information is complete and up-to-date.
                    </p>
                    @elseif($status === 'approved')
                    <p class="text-sm text-gray-600">
                        Your application has been approved! You can now register for events.
                    </p>
                    @elseif($status === 'rejected')
                    <p class="text-sm text-gray-600">
                        Your application was not approved. Please contact administration for more information.
                    </p>
                    @elseif($status === 'in review')
                    <p class="text-sm text-gray-600">
                        Your application is currently being reviewed by our team.
                    </p>
                    @endif
                @else
                    <div class="flex items-center mb-3">
                        <span class="text-2xl mr-3">❓</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            Not Registered
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">
                        Please complete your athlete registration to get started.
                    </p>
                @endif
            </div>
        </div>

        <!-- Quick Stats Cards with Redirection -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('athlete.events.history') }}" class="block">
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition cursor-pointer">
                    <h3 class="text-xl font-semibold mb-2">📅 Upcoming Events</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $upcomingEvents->count() }}</p>
                    <p class="text-gray-600 text-sm">Scheduled Events</p>
                </div>
            </a>
            
            <a href="{{ route('athlete.notifications.index') }}" class="block">
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition cursor-pointer">
                    <h3 class="text-xl font-semibold mb-2">📢 Announcements</h3>
                    <p class="text-2xl font-bold text-gray-800">{{ $announcements->count() }}</p>
                    <p class="text-gray-600 text-sm">New Updates</p>
                </div>
            </a>
            
            <a href="{{ auth()->user()->athlete && auth()->user()->athlete->status === 'approved' ? route('athlete.events.index') : '#' }}" class="block">
                <div class="bg-white shadow-md rounded-xl p-6 hover:shadow-xl transition cursor-pointer">
                    <h3 class="text-xl font-semibold mb-2">🏆 Registrations</h3>
                    <p class="text-2xl font-bold text-gray-800">
                        {{ auth()->user()->athlete && auth()->user()->athlete->status === 'approved' ? 'Available' : 'Pending' }}
                    </p>
                    <p class="text-gray-600 text-sm">
                        @if(auth()->user()->athlete && auth()->user()->athlete->status === 'approved')
                            Ready to Register
                        @else
                            Complete Approval First
                        @endif
                    </p>
                </div>
            </a>
        </div>

        <!-- Rest of your Upcoming Events and Announcements sections remain unchanged -->

    </main>
</div>

<!-- Flowbite/Alpine modal toggle script -->
<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
@endsection
