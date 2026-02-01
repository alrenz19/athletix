@extends('layouts.app')
@section('title', 'Attendance & Performance Calendar')

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Filter Attendance Records</h2>
            
            <form method="GET" action="{{ route('coach.attendance.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full border rounded px-3 py-2">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                    <select name="event_id" class="w-full border rounded px-3 py-2">
                        <option value="">All Events</option>
                        @foreach($allEvents as $event)
                            <option value="{{ $event->event_id }}" 
                                    {{ request('event_id') == $event->event_id ? 'selected' : '' }}>
                                {{ $event->event_name }} ({{ $event->event_date->format('M d, Y') }})
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
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                        Filter
                    </button>
                    <a href="{{ route('coach.attendance.index') }}" 
                       class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded text-center">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!-- Change Indicator (Moved to top for visibility) -->
    <div id="changeIndicator" class="p-3 bg-amber-50 border border-amber-200 rounded-lg hidden">
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
                <button id="saveAllBtn" onclick="saveAllChanges()" 
                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Attendance & Performance Table Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Attendance & Performance Records</h2>
                <div class="text-sm text-gray-600">
                    @if($events->count() > 0)
                        Showing {{ $events->count() }} event(s) with attendance records
                    @else
                        No attendance records found
                    @endif
                </div>
            </div>
            
            @if($events->count() > 0)
                <!-- Table with relative positioning -->
                <div class="overflow-x-auto relative">
                    <table class="w-full text-left border">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="p-3 border">Date</th>
                                <th class="p-3 border">Athlete</th>
                                <th class="p-3 border">Event</th>
                                <th class="p-3 border">Status</th>
                                <th class="p-3 border">Score</th>
                                <th class="p-3 border">Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($events as $event)
                                @foreach($athletes as $athlete)
                                    @if($athlete->events->contains($event->event_id))
                                        @php
                                            $attendance = $athlete->attendances->firstWhere('event_id', $event->event_id);
                                            $performance = $athlete->performances->firstWhere('event_id', $event->event_id);
                                            $rowId = "row-{$athlete->athlete_id}-{$event->event_id}";
                                            $attendanceDate = \Carbon\Carbon::parse($event->event_date);
                                            $oneWeekAgo = \Carbon\Carbon::now()->subWeek();
                                            $canEdit = ($attendanceDate >= $oneWeekAgo);
                                        @endphp
                                        <tr class="hover:bg-gray-50 data-row" 
                                            id="{{ $rowId }}"
                                            data-athlete-id="{{ $athlete->athlete_id }}"
                                            data-event-id="{{ $event->event_id }}"
                                            data-original-status="{{ $attendance?->status ?? '' }}"
                                            data-original-score="{{ $performance?->score ?? '' }}"
                                            data-original-remarks="{{ $performance?->remarks ?? '' }}">
                                            <td class="p-3 border">
                                                <div class="font-medium">{{ $athlete->full_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $athlete->student_id ?? '' }}</div>
                                            </td>
                                            <td class="p-3 border">
                                                <div class="font-medium">{{ $event->event_name }}</div>
                                                <div class="text-sm text-gray-500">{{ $event->location ?? 'No location' }}</div>
                                            </td>
                                            <td class="p-3 border">
                                                @if($canEdit)
                                                    <select name="status" 
                                                            class="status-select w-full border rounded px-2 py-1"
                                                            onchange="updateRow('{{ $rowId }}')"
                                                            data-original="{{ $attendance?->status ?? '' }}">
                                                        @foreach(['Present','Absent','Late','Excused'] as $status)
                                                            <option value="{{ $status }}" {{ ($attendance?->status ?? '') === $status ? 'selected' : '' }}>
                                                                {{ $status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <span class="px-3 py-1 rounded-full text-sm {{ getStatusClass($attendance?->status ?? '') }}">
                                                        {{ $attendance?->status ?? 'Not Set' }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="p-3 border">
                                                @if($canEdit)
                                                    <input type="number" 
                                                           name="score" 
                                                           value="{{ $performance?->score ?? '' }}" 
                                                           min="0" 
                                                           max="100"
                                                           class="score-input w-full border rounded px-2 py-1"
                                                           onchange="updateRow('{{ $rowId }}')"
                                                           data-original="{{ $performance?->score ?? '' }}"
                                                           oninput="updateRow('{{ $rowId }}')">
                                                @else
                                                    {{ $performance?->score ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td class="p-3 border">
                                                @if($canEdit)
                                                    <input type="text" 
                                                           name="remarks" 
                                                           value="{{ $performance?->remarks ?? '' }}"
                                                           class="remarks-input w-full border rounded px-2 py-1"
                                                           onchange="updateRow('{{ $rowId }}')"
                                                           data-original="{{ $performance?->remarks ?? '' }}"
                                                           oninput="updateRow('{{ $rowId }}')">
                                                @else
                                                    {{ $performance?->remarks ?? 'N/A' }}
                                                @endif
                                            </td>
                                            <td class="p-3 border">
                                                @if($canEdit)
                                                    <button type="button" 
                                                            onclick="saveSingleRow('{{ $rowId }}')"
                                                            class="save-single-btn px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                                                        Save
                                                    </button>
                                                @else
                                                    <span class="text-gray-500 text-sm">Read-only</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6">
                    {{ $athletes->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg mb-4">No attendance records found.</p>
                    @if(request()->hasAny(['date', 'event_id', 'status']))
                        <p class="text-gray-600 mb-2">Try changing your filters or:</p>
                    @endif
                    <a href="{{ route('coach.attendance.index') }}" 
                       class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        View All Records
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>

<script>
let changedRows = new Map(); // Map to store rowId -> {changes}
let isSubmitting = false;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    console.log('Page loaded, initializing change tracking...');
    
    // Initialize change indicator
    updateChangeIndicator();
    
    // Add event listeners for keyboard shortcuts
    document.addEventListener('keydown', function(event) {
        // Ctrl+S or Cmd+S to save changes
        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
            event.preventDefault();
            saveAllChanges();
        }
        
        // Escape to discard changes
        if (event.key === 'Escape' && changedRows.size > 0) {
            event.preventDefault();
            if (confirm('Discard all unsaved changes?')) {
                discardChanges();
            }
        }
    });
    
    // Add input event listeners to all editable fields
    document.querySelectorAll('.status-select, .score-input, .remarks-input').forEach(input => {
        // Remove existing listeners to avoid duplicates
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
        
        // Add event listeners
        if (newInput.classList.contains('status-select')) {
            newInput.addEventListener('change', function() {
                const rowId = this.closest('tr').id;
                if (rowId) {
                    updateRow(rowId);
                }
            });
        } else {
            newInput.addEventListener('input', function() {
                const rowId = this.closest('tr').id;
                if (rowId) {
                    updateRow(rowId);
                }
            });
            newInput.addEventListener('change', function() {
                const rowId = this.closest('tr').id;
                if (rowId) {
                    updateRow(rowId);
                }
            });
        }
    });
    
    // Debug: Log all editable rows
    const editableRows = document.querySelectorAll('.data-row .save-single-btn');
    console.log(`Found ${editableRows.length} editable rows`);
});

function updateRow(rowId) {
    console.log(`Updating row: ${rowId}`);
    const row = document.getElementById(rowId);
    if (!row) {
        console.error('Row not found:', rowId);
        return;
    }
    
    const athleteId = row.dataset.athleteId;
    const eventId = row.dataset.eventId;
    const originalStatus = row.dataset.originalStatus || '';
    const originalScore = row.dataset.originalScore || '';
    const originalRemarks = row.dataset.originalRemarks || '';
    
    // Get current values
    const statusSelect = row.querySelector('.status-select');
    const scoreInput = row.querySelector('.score-input');
    const remarksInput = row.querySelector('.remarks-input');
    
    // Check if row is editable (has save button)
    const saveBtn = row.querySelector('.save-single-btn');
    if (!saveBtn) {
        console.log('Row is not editable, skipping:', rowId);
        return;
    }
    
    if (!statusSelect && !scoreInput && !remarksInput) {
        console.error('No input elements found in row:', rowId);
        return;
    }
    
    const currentStatus = statusSelect ? statusSelect.value : '';
    const currentScore = scoreInput ? scoreInput.value.trim() : '';
    const currentRemarks = remarksInput ? remarksInput.value.trim() : '';
    
    console.log('Original values:', { originalStatus, originalScore, originalRemarks });
    console.log('Current values:', { currentStatus, currentScore, currentRemarks });
    
    // Check if any value has changed
    const hasChanged = 
        currentStatus !== originalStatus ||
        currentScore !== originalScore ||
        currentRemarks !== originalRemarks;
    
    console.log('Has changed?', hasChanged);
    
    if (hasChanged) {
        // Store changes
        changedRows.set(rowId, {
            athlete_id: athleteId,
            event_id: eventId,
            status: currentStatus,
            score: currentScore === '' ? null : parseFloat(currentScore),
            remarks: currentRemarks === '' ? null : currentRemarks
        });
        
        // Highlight row
        row.classList.add('bg-blue-50');
        row.classList.add('border-l-4', 'border-l-blue-500');
        console.log(`Added row ${rowId} to changed rows. Total: ${changedRows.size}`);
    } else {
        // Remove from changed rows
        changedRows.delete(rowId);
        row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
        console.log(`Removed row ${rowId} from changed rows. Total: ${changedRows.size}`);
    }
    
    // Update change indicator
    updateChangeIndicator();
}

function updateChangeIndicator() {
    const changeIndicator = document.getElementById('changeIndicator');
    const changeCount = document.getElementById('changeCount');
    const saveAllBtn = document.getElementById('saveAllBtn');
    
    console.log('Updating change indicator. Changed rows:', changedRows.size);
    
    // Check if elements exist before accessing them
    if (!changeIndicator || !changeCount) {
        console.warn('Change indicator elements not found');
        return;
    }
    
    if (changedRows.size > 0) {
        changeCount.textContent = changedRows.size;
        changeIndicator.classList.remove('hidden');
        if (saveAllBtn) {
            saveAllBtn.classList.remove('hidden');
        }
        console.log('Change indicator shown');
    } else {
        changeIndicator.classList.add('hidden');
        if (saveAllBtn) {
            saveAllBtn.classList.add('hidden');
        }
        console.log('Change indicator hidden');
    }
}

async function saveAllChanges() {
    if (isSubmitting) {
        showNotification('Already saving changes, please wait...', 'info');
        return;
    }
    
    if (changedRows.size === 0) {
        showNotification('No changes to save', 'info');
        return;
    }
    
    // Create array of all changes
    const changes = Array.from(changedRows.values());
    
    console.log('Saving all changes:', changes);
    
    try {
        isSubmitting = true;
        
        // Show loading on save button
        const saveAllBtn = document.getElementById('saveAllBtn');
        if (saveAllBtn) {
            saveAllBtn.disabled = true;
            saveAllBtn.textContent = 'Saving...';
            saveAllBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        // Get CSRF token with null check
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            throw new Error('CSRF token not found. Please refresh the page.');
        }
        
        const csrfToken = csrfMeta.getAttribute('content');
        
        // Send all changes in one request
        const response = await fetch('{{ route("coach.attendance.bulk-update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ changes: changes })
        });
        
        const data = await response.json();
        
        console.log('Save response:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Update original values for all changed rows
            changes.forEach(change => {
                const rowId = `row-${change.athlete_id}-${change.event_id}`;
                const row = document.getElementById(rowId);
                if (row) {
                    // Update dataset with new values
                    row.dataset.originalStatus = change.status;
                    row.dataset.originalScore = change.score || '';
                    row.dataset.originalRemarks = change.remarks || '';
                    
                    // Update input data-original attributes
                    const statusSelect = row.querySelector('.status-select');
                    const scoreInput = row.querySelector('.score-input');
                    const remarksInput = row.querySelector('.remarks-input');
                    
                    if (statusSelect) statusSelect.dataset.original = change.status;
                    if (scoreInput) scoreInput.dataset.original = change.score || '';
                    if (remarksInput) remarksInput.dataset.original = change.remarks || '';
                    
                    // Remove highlight
                    row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
                }
            });
            
            // Clear changed rows
            changedRows.clear();
            updateChangeIndicator();
            
        } else {
            throw new Error(data.message || 'Failed to save changes');
        }
    } catch (error) {
        console.error('Error saving changes:', error);
        showNotification('Failed to save changes: ' + error.message, 'error');
    } finally {
        isSubmitting = false;
        
        // Reset save button
        const saveAllBtn = document.getElementById('saveAllBtn');
        if (saveAllBtn) {
            saveAllBtn.disabled = false;
            saveAllBtn.textContent = 'Save Changes';
            saveAllBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

async function saveSingleRow(rowId) {
    if (isSubmitting) {
        showNotification('Please wait for current operation to complete', 'info');
        return;
    }
    
    const row = document.getElementById(rowId);
    if (!row) {
        console.error('Row not found:', rowId);
        return;
    }
    
    const athleteId = row.dataset.athleteId;
    const eventId = row.dataset.eventId;
    const statusSelect = row.querySelector('.status-select');
    const scoreInput = row.querySelector('.score-input');
    const remarksInput = row.querySelector('.remarks-input');
    const saveBtn = row.querySelector('.save-single-btn');
    
    // Check if row is editable
    if (!saveBtn) {
        showNotification('This record is read-only and cannot be edited', 'error');
        return;
    }
    
    if (!statusSelect || !scoreInput || !remarksInput) {
        console.error('Input elements not found in row:', rowId);
        return;
    }
    
    const status = statusSelect.value;
    const score = scoreInput.value.trim() === '' ? null : parseFloat(scoreInput.value);
    const remarks = remarksInput.value.trim() === '' ? null : remarksInput.value.trim();
    
    // Validate score
    if (score !== null && (isNaN(score) || score < 0 || score > 100)) {
        showNotification('Score must be between 0 and 100', 'error');
        return;
    }
    
    console.log('Saving single row:', { athleteId, eventId, status, score, remarks });
    
    try {
        isSubmitting = true;
        
        // Show loading on the save button
        if (saveBtn) {
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
            saveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        
        // Get CSRF token
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (!csrfMeta) {
            throw new Error('CSRF token not found.');
        }
        
        const csrfToken = csrfMeta.getAttribute('content');
        
        // Send single row update
        const response = await fetch('{{ route("coach.attendance.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                athlete_id: athleteId,
                event_id: eventId,
                status: status,
                score: score,
                remarks: remarks
            })
        });
        
        const data = await response.json();
        
        console.log('Save single response:', data);
        
        if (data.success) {
            showNotification(data.message, 'success');
            
            // Update original values
            row.dataset.originalStatus = status;
            row.dataset.originalScore = score || '';
            row.dataset.originalRemarks = remarks || '';
            
            // Update input data-original attributes
            statusSelect.dataset.original = status;
            scoreInput.dataset.original = score || '';
            remarksInput.dataset.original = remarks || '';
            
            // Remove from changed rows and highlight
            changedRows.delete(rowId);
            row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
            updateChangeIndicator();
            
        } else {
            throw new Error(data.message || 'Failed to save');
        }
    } catch (error) {
        console.error('Error saving row:', error);
        showNotification('Failed to save: ' + error.message, 'error');
    } finally {
        isSubmitting = false;
        
        // Reset save button
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = 'Save';
            saveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function discardChanges() {
    if (changedRows.size === 0) {
        showNotification('No changes to discard', 'info');
        return;
    }
    
    console.log('Discarding all changes');
    
    // Reset all changed rows to original values
    changedRows.forEach((changes, rowId) => {
        const row = document.getElementById(rowId);
        if (!row) return;
        
        const statusSelect = row.querySelector('.status-select');
        const scoreInput = row.querySelector('.score-input');
        const remarksInput = row.querySelector('.remarks-input');
        
        if (statusSelect) {
            statusSelect.value = statusSelect.dataset.original || '';
        }
        
        if (scoreInput) {
            scoreInput.value = scoreInput.dataset.original || '';
        }
        
        if (remarksInput) {
            remarksInput.value = remarksInput.dataset.original || '';
        }
        
        // Remove highlight
        row.classList.remove('bg-blue-50', 'border-l-4', 'border-l-blue-500');
    });
    
    // Clear changed rows
    changedRows.clear();
    updateChangeIndicator();
    
    showNotification('Changes discarded', 'info');
}

function showNotification(message, type) {
    // Check if notification already exists
    const existingNotification = document.querySelector('#temp-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'info' ? 'bg-blue-500 text-white' : 'bg-gray-500 text-white'
    }`;
    notification.textContent = message;
    notification.id = 'temp-notification';
    
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

function getStatusClass(status) {
    switch(status) {
        case 'Present': return 'bg-green-100 text-green-800';
        case 'Late': return 'bg-yellow-100 text-yellow-800';
        case 'Absent': return 'bg-red-100 text-red-800';
        case 'Excused': return 'bg-blue-100 text-blue-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
</script>

<style>
.data-row.bg-blue-50 {
    transition: background-color 0.3s ease;
}

.status-select:focus, .score-input:focus, .remarks-input:focus {
    outline: 2px solid #3b82f6;
    outline-offset: -1px;
    border-color: #3b82f6;
}

.save-single-btn:disabled, #saveAllBtn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Status badge styling */
.bg-green-100 { background-color: #d1fae5; }
.text-green-800 { color: #065f46; }
.bg-yellow-100 { background-color: #fef3c7; }
.text-yellow-800 { color: #92400e; }
.bg-red-100 { background-color: #fee2e2; }
.text-red-800 { color: #991b1b; }
.bg-blue-100 { background-color: #dbeafe; }
.text-blue-800 { color: #1e40af; }
.bg-gray-100 { background-color: #f3f4f6; }
.text-gray-800 { color: #1f2937; }
</style>

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