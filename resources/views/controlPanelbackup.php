@extends('layouts.app')

@section('title', 'Control Panel')

@section('content')
  <div class="space-y-10">

    <!-- Section: User & Role Management -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">User & Role Management</h2>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
          <h3 class="text-lg font-semibold">System Users</h3>
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add User</button>
        </div>
        <table class="w-full text-left border">
          <thead>
            <tr class="bg-gray-100">
              <th class="p-3 border">Name</th>
              <th class="p-3 border">Role</th>
              <th class="p-3 border">Status</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <!-- Example row -->
            <tr>
              <td class="p-3 border">John Doe</td>
              <td class="p-3 border">Coach</td>
              <td class="p-3 border text-green-600">Active</td>
              <td class="p-3 border space-x-2">
                <button class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                <button class="px-3 py-1 bg-red-500 text-white rounded">Deactivate</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Section: Team & Athlete Structure -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Teams & Athletes</h2>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
          <h3 class="text-lg font-semibold">Teams</h3>
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add Team</button>
        </div>
        <ul class="space-y-2">
          <li class="flex justify-between bg-gray-50 p-3 rounded-lg">
            <span>Basketball Team A</span>
            <button class="text-sm text-blue-600 hover:underline">Manage</button>
          </li>
        </ul>
      </div>
    </section>

    <!-- Section: Performance & Attendance Settings -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Performance & Attendance Settings</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-semibold mb-4">Performance Criteria</h3>
          <button class="bg-green-500 text-white px-4 py-2 rounded-lg">Edit Criteria</button>
        </div>
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-semibold mb-4">Attendance Rules</h3>
          <button class="bg-green-500 text-white px-4 py-2 rounded-lg">Edit Rules</button>
        </div>
      </div>
    </section>

    <!-- Section: Events Management -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Events Management</h2>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between mb-4">
          <h3 class="text-lg font-semibold">Upcoming Events</h3>
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add Event</button>
        </div>
        <ul class="space-y-2">
          <li class="flex justify-between bg-gray-50 p-3 rounded-lg">
            <span>Training Camp - Sept 10, 2025</span>
            <button class="text-sm text-blue-600 hover:underline">Edit</button>
          </li>
        </ul>
      </div>
    </section>

    <!-- Section: System Settings -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">System Settings</h2>
      <div class="bg-white rounded-lg shadow-lg p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <button class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">⚙️ General Settings</button>
        <button class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">🔔 Notifications</button>
        <button class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">📂 Data Backup</button>
        <button class="bg-gray-800 text-white px-4 py-6 rounded-lg shadow">📑 Audit Logs</button>
      </div>
    </section>

    <!-- Section: Reports -->
    <section>
      <h2 class="text-2xl font-bold text-gray-800 mb-4">Reports</h2>
      <div class="bg-white rounded-lg shadow-lg p-6">
        <p class="mb-4">Generate and export system-wide reports:</p>
        <div class="flex space-x-4">
          <button class="bg-green-500 text-white px-4 py-2 rounded-lg">Export CSV</button>
          <button class="bg-blue-500 text-white px-4 py-2 rounded-lg">Export PDF</button>
        </div>
      </div>
    </section>

  </div>
@endsection
