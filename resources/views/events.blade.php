@extends('layouts.app')

@section('title', 'Events Management')
@section('header-actions')
 <button data-modal-target="addEventModal" class="bg-amber-900 text-white px-6 py-2 rounded-lg hover:bg-amber-800 transition-colors font-semibold shadow-md hover:shadow-lg">+ Add Event</button>
@endsection

@section('content')
<div class="space-y-10">

  <!-- Events Management Section -->
  <section>
    <div class="bg-white rounded-lg shadow-lg p-6">
      <div class="flex justify-between mb-4">
        <h3 class="text-lg font-semibold">Upcoming Events</h3>
      </div>

      <!-- Scrollable Table -->
      <div class="overflow-y-auto max-h-80 border rounded">
        <table class="w-full text-left">
          <thead class="sticky top-0 bg-gray-100">
            <tr>
              <th class="p-3 border">Event Name</th>
              <th class="p-3 border">Date & Time</th>
              <th class="p-3 border">Type</th>
              <th class="p-3 border">Sport</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($events as $event)
            <tr class="bg-gray-50">
              <td class="p-3 border">{{ $event->event_name }}</td>
             <td class="p-3 border">{{ \Carbon\Carbon::parse($event->event_date)->setTimezone('Asia/Manila')->format('F d, Y \a\t h:i A') }}</td>
              <td class="p-3 border">{{ $event->event_type }}</td>
              <td class="p-3 border">{{ $event->sport->sport_name ?? 'N/A' }}</td>
              <td class="p-3 border space-x-2">
                <button data-modal-target="editEventModal{{ $event->event_id }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Edit</button>
                <form action="{{ route('events.deleteEvent', $event->event_id) }}" method="POST" class="inline-block" onsubmit="return confirmDeleteEvent();">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
                </form>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="5" class="text-center p-6 text-gray-500">
                No events found. Click <strong>+ Add Event</strong> to create a new one.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </section>

</div>

<!-- Add Event Modal -->
<div id="addEventModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-50"></div>
    </div>

    <!-- Modal panel -->
    <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <h3 class="font-bold text-lg mb-4">Add Event</h3>
        <form action="{{ route('events.storeEvent') }}" method="POST">
          @csrf
          <label class="block mb-1 font-medium">Event Name</label>
          <input type="text" name="event_name" class="w-full mb-3 p-2 border rounded" required>

          <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
              <label class="block mb-1 font-medium">Event Date</label>
              <input type="date" name="event_date" min="<?php echo date('Y-m-d'); ?>" class="w-full p-2 border rounded" required>
            </div>
            <div>
              <label class="block mb-1 font-medium">Event Time</label>
              <input type="time" name="event_time" class="w-full p-2 border rounded" required>
            </div>
          </div>

          <label class="block mb-1 font-medium">Event Type</label>
          <select name="event_type" class="w-full mb-3 p-2 border rounded" required>
            <option value="">Select Event Type</option>
            <option value="Training">Training</option>
            <option value="Competition">Competition</option>
            <option value="Meeting">Meeting</option>
            <option value="TryOut">Try Out</option>
          </select>

          <label class="block mb-1 font-medium">Location</label>
          <input type="text" name="location" class="w-full mb-3 p-2 border rounded" placeholder="Enter location">

          <label class="block mb-1 font-medium">Sport</label>
          <select name="sport_id" class="w-full mb-3 p-2 border rounded" required>
            <option value="">Select Sport</option>
            @foreach($sports as $sport)
            <option value="{{ $sport->sport_id }}">{{ $sport->sport_name }}</option>
            @endforeach
          </select>

          <div class="flex justify-end space-x-2 mt-4">
            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('addEventModal')">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Event Modals -->
@foreach($events as $event)
<div id="editEventModal{{ $event->event_id }}" class="hidden fixed inset-0 z-50 overflow-y-auto">
  <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
    <!-- Background overlay -->
    <div class="fixed inset-0 transition-opacity" aria-hidden="true">
      <div class="absolute inset-0 bg-gray-800 bg-opacity-50"></div>
    </div>

    <!-- Modal panel -->
    <div class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
      <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
        <h3 class="font-bold text-lg mb-4">Edit Event</h3>
        <form action="{{ route('events.updateEvent', $event->event_id) }}" method="POST">
          @csrf
          @method('PUT')

          <label class="block mb-1 font-medium">Event Name</label>
          <input type="text" name="event_name" value="{{ old('event_name', $event->event_name) }}" class="w-full mb-3 p-2 border rounded">

          <div class="grid grid-cols-2 gap-3 mb-3">
            <div>
              <label class="block mb-1 font-medium">Event Date</label>
              <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" class="w-full p-2 border rounded">
            </div>
            <div>
              <label class="block mb-1 font-medium">Event Time</label>
              <input type="time" name="event_time" value="{{ old('event_time', $event->event_date->format('H:i')) }}" class="w-full p-2 border rounded">
            </div>
          </div>

          <label class="block mb-1 font-medium">Event Type</label>
          <select name="event_type" class="w-full mb-3 p-2 border rounded">
            <option value="Training" @if(old('event_type', $event->event_type) == 'Training') selected @endif>Training</option>
            <option value="Competition" @if(old('event_type', $event->event_type) == 'Competition') selected @endif>Competition</option>
            <option value="Meeting" @if(old('event_type', $event->event_type) == 'Meeting') selected @endif>Meeting</option>
            <option value="TryOut" @if(old('event_type', $event->event_type) == 'TryOut') selected @endif>Try Out</option>
          </select>

          <label class="block mb-1 font-medium">Location</label>
          <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full mb-3 p-2 border rounded" placeholder="Enter location">

          <label class="block mb-1 font-medium">Sport</label>
          <select name="sport_id" class="w-full mb-3 p-2 border rounded">
            @foreach($sports as $sport)
              <option value="{{ $sport->sport_id }}" @if(old('sport_id', $event->sport_id) == $sport->sport_id) selected @endif>{{ $sport->sport_name }}</option>
            @endforeach
          </select>

          <div class="flex justify-end space-x-2 mt-4">
            <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded" onclick="closeModal('editEventModal{{ $event->event_id }}')">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach

<script>
  function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
      document.body.style.overflow = 'auto';
  }

  document.querySelectorAll('[data-modal-target]').forEach(btn => {
      btn.addEventListener('click', () => {
          const modalId = btn.getAttribute('data-modal-target');
          document.getElementById(modalId).classList.remove('hidden');
          document.body.style.overflow = 'hidden';
      });
  });

  // Close modal when clicking outside the modal content
  document.addEventListener('click', function(e) {
      if (e.target.classList.contains('bg-gray-800') || e.target.classList.contains('bg-opacity-50')) {
          const modal = e.target.closest('[id^="editEventModal"], #addEventModal');
          if (modal) {
              closeModal(modal.id);
          }
      }
  });

  function confirmDeleteEvent() {
      return confirm('Are you sure you want to delete this event? This action cannot be undone.');
  }

  // Optional: Set default time to next hour
  document.addEventListener('DOMContentLoaded', function() {
      const now = new Date();
      const nextHour = new Date(now.getTime() + 60 * 60 * 1000);
      const defaultTime = nextHour.getHours().toString().padStart(2, '0') + ':' + 
                          nextHour.getMinutes().toString().padStart(2, '0');
      
      // Set default time for all time inputs
      document.querySelectorAll('input[type="time"]').forEach(input => {
          if (!input.value) {
              input.value = defaultTime;
          }
      });
  });
</script>
@endsection