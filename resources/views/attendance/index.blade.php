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
<div class="space-y-6">
    <!-- Filter Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Filter Attendance Records</h2>
            
            <form method="GET" action="{{ route('attendance') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full border rounded px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                    <select name="event_id" class="w-full border rounded px-3 py-2">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}" 
                                    {{ request('event_id') == $event->event_id ? 'selected' : '' }}>
                                {{ $event->event_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sport</label>
                    <select name="sport_id" class="w-full border rounded px-3 py-2">
                        <option value="">All Sports</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}" 
                                    {{ request('sport_id') == $sport->sport_id ? 'selected' : '' }}>
                                {{ $sport->sport_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full border rounded px-3 py-2">
                        <option value="">All Status</option>
                        <option value="Present" {{ request('status') == 'Present' ? 'selected' : '' }}>Present</option>
                        <option value="Absent" {{ request('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                        <option value="Late" {{ request('status') == 'Late' ? 'selected' : '' }}>Late</option>
                        <option value="Excused" {{ request('status') == 'Excused' ? 'selected' : '' }}>Excused</option>
                    </select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" 
                            class="w-full bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                        Filter
                    </button>
                    <a href="{{ route('attendance') }}" 
                       class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-center">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Attendance Table Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Attendance Records</h2>
                <div class="text-sm text-gray-600">
                    Showing {{ $attendances->count() }} record(s)
                </div>
            </div>
            
            <!-- Table with relative positioning for dropdowns -->
            <div class="overflow-x-auto relative">
                <table class="w-full text-left border">
                    <thead class="bg-gray-100 sticky top-0">
                        <tr>
                            <th class="p-3 border">Date</th>
                            <th class="p-3 border">Athlete</th>
                            <th class="p-3 border">Sport</th>
                            <th class="p-3 border">Event</th>
                            <th class="p-3 border">Status</th>
                            <th class="p-3 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            @php
                                $attendanceDate = \Carbon\Carbon::parse($attendance->event_date);
                                $oneWeekAgo = \Carbon\Carbon::now()->subWeek();
                                $canEdit = ($attendanceDate >= $oneWeekAgo) || auth()->user()->role === 'SuperAdmin';
                            @endphp
                            <tr class="hover:bg-gray-50 attendance-row" 
                                data-attendance-id="{{ $attendance->attendance_id }}"
                                data-original-status="{{ $attendance->status }}"
                                id="row-{{ $attendance->attendance_id }}">
                                <td class="p-3 border">
                                    {{ $attendanceDate->format('M d, Y') }}
                                    @if(!$canEdit)
                                        <span class="text-xs text-gray-500 block">Read-only</span>
                                    @endif
                                </td>
                                <td class="p-3 border">
                                    <div class="font-medium">{{ $attendance->athlete->full_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendance->athlete->student_id ?? '' }}</div>
                                </td>
                                <td class="p-3 border">
                                    {{ $attendance->athlete->sport->sport_name ?? 'N/A' }}
                                </td>
                                <td class="p-3 border">
                                    <div class="font-medium">{{ $attendance->event->event_name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">
                                        {{ optional($attendance->event->event_date)->format('M d, Y') ?? '' }}
                                    </div>
                                </td>
                                <td class="p-3 border">
                                    <span class="px-3 py-1 rounded-full text-sm {{ getStatusClass($attendance->status) }}">
                                        {{ $attendance->status }}
                                    </span>
                                </td>
                                <td class="p-3 border relative">
                                    @if($canEdit)
                                        <div class="relative inline-block">
                                            <button type="button" 
                                                    class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-3 py-1 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
                                                    onclick="toggleDropdown({{ $attendance->attendance_id }})">
                                                Set Status
                                                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                            
                                            <!-- Dropdown menu - positioned absolutely to page -->
                                            <div id="dropdown-{{ $attendance->attendance_id }}" 
                                                 class="hidden absolute left-0 mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                                                 style="position: fixed;">
                                                <div class="py-1" role="menu">
                                                    <button onclick="updateStatus({{ $attendance->attendance_id }}, 'Present'); hideDropdown({{ $attendance->attendance_id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $attendance->status == 'Present' ? 'bg-green-50' : '' }}"
                                                            role="menuitem">
                                                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                                        Present
                                                    </button>
                                                    <button onclick="updateStatus({{ $attendance->attendance_id }}, 'Late'); hideDropdown({{ $attendance->attendance_id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $attendance->status == 'Late' ? 'bg-yellow-50' : '' }}"
                                                            role="menuitem">
                                                        <span class="inline-block w-2 h-2 rounded-full bg-yellow-500 mr-2"></span>
                                                        Late
                                                    </button>
                                                    <button onclick="updateStatus({{ $attendance->attendance_id }}, 'Absent'); hideDropdown({{ $attendance->attendance_id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $attendance->status == 'Absent' ? 'bg-red-50' : '' }}"
                                                            role="menuitem">
                                                        <span class="inline-block w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                                        Absent
                                                    </button>
                                                    <button onclick="updateStatus({{ $attendance->attendance_id }}, 'Excused'); hideDropdown({{ $attendance->attendance_id }})"
                                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $attendance->status == 'Excused' ? 'bg-blue-50' : '' }}"
                                                            role="menuitem">
                                                        <span class="inline-block w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                                        Excused
                                                    </button>
                                                    <div class="border-t border-gray-100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-500 text-sm">Read-only</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        
                        @if($attendances->count() == 0)
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No attendance records found.
                                    @if(request()->hasAny(['date', 'event_id', 'sport_id', 'status']))
                                        Try changing your filters.
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            
            <!-- Change Indicator -->
            <div id="changeIndicator" class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-amber-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-amber-700 font-medium">
                            You have <span id="changeCount">0</span> unsaved change(s)
                        </span>
                    </div>
                    <div class="space-x-2">
                        <button onclick="discardChanges()" 
                                class="px-3 py-1 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Discard
                        </button>
                        <button id="saveAllBtn" onclick="saveAllAttendance()" 
                                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Add Attendance Modal -->
<div id="addAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add Attendance</h3>
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block font-medium mb-1">Date</label>
                <input type="date" name="event_date" class="w-full border p-2 rounded" required 
                       value="{{ old('event_date', date('Y-m-d')) }}">
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Athlete</label>
                <select name="athlete_id" class="w-full border p-2 rounded" required>
                    <option value="">Select Athlete</option>
                    @foreach(App\Models\Athlete::where('removed', false)->get() as $athlete)
                        <option value="{{ $athlete->athlete_id }}" {{ old('athlete_id') == $athlete->athlete_id ? 'selected' : '' }}>
                            {{ $athlete->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Event</label>
                <select name="event_id" class="w-full border p-2 rounded" required>
                    <option value="">Select Event</option>
                    @foreach(App\Models\Event::where('removed', false)->get() as $event)
                        <option value="{{ $event->event_id }}" {{ old('event_id') == $event->event_id ? 'selected' : '' }}>
                            {{ $event->event_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block font-medium mb-1">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="Present" {{ old('status') == 'Present' ? 'selected' : '' }}>Present</option>
                    <option value="Late" {{ old('status') == 'Late' ? 'selected' : '' }}>Late</option>
                    <option value="Absent" {{ old('status') == 'Absent' ? 'selected' : '' }}>Absent</option>
                    <option value="Excused" {{ old('status') == 'Excused' ? 'selected' : '' }}>Excused</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4 text-red-600">Confirm Delete</h3>
        <p class="mb-2" id="deleteConfirmationText"></p>
        <p class="mb-4 text-sm text-gray-600">This action cannot be undone.</p>
        <form id="deleteForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="attendance_id" id="deleteAttendanceId">
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeDeleteModal()" 
                        class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let changedAttendances = new Map(); // Map to store attendance_id -> new_status
    let isSuperAdmin = {{ auth()->user()->role === 'SuperAdmin' ? 'true' : 'false' }};
    let activeDropdown = null;
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize change indicator only if elements exist
        const changeIndicator = document.getElementById('changeIndicator');
        if (changeIndicator) {
            updateChangeIndicator();
        }
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative.inline-block') && activeDropdown) {
                hideDropdown(activeDropdown);
            }
        });
    });
    
    function toggleDropdown(attendanceId) {
        // Hide any active dropdown first
        if (activeDropdown && activeDropdown !== attendanceId) {
            hideDropdown(activeDropdown);
        }
        
        const dropdown = document.getElementById('dropdown-' + attendanceId);
        const button = document.querySelector(`#row-${attendanceId} button`);
        
        if (!dropdown || !button) return;
        
        if (dropdown.classList.contains('hidden')) {
            // Calculate position
            const buttonRect = button.getBoundingClientRect();
            const viewportHeight = window.innerHeight;
            
            // Position the dropdown below the button
            dropdown.style.position = 'fixed';
            dropdown.style.left = buttonRect.left + 'px';
            
            // Check if dropdown would overflow bottom of viewport
            const dropdownHeight = 200; // Approximate dropdown height
            const spaceBelow = viewportHeight - buttonRect.bottom;
            
            if (spaceBelow < dropdownHeight && buttonRect.top > dropdownHeight) {
                // Position above the button
                dropdown.style.top = (buttonRect.top - dropdownHeight) + 'px';
            } else {
                // Position below the button
                dropdown.style.top = (buttonRect.bottom + 5) + 'px';
            }
            
            dropdown.classList.remove('hidden');
            activeDropdown = attendanceId;
        } else {
            dropdown.classList.add('hidden');
            activeDropdown = null;
        }
    }
    
    function hideDropdown(attendanceId) {
        const dropdown = document.getElementById('dropdown-' + attendanceId);
        if (dropdown) {
            dropdown.classList.add('hidden');
        }
        if (activeDropdown === attendanceId) {
            activeDropdown = null;
        }
    }
    
    function updateStatus(attendanceId, newStatus) {
        const row = document.querySelector(`[data-attendance-id="${attendanceId}"]`);
        if (!row) {
            console.error('Row not found for attendance ID:', attendanceId);
            return;
        }
        
        const originalStatus = row.dataset.originalStatus;
        
        // Check if date is editable
        const dateCell = row.querySelector('td:first-child');
        if (!dateCell) {
            console.error('Date cell not found for row:', attendanceId);
            return;
        }
        
        const isReadOnly = dateCell.textContent.includes('Read-only');
        
        if (isReadOnly && !isSuperAdmin) {
            alert('Cannot edit attendance for dates older than one week');
            return;
        }
        
        // Update the status display in the table
        const statusCell = row.querySelector('td:nth-child(5) span');
        if (statusCell) {
            statusCell.textContent = newStatus;
            statusCell.className = `px-3 py-1 rounded-full text-sm ${getStatusClass(newStatus)}`;
        }
        
        // Highlight the row to indicate change
        if (newStatus !== originalStatus) {
            row.classList.add('bg-blue-50');
            changedAttendances.set(attendanceId, newStatus);
        } else {
            row.classList.remove('bg-blue-50');
            changedAttendances.delete(attendanceId);
        }
        
        // Update change indicator
        updateChangeIndicator();
    }
    
    function updateChangeIndicator() {
        const changeIndicator = document.getElementById('changeIndicator');
        const changeCount = document.getElementById('changeCount');
        const saveAllBtn = document.getElementById('saveAllBtn');
        
        // Check if elements exist before accessing them
        if (!changeIndicator || !changeCount) {
            console.warn('Change indicator elements not found');
            return;
        }
        
        if (changedAttendances.size > 0) {
            changeCount.textContent = changedAttendances.size;
            changeIndicator.classList.remove('hidden');
            if (saveAllBtn) {
                saveAllBtn.classList.remove('hidden');
            }
        } else {
            changeIndicator.classList.add('hidden');
            if (saveAllBtn) {
                saveAllBtn.classList.add('hidden');
            }
        }
    }
    
    async function saveAllAttendance() {
        if (changedAttendances.size === 0) {
            showNotification('No changes to save', 'info');
            return;
        }
        
        const attendances = Array.from(changedAttendances.entries()).map(([attendanceId, status]) => ({
            attendance_id: attendanceId,
            status: status
        }));
        
        try {
            // Get CSRF token with null check
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) {
                throw new Error('CSRF token not found. Please refresh the page.');
            }
            
            const csrfToken = csrfMeta.getAttribute('content');
            
            const response = await fetch('{{ route("attendance.bulk-update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ attendances: attendances })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                
                // Update original status for all changed rows with null checks
                attendances.forEach(({attendance_id, status}) => {
                    const row = document.querySelector(`[data-attendance-id="${attendance_id}"]`);
                    if (row) {
                        row.dataset.originalStatus = status;
                        row.classList.remove('bg-blue-50');
                    }
                });
                
                // Clear changed attendances
                changedAttendances.clear();
                updateChangeIndicator();
                
            } else {
                throw new Error(data.message || 'Failed to save attendance');
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
            showNotification('Failed to save attendance: ' + error.message, 'error');
        }
    }
    
    function discardChanges() {
        // Reset all changed rows to original status with null checks
        changedAttendances.forEach((newStatus, attendanceId) => {
            const row = document.querySelector(`[data-attendance-id="${attendanceId}"]`);
            if (!row) return;
            
            const originalStatus = row.dataset.originalStatus;
            
            // Update the status display with null check
            const statusCell = row.querySelector('td:nth-child(5) span');
            if (statusCell) {
                statusCell.textContent = originalStatus;
                statusCell.className = `px-3 py-1 rounded-full text-sm ${getStatusClass(originalStatus)}`;
            }
            
            // Remove highlight
            row.classList.remove('bg-blue-50');
        });
        
        // Clear changed attendances
        changedAttendances.clear();
        updateChangeIndicator();
        
        showNotification('Changes discarded', 'info');
    }
    
    function confirmDelete(attendanceId, athleteName, eventName) {
        // Check if date is editable
        const row = document.querySelector(`[data-attendance-id="${attendanceId}"]`);
        if (!row) return;
        
        const dateCell = row.querySelector('td:first-child');
        if (!dateCell) return;
        
        const isReadOnly = dateCell.textContent.includes('Read-only');
        
        if (isReadOnly && !isSuperAdmin) {
            alert('Cannot delete attendance for dates older than one week');
            return;
        }
        
        document.getElementById('deleteAttendanceId').value = attendanceId;
        document.getElementById('deleteForm').action = `/attendance/delete/${attendanceId}`;
        document.getElementById('deleteConfirmationText').textContent = 
            `Are you sure you want to delete attendance record for "${athleteName}" in event "${eventName}"?`;
        
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeAddModal() {
        const modal = document.getElementById('addAttendanceModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    function getStatusClass(status) {
        switch(status) {
            case 'Present': return 'bg-green-100 text-green-800';
            case 'Late': return 'bg-yellow-100 text-yellow-800';
            case 'Absent': return 'bg-red-100 text-red-800';
            case 'Excused': return 'bg-blue-100 text-blue-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            type === 'info' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white'
        }`;
        notification.textContent = message;
        notification.id = 'temp-notification-' + Date.now();
        
        // Add close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.className = 'ml-4 text-white hover:text-gray-200';
        closeBtn.onclick = function() {
            notification.remove();
        };
        notification.appendChild(closeBtn);
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            if (notification && notification.parentNode) {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100px)';
                setTimeout(() => {
                    if (notification && notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }
        }, 3000);
    }
</script>

<?php
// Helper functions for PHP
function getStatusClass($status) {
    switch($status) {
        case 'Present': return 'bg-green-100 text-green-800';
        case 'Late': return 'bg-yellow-100 text-yellow-800';
        case 'Absent': return 'bg-red-100 text-red-800';
        case 'Excused': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
?>
@endsection