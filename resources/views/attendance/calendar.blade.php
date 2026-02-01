@extends('layouts.app')

@section('title', 'Attendance Management - Calendar View')
@section('header-actions')
    <div class="flex space-x-2">
        <a href="{{ route('attendance') }}" 
           class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded">
            Table View
        </a>
        <button onclick="openAddModal()"
                class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
            Add Single Attendance
        </button>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Filter Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Attendance Management</h2>
            
            <form id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Event</label>
                    <select name="event_id" id="eventFilter" class="w-full border rounded px-3 py-2">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->event_id }}">
                                {{ $event->event_name }} ({{ $event->event_date->format('M d, Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Date</label>
                    <input type="date" name="date" id="dateFilter" 
                           class="w-full border rounded px-3 py-2" 
                           value="{{ date('Y-m-d') }}">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sport</label>
                    <select name="sport_id" id="sportFilter" class="w-full border rounded px-3 py-2">
                        <option value="">All Sports</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}">{{ $sport->sport_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="button" onclick="filterAttendance()" 
                            class="w-full bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded">
                        Filter
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Calendar Section -->
    <section>
        <div class="bg-white rounded-lg shadow-lg p-4 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h3 id="currentMonthYear" class="text-xl font-bold text-gray-800">
                        {{ \Carbon\Carbon::now()->format('F Y') }}
                    </h3>
                </div>
                <div class="flex space-x-2">
                    <button onclick="previousMonth()" 
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                        &larr; Prev
                    </button>
                    <button onclick="today()" 
                            class="px-3 py-1 bg-amber-900 hover:bg-amber-800 text-white rounded">
                        Today
                    </button>
                    <button onclick="nextMonth()" 
                            class="px-3 py-1 bg-gray-200 hover:bg-gray-300 rounded">
                        Next &rarr;
                    </button>
                </div>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="grid grid-cols-7 gap-2 mb-4">
                @php $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; @endphp
                @foreach($days as $day)
                    <div class="text-center font-bold text-gray-700 p-2">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <div id="calendarGrid" class="grid grid-cols-7 gap-2">
                <!-- Calendar will be populated by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Attendance List Section (for selected date) -->
    <section id="attendanceSection" class="hidden">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 id="selectedDateTitle" class="text-xl font-bold text-gray-800"></h3>
                <div id="actionButtons" class="space-x-2"></div>
            </div>
            
            <div id="attendanceListContainer">
                <!-- Attendance list will be populated here -->
            </div>
        </div>
    </section>
</div>

<!-- Add Single Attendance Modal -->
<div id="addAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Add Attendance</h3>
        <form action="{{ route('attendance.store') }}" method="POST" id="singleAttendanceForm">
            @csrf
            <div class="mb-2">
                <label class="block font-medium">Athlete</label>
                <select name="athlete_id" class="w-full border p-2 rounded" required>
                    <option value="">Select Athlete</option>
                    @foreach($allAthletes as $athlete)
                        <option value="{{ $athlete->athlete_id }}">{{ $athlete->full_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block font-medium">Event</label>
                <select name="event_id" class="w-full border p-2 rounded" required>
                    <option value="">Select Event</option>
                    @foreach($events as $event)
                        <option value="{{ $event->event_id }}">{{ $event->event_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-2">
                <label class="block font-medium">Date</label>
                <input type="date" name="event_date" class="w-full border p-2 rounded" required 
                       value="{{ date('Y-m-d') }}">
            </div>
            <div class="mb-4">
                <label class="block font-medium">Status</label>
                <select name="status" class="w-full border p-2 rounded">
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Absent">Absent</option>
                    <option value="Excused">Excused</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddModal()" class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Cancel</button>
                <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Attendance Modal -->
<div id="editAttendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4">Edit Attendance</h3>
        <form id="editAttendanceForm" method="POST">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <input type="hidden" name="attendance_id" id="editAttendanceId">
            <input type="hidden" name="event_date" id="editAttendanceDate">
            
            <div class="mb-4">
                <label class="block font-medium mb-2">Athlete</label>
                <p id="editAthleteName" class="text-gray-700 font-medium"></p>
            </div>
            
            <div class="mb-4">
                <label class="block font-medium mb-2">Event</label>
                <p id="editEventName" class="text-gray-700 font-medium"></p>
            </div>
            
            <div class="mb-4">
                <label class="block font-medium">Status</label>
                <select name="status" id="editStatus" class="w-full border p-2 rounded">
                    <option value="Present">Present</option>
                    <option value="Late">Late</option>
                    <option value="Absent">Absent</option>
                    <option value="Excused">Excused</option>
                </select>
            </div>
            
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" 
                        class="px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-3 py-1 bg-amber-900 text-white rounded hover:bg-amber-800">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <h3 class="text-lg font-bold mb-4 text-red-600">Confirm Delete</h3>
        <p class="mb-6">Are you sure you want to delete this attendance record?</p>
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
    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDate = currentDate.toISOString().split('T')[0];
    let selectedEventId = '';
    let selectedSportId = '';
    let canEdit = true;
    let isSuperAdmin = {{ auth()->user()->role === 'SuperAdmin' ? 'true' : 'false' }};

    // Initialize calendar on page load
    document.addEventListener('DOMContentLoaded', function() {
        renderCalendar(currentMonth, currentYear);
        loadAttendanceForDate(selectedDate);
        
        // Set up event listeners for filters
        document.getElementById('eventFilter').addEventListener('change', function() {
            selectedEventId = this.value;
            loadAttendanceForDate(selectedDate);
        });
        
        document.getElementById('sportFilter').addEventListener('change', function() {
            selectedSportId = this.value;
            loadAttendanceForDate(selectedDate);
        });
        
        document.getElementById('dateFilter').addEventListener('change', function() {
            selectedDate = this.value;
            renderCalendar(currentMonth, currentYear);
            loadAttendanceForDate(selectedDate);
        });

        // Handle single attendance form submission
        document.getElementById('singleAttendanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSingleAttendance(this);
        });
    });

    function renderCalendar(month, year) {
        const monthYearElement = document.getElementById('currentMonthYear');
        const calendarGrid = document.getElementById('calendarGrid');
        
        // Update month/year display
        const monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];
        monthYearElement.textContent = `${monthNames[month]} ${year}`;
        
        // Clear previous calendar
        calendarGrid.innerHTML = '';
        
        // Get first day of month and total days
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDay = firstDay.getDay();
        
        // Add empty cells for days before the first day of month
        for (let i = 0; i < startingDay; i++) {
            const emptyCell = document.createElement('div');
            emptyCell.className = 'h-24 p-2 border rounded bg-gray-50';
            calendarGrid.appendChild(emptyCell);
        }
        
        // Add cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dayCell = document.createElement('div');
            dayCell.className = 'h-24 p-2 border rounded cursor-pointer transition-colors hover:bg-gray-50';
            
            const dateStr = `${year}-${(month + 1).toString().padStart(2, '0')}-${day.toString().padStart(2, '0')}`;
            const dateObj = new Date(year, month, day);
            
            // Check if it's today
            const today = new Date();
            if (dateObj.toDateString() === today.toDateString()) {
                dayCell.classList.add('bg-amber-50', 'border-amber-300');
            }
            
            // Check if it's selected date
            if (dateStr === selectedDate) {
                dayCell.classList.add('ring-2', 'ring-amber-500');
            }
            
            // Day number
            const dayNumber = document.createElement('div');
            dayNumber.className = 'font-bold text-gray-800 mb-1';
            dayNumber.textContent = day;
            dayCell.appendChild(dayNumber);
            
            // Check if it's a past date that shouldn't be editable
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            
            if (dateObj < oneWeekAgo && !isSuperAdmin) {
                dayCell.classList.add('bg-gray-100', 'text-gray-400');
                const pastBadge = document.createElement('div');
                pastBadge.className = 'text-xs text-gray-500 italic mt-1';
                pastBadge.textContent = 'Read-only';
                dayCell.appendChild(pastBadge);
            }
            
            // Add click event
            dayCell.onclick = () => selectDate(dateStr);
            
            calendarGrid.appendChild(dayCell);
        }
    }
    
    function selectDate(dateStr) {
        selectedDate = dateStr;
        document.getElementById('dateFilter').value = dateStr;
        renderCalendar(currentMonth, currentYear);
        loadAttendanceForDate(dateStr);
    }
    
    function previousMonth() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    }
    
    function nextMonth() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    }
    
    function today() {
        currentDate = new Date();
        currentMonth = currentDate.getMonth();
        currentYear = currentDate.getFullYear();
        selectedDate = currentDate.toISOString().split('T')[0];
        document.getElementById('dateFilter').value = selectedDate;
        renderCalendar(currentMonth, currentYear);
        loadAttendanceForDate(selectedDate);
    }
    
    async function loadAttendanceForDate(dateStr) {
        const eventId = document.getElementById('eventFilter').value;
        const sportId = document.getElementById('sportFilter').value;
        
        try {
            const response = await fetch(`{{ route('attendance.get') }}?date=${dateStr}&event_id=${eventId}&sport_id=${sportId}`);
            const data = await response.json();
            
            // Update UI
            updateAttendanceUI(data);
        } catch (error) {
            console.error('Error loading attendance:', error);
            showNotification('Error loading attendance data', 'error');
        }
    }
    
    function filterAttendance() {
        loadAttendanceForDate(selectedDate);
    }
    
    function updateAttendanceUI(data) {
        const section = document.getElementById('attendanceSection');
        const title = document.getElementById('selectedDateTitle');
        const actionButtons = document.getElementById('actionButtons');
        const container = document.getElementById('attendanceListContainer');
        
        const dateObj = new Date(data.selectedDate);
        title.textContent = `Attendance for ${dateObj.toLocaleDateString('en-US', { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        })}`;
        
        // Check if date is editable
        const selectedDateObj = new Date(data.selectedDate);
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        canEdit = (selectedDateObj >= oneWeekAgo) || isSuperAdmin;
        
        // Update action buttons
        actionButtons.innerHTML = '';
        if (canEdit && data.event) {
            const saveAllBtn = document.createElement('button');
            saveAllBtn.className = 'bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded';
            saveAllBtn.textContent = 'Save All Attendance';
            saveAllBtn.onclick = saveAllAttendance;
            actionButtons.appendChild(saveAllBtn);
        }
        
        // Display attendance list
        container.innerHTML = '';
        
        if (!data.event && data.selectedEventId) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500">No event found for selected date</p>
                    <p class="text-sm text-gray-400 mt-2">Select a different event or date</p>
                </div>
            `;
            section.classList.remove('hidden');
            return;
        }
        
        if (data.athletes.length === 0) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500">No athletes found for this event</p>
                    <p class="text-sm text-gray-400 mt-2">Register athletes to the event first</p>
                </div>
            `;
            section.classList.remove('hidden');
            return;
        }
        
        // Create attendance table
        const table = document.createElement('table');
        table.className = 'w-full border-collapse border border-gray-300';
        
        // Table header
        const thead = document.createElement('thead');
        thead.innerHTML = `
            <tr class="bg-gray-100">
                <th class="border p-3 text-left">Athlete</th>
                <th class="border p-3 text-left">Sport</th>
                <th class="border p-3 text-left">Status</th>
                <th class="border p-3 text-left">Actions</th>
            </tr>
        `;
        table.appendChild(thead);
        
        // Table body
        const tbody = document.createElement('tbody');
        
        data.athletes.forEach(athlete => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';
            row.dataset.athleteId = athlete.athlete_id;
            
            // Find existing attendance for this athlete
            const existingAttendance = data.existingAttendances.find(
                att => att.athlete_id == athlete.athlete_id
            );
            
            const status = existingAttendance ? existingAttendance.status : 'Absent';
            const attendanceId = existingAttendance ? existingAttendance.attendance_id : null;
            const athleteName = athlete.full_name;
            const eventName = data.event ? data.event.event_name : '';
            
            row.innerHTML = `
                <td class="border p-3">
                    <div class="font-medium">${athleteName}</div>
                    <div class="text-sm text-gray-500">${athlete.student_id || ''}</div>
                </td>
                <td class="border p-3">${athlete.sport?.sport_name || 'N/A'}</td>
                <td class="border p-3">
                    <span class="px-3 py-1 rounded-full text-sm ${getStatusClass(status)}">
                        ${status}
                    </span>
                </td>
                <td class="border p-3 space-x-2">
                    ${canEdit ? `
                        <select onchange="updateAttendanceStatus(this, ${athlete.athlete_id}, ${attendanceId || 'null'})" 
                                class="border rounded px-2 py-1 ${getStatusColorClass(status)}">
                            <option value="Present" ${status === 'Present' ? 'selected' : ''}>Present</option>
                            <option value="Late" ${status === 'Late' ? 'selected' : ''}>Late</option>
                            <option value="Absent" ${status === 'Absent' ? 'selected' : ''}>Absent</option>
                            <option value="Excused" ${status === 'Excused' ? 'selected' : ''}>Excused</option>
                        </select>
                    ` : `
                        <span class="text-gray-500 text-sm">Read-only</span>
                    `}
                    ${attendanceId ? `
                        <button onclick="openEditModal(${attendanceId}, '${athleteName}', '${eventName}', '${status}')" 
                                class="px-2 py-1 bg-amber-100 text-amber-800 rounded text-xs hover:bg-amber-200">
                            Edit
                        </button>
                        <button onclick="openDeleteModal(${attendanceId})" 
                                class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">
                            Delete
                        </button>
                    ` : ''}
                </td>
            `;
            tbody.appendChild(row);
        });
        
        table.appendChild(tbody);
        container.appendChild(table);
        
        // Show event info
        if (data.event) {
            const eventInfo = document.createElement('div');
            eventInfo.className = 'mb-4 p-4 bg-blue-50 rounded-lg';
            eventInfo.innerHTML = `
                <h4 class="font-bold text-lg text-blue-800 mb-2">${data.event.event_name}</h4>
                <p class="text-blue-700"><strong>Date:</strong> ${new Date(data.event.event_date).toLocaleDateString()}</p>
                <p class="text-blue-700"><strong>Type:</strong> ${data.event.event_type}</p>
                <p class="text-blue-700"><strong>Location:</strong> ${data.event.location}</p>
                <p class="text-blue-700"><strong>Sport:</strong> ${data.event.sport?.sport_name || 'N/A'}</p>
            `;
            container.prepend(eventInfo);
        }
        
        section.classList.remove('hidden');
    }
    
    async function updateAttendanceStatus(selectElement, athleteId, attendanceId) {
        const status = selectElement.value;
        const eventId = document.getElementById('eventFilter').value;
        
        if (!eventId) {
            alert('Please select an event first');
            selectElement.value = 'Absent';
            return;
        }
        
        if (!canEdit) {
            alert('You cannot edit attendance for dates older than one week');
            selectElement.value = 'Absent';
            return;
        }
        
        const formData = new FormData();
        formData.append('athlete_id', athleteId);
        formData.append('event_id', eventId);
        formData.append('status', status);
        formData.append('event_date', selectedDate);
        
        const url = attendanceId 
            ? `/attendance/update/${attendanceId}` 
            : '/attendance/store';
        
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Update the status display
                selectElement.className = `border rounded px-2 py-1 ${getStatusColorClass(status)}`;
                
                // Update status badge in the same row
                const row = selectElement.closest('tr');
                const statusCell = row.querySelector('td:nth-child(3) span');
                statusCell.textContent = status;
                statusCell.className = `px-3 py-1 rounded-full text-sm ${getStatusClass(status)}`;
                
                // Show success message
                showNotification('Attendance updated successfully!', 'success');
                
                // Reload attendance data
                loadAttendanceForDate(selectedDate);
            } else {
                throw new Error(data.message || 'Failed to update attendance');
            }
        } catch (error) {
            console.error('Error updating attendance:', error);
            alert('Failed to update attendance: ' + error.message);
            selectElement.value = 'Absent';
        }
    }
    
    async function saveAllAttendance() {
        // Collect all attendance data from the table
        const rows = document.querySelectorAll('#attendanceListContainer tbody tr');
        const eventId = document.getElementById('eventFilter').value;
        
        if (!eventId) {
            alert('Please select an event first');
            return;
        }
        
        if (!canEdit) {
            alert('You cannot edit attendance for dates older than one week');
            return;
        }
        
        const attendances = [];
        rows.forEach(row => {
            const athleteId = row.dataset.athleteId;
            const select = row.querySelector('select');
            const status = select ? select.value : 'Absent';
            
            attendances.push({
                athlete_id: athleteId,
                event_id: eventId,
                status: status,
                event_date: selectedDate
            });
        });
        
        try {
            const response = await fetch('{{ route("attendance.bulk-update") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ attendances: attendances })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('All attendance saved successfully!', 'success');
                loadAttendanceForDate(selectedDate);
            } else {
                throw new Error(data.message || 'Failed to save attendance');
            }
        } catch (error) {
            console.error('Error saving attendance:', error);
            alert('Failed to save attendance: ' + error.message);
        }
    }
    
    async function saveSingleAttendance(form) {
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification('Attendance added successfully!', 'success');
                closeAddModal();
                // Reload the current view
                selectedDate = formData.get('event_date');
                document.getElementById('dateFilter').value = selectedDate;
                loadAttendanceForDate(selectedDate);
            } else {
                throw new Error(data.message || 'Failed to add attendance');
            }
        } catch (error) {
            console.error('Error adding attendance:', error);
            alert('Failed to add attendance: ' + error.message);
        }
    }
    
    function openAddModal() {
        document.getElementById('addAttendanceModal').classList.remove('hidden');
    }
    
    function openEditModal(id, athleteName, eventName, status) {
        document.getElementById('editAttendanceModal').classList.remove('hidden');
        document.getElementById('editAthleteName').textContent = athleteName;
        document.getElementById('editEventName').textContent = eventName;
        document.getElementById('editStatus').value = status;
        document.getElementById('editAttendanceId').value = id;
        document.getElementById('editAttendanceForm').action = `/attendance/update/${id}`;
    }
    
    function openDeleteModal(id) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteAttendanceId').value = id;
        document.getElementById('deleteForm').action = `/attendance/delete/${id}`;
    }
    
    function closeAddModal() {
        document.getElementById('addAttendanceModal').classList.add('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editAttendanceModal').classList.add('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
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
    
    function getStatusColorClass(status) {
        switch(status) {
            case 'Present': return 'bg-green-50 border-green-300 text-green-800';
            case 'Late': return 'bg-yellow-50 border-yellow-300 text-yellow-800';
            case 'Absent': return 'bg-red-50 border-red-300 text-red-800';
            case 'Excused': return 'bg-blue-50 border-blue-300 text-blue-800';
            default: return 'bg-gray-50 border-gray-300 text-gray-800';
        }
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remove notification after 3 seconds
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100px)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
</script>
@endsection