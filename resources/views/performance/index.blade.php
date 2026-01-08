@extends('layouts.app')

@section('title', 'Performance Management')
@section('header-actions')
    <button
        class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded"
        onclick="document.getElementById('addPerformanceModal').classList.remove('hidden')">
        Add Performance
    </button>
@endsection
@section('content')
<div class="space-y-10">

    <!-- Section: Performance Table -->
    <section>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Athlete Performance</h2>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-2 border">Athlete</th>
                            <th class="p-2 border">Event</th>
                            <th class="p-2 border">Score</th>
                            <th class="p-2 border">Remarks</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($performances as $performance)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 border">{{ $performance->athlete->full_name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $performance->event->event_name ?? 'N/A' }}</td>
                            <td class="p-2 border">{{ $performance->score }}</td>
                            <td class="p-2 border">{{ $performance->remarks }}</td>
                            <td class="p-2 border space-x-2">
                                <!-- Edit Button -->
                                <button
                                    class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800"
                                    onclick="openEditModal({{ $performance->performance_id }}, '{{ $performance->score }}', '{{ $performance->remarks }}')">
                                    Edit
                                </button>
                                <!-- Delete Form -->
                                <form action="{{ route('performance.destroy', $performance->performance_id) }}" method="POST" class="inline">
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

<!-- Add Performance Modal -->
<div id="addPerformanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add Performance</h3>
        <form action="{{ route('performance.store') }}" method="POST">
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
            <div class="mb-2">
                <label class="block font-medium">Score</label>
                <input type="number" name="score" step="0.01" min="0" max="100" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block font-medium">Remarks</label>
                <textarea name="remarks" class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addPerformanceModal').classList.add('hidden')" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Performance Modal -->
<div id="editPerformanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Edit Performance</h3>
        <form id="editPerformanceForm" method="POST">
            @csrf
            <div class="mb-2">
                <label class="block font-medium">Score</label>
                <input type="number" name="score" id="editScore" step="0.01" min="0" max="100" class="w-full border p-2 rounded">
            </div>
            <div class="mb-4">
                <label class="block font-medium">Remarks</label>
                <textarea name="remarks" id="editRemarks" class="w-full border p-2 rounded"></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editPerformanceModal').classList.add('hidden')" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, score, remarks) {
        document.getElementById('editPerformanceModal').classList.remove('hidden');
        document.getElementById('editScore').value = score;
        document.getElementById('editRemarks').value = remarks;
        document.getElementById('editPerformanceForm').action = '/performance/update/' + id;
    }
</script>
@endsection
