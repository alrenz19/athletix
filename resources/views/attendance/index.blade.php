@extends('layouts.app')

@section('title', 'Attendance Management')
@section('header-actions')
    <button
        class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded"
        onclick="document.getElementById('addAttendanceModal').classList.remove('hidden')">
        Add Attendance
    </button>
@endsection

@section('content')
<div class="space-y-10">

    <!-- Section: Attendance Table -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Attendance Records</h2>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">Athlete</th>
                            <th class="p-2 border">Event</th>
                            <th class="p-2 border">Status</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $attendance->athlete->full_name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $attendance->event->event_name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $attendance->status }}</td>
                            <td class="p-2 border space-x-2">
                                <!-- Edit Button -->
                                <button
                                    class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800"
                                    onclick="openEditModal({{ $attendance->attendance_id }}, '{{ $attendance->status }}')">
                                    Edit
                                </button>
                                <!-- Delete Form -->
                                <form action="{{ route('attendance.destroy', $attendance->attendance_id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </section>
</div>

<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add Attendance</h3>
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="mb-2">
                <label class="block font-medium">Athlete</label>
                <select name="athlete_id" class="w-full border p-2 rounded">
                    @foreach(App\Models\Athlete::all() as $athlete)
                        <option value="{{ $athlete->athlete_id }}">{{ $athlete->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block font-medium">Event</label>
                <select name="event_id" class="w-full border p-2 rounded">
                    @foreach(App\Models\Event::all() as $event)
                        <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Late">Late</option>
                    <option value="Excused">Excused</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addAttendanceModal').classList.add('hidden')" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Edit Attendance</h3>
        <form id="editAttendanceForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-medium">Status</label>
                <select name="status" id="editStatus" class="w-full border p-2 rounded">
                    <option value="Present">Present</option>
                    <option value="Absent">Absent</option>
                    <option value="Late">Late</option>
                    <option value="Excused">Excused</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editAttendanceModal').classList.add('hidden')" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, status) {
        document.getElementById('editAttendanceModal').classList.remove('hidden');
        document.getElementById('editStatus').value = status;
        document.getElementById('editAttendanceForm').action = '/attendance/update/' + id;
    }
</script>
@endsection
