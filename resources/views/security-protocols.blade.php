@extends('layouts.app')

@section('title', 'Security Protocols')

@section('content')
<div class="space-y-10">

    <!-- Section: User Security Status -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">User Security Status</h2>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-3 gap-6 mb-4">
                <div class="p-4 bg-green-100 rounded text-center">
                    <h3 class="font-bold text-lg">Active Users</h3>
                    <p class="text-xl">{{ $activeUsers }}</p>
                </div>
                <div class="p-4 bg-red-100 rounded text-center">
                    <h3 class="font-bold text-lg">Inactive Users</h3>
                    <p class="text-xl">{{ $inactiveUsers }}</p>
                </div>
                <div class="p-4 bg-yellow-100 rounded text-center">
                    <h3 class="font-bold text-lg">Weak Passwords</h3>
                    <p class="text-xl">{{ $weakPasswords }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">Username</th>
                            <th class="p-2 border">Role</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Weak Password</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $user->username }}</td>
                            <td class="p-2 border">{{ $user->role }}</td>
                            <td class="p-2 border">
                                <span class="{{ $user->removed ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $user->removed ? 'Inactive' : 'Active' }}
                                </span>
                            </td>
                            <td class="p-2 border">
                                @if($user->isWeakPassword)
                                    <span class="text-yellow-700 font-bold">⚠ Weak</span>
                                @else
                                    <span class="text-green-600">✔ Strong</span>
                                @endif
                            </td>
                            <td class="p-2 border space-x-2">
                                @if(!$user->removed)
                                <form action="{{ route('security.forceReset', $user->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800">
                                        Force Reset
                                    </button>
                                </form>
                                <form action="{{ route('security.deactivateUser', $user->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Deactivate
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('security.activateUser', $user->user_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                        Reactivate
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Section: Audit Log Summary -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Audit Logs</h2>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Recent Activity</h3>
                <a href="{{ route('security.downloadLogs') }}" class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                    Download CSV
                </a>
            </div>
            <div class="overflow-x-auto max-h-[60vh]">
                <table class="w-full text-left border">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">User</th>
                            <th class="p-2 border">Action</th>
                            <th class="p-2 border">Module</th>
                            <th class="p-2 border">Description</th>
                            <th class="p-2 border">IP Address</th>
                            <th class="p-2 border">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $log->user->username ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $log->action }}</td>
                            <td class="p-2 border">{{ $log->module }}</td>
                            <td class="p-2 border">{{ $log->description }}</td>
                            <td class="p-2 border">{{ $log->ip_address }}</td>
                            <td class="p-2 border">{{ $log->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Section: Security Protocol Explanation -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Security Protocols & Compliance</h2>
        <div class="bg-white rounded-lg shadow-lg p-6 space-y-4">
            <p><strong>Protect Sensitive Data:</strong> All athlete and staff information is secured to prevent unauthorized access or data breaches.</p>
            <p><strong>Authentication & Access Control:</strong> Users have role-based access. Super Admins have full control, coaches and staff have limited permissions.</p>
            <p><strong>Prevent Data Breaches & Cyber Attacks:</strong> Passwords are hashed, SSL/TLS is enforced, and input sanitization prevents common attacks (SQLi, XSS).</p>
            <p><strong>Audit & Accountability:</strong> All actions are logged. Suspicious activity is monitored and can trigger password resets or account deactivation.</p>
            <p><strong>Compliance:</strong> Ensures the system aligns with university policies and data protection standards.</p>
        </div>
    </section>

</div>
@endsection
