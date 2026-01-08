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
              <th class="p-3 border">Date</th>
              <th class="p-3 border">Type</th>
              <th class="p-3 border">Sport</th>
              <th class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($events as $event)
            <tr class="bg-gray-50">
              <td class="p-3 border">{{ $event->event_name }}</td>
              <td class="p-3 border">{{ $event->event_date }}</td>
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

            <!-- Edit Event Modal -->
            <div id="editEventModal{{ $event->event_id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h3 class="font-bold text-lg mb-4">Edit Event</h3>
                    <form action="{{ route('events.updateEvent', $event->event_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <label>Event Name</label>
                        <input type="text" name="event_name" value="{{ old('event_name', $event->event_name) }}" class="w-full mb-2 p-2 border rounded">

                        <label>Event Date</label>
                        <input type="date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" class="w-full mb-2 p-2 border rounded">

                        <label>Event Type</label>
                        <select name="event_type" class="w-full mb-2 p-2 border rounded">
                            <option value="Training" @if(old('event_type', $event->event_type) == 'Training') selected @endif>Training</option>
                            <option value="Competition" @if(old('event_type', $event->event_type) == 'Competition') selected @endif>Competition</option>
                            <option value="Meeting" @if(old('event_type', $event->event_type) == 'Meeting') selected @endif>Meeting</option>
                            <option value="TryOut" @if(old('event_type', $event->event_type) == 'TryOut') selected @endif>Try Out</option>
                        </select>

                        <label>Location</label>
                        <input type="text" name="location" value="{{ old('location', $event->location) }}" class="w-full mb-2 p-2 border rounded" placeholder="Enter location">

                        <label>Sport</label>
                        <select name="sport_id" class="w-full mb-2 p-2 border rounded">
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
<div id="addEventModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
  <div class="bg-white p-6 rounded-lg w-1/3">
    <h3 class="font-bold text-lg mb-4">Add Event</h3>
    <form action="{{ route('events.storeEvent') }}" method="POST">
      @csrf
      <label>Event Name</label>
      <input type="text" name="event_name" class="w-full mb-2 p-2 border rounded">

      <label>Event Date</label>
      <input type="date" name="event_date" class="w-full mb-2 p-2 border rounded">

      <label>Event Type</label>
      <select name="event_type" class="w-full mb-2 p-2 border rounded">
        <option value="Training">Training</option>
        <option value="Competition">Competition</option>
        <option value="Meeting">Meeting</option>
         <option value="TryOut">Try Out</option>
      </select>

      <label>Location</label>
      <input type="text" name="location" class="w-full mb-2 p-2 border rounded" placeholder="Enter location">


      <label>Sport</label>
      <select name="sport_id" class="w-full mb-2 p-2 border rounded">
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

<script>
  function closeModal(id) {
      document.getElementById(id).classList.add('hidden');
  }

  document.querySelectorAll('[data-modal-target]').forEach(btn => {
      btn.addEventListener('click', () => {
          document.getElementById(btn.getAttribute('data-modal-target')).classList.remove('hidden');
      });
  });

  function confirmDeleteEvent() {
      return confirm('Are you sure you want to delete this event? This action cannot be undone.');
  }
</script>
@endsection
