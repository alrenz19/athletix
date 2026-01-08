@extends('layouts.app')
@section('title', 'Manage Athletes')

@section('content')
<div class="p-6">
    <h2 class="text-xl font-semibold mb-4">Athletes</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-500 text-white px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Search and Filter Form --}}
    <form method="GET" action="{{ route('staff.athletes.index') }}" class="mb-6 flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-sm font-medium mb-1">Search Athletes</label>
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Search by name, year level, sport, or email..." 
                class="w-full border rounded p-2"
            />
        </div>
        
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium mb-1">Filter by Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="injured" {{ request('status') === 'injured' ? 'selected' : '' }}>Injured</option>
                <option value="graduate" {{ request('status') === 'graduate' ? 'selected' : '' }}>Graduated</option>
            </select>
        </div>
        
        <div class="w-full md:w-48">
            <label class="block text-sm font-medium mb-1">Filter by Year Level</label>
            <select name="year_level" class="w-full border rounded p-2">
                <option value="">All Year Levels</option>
                @foreach(['1st year', '2nd year', '3rd year', '4th year'] as $level)
                    <option value="{{ $level }}" {{ request('year_level') === $level ? 'selected' : '' }}>
                        {{ $level }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Search
            </button>
            <a href="{{ route('staff.athletes.index') }}" 
               class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">
                Clear
            </a>
        </div>
    </form>

    {{-- Scrollable Table --}}
    <div class="overflow-y-auto max-h-[500px] border rounded">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="px-3 py-2">Name</th>
                    <th class="px-3 py-2">Sport</th>
                    <th class="px-3 py-2">Year Level</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Email</th>
                    <th class="px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="athleteTable">
                @foreach($athletes as $athlete)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-3 py-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full overflow-hidden mr-3">
                                @if($athlete->profile_url)
                                    <img src="{{ $athlete->profile_url }}" 
                                         alt="{{ $athlete->full_name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center text-gray-600">
                                        {{ substr($athlete->full_name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <span>{{ $athlete->full_name }}</span>
                        </div>
                    </td>
                    <td class="px-3 py-2">{{ $athlete->sport->sport_name ?? 'N/A' }}</td>
                    <td class="px-3 py-2">
                        @if($athlete->year_level)
                            {{ $athlete->year_level }}
                        @else
                            <span class="text-gray-500">Not set</span>
                        @endif
                    </td>
                    <td class="px-3 py-2">
                        @if($athlete->conditions)
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'injured' => 'bg-red-100 text-red-800',
                                    'graduate' => 'bg-blue-100 text-blue-800'
                                ];
                                $statusText = [
                                    'active' => 'Active',
                                    'injured' => 'Injured',
                                    'graduate' => 'Graduated'
                                ];
                                $color = $statusColors[$athlete->conditions] ?? 'bg-gray-100 text-gray-800';
                                $text = $statusText[$athlete->conditions] ?? ucfirst($athlete->conditions);
                            @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $color }}">
                                {{ $text }}
                            </span>
                        @else
                            <span class="text-gray-500">Not set</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-sm">
                        {{ $athlete->user->email ?? 'No email' }}
                    </td>
                    <td class="px-3 py-2">
                        <div class="flex gap-2 items-center">
                            <!-- Edit Button triggers modal -->
                            <button onclick="openModal({{ $athlete->athlete_id }})" 
                                    class="bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                Edit
                            </button>
                            
                            {{-- Status Change Dropdown (for staff) --}}
                            <form action="{{ route('staff.athletes.update-status', $athlete->athlete_id) }}" 
                                  method="POST" 
                                  class="inline"
                                  onsubmit="return confirm('Change athlete status to ' + this.status.value + '?')">
                                @csrf
                                @method('PUT')
                                <select name="status" 
                                        onchange="this.form.submit()" 
                                        class="border rounded p-1.5 text-sm bg-white min-w-32">
                                    <option value="active" {{ $athlete->conditions === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="injured" {{ $athlete->conditions === 'injured' ? 'selected' : '' }}>
                                        Injured
                                    </option>
                                    <option value="graduate" {{ $athlete->conditions === 'graduate' ? 'selected' : '' }}>
                                        Graduate
                                    </option>
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>

                <!-- Modal -->
                <div id="modal-{{ $athlete->athlete_id }}" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                    <div class="bg-white rounded-lg w-full max-w-lg mx-4 p-6 max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold">Edit Athlete: {{ $athlete->full_name }}</h3>
                            <button onclick="closeModal({{ $athlete->athlete_id }})" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
                        </div>
                        <form method="POST" action="{{ route('staff.athletes.update', $athlete->athlete_id) }}" id="form-{{ $athlete->athlete_id }}">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Full Name</label>
                                <input type="text" name="full_name" value="{{ $athlete->full_name }}" class="w-full border px-3 py-2 rounded" required>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Birthdate</label>
                                <input type="date" name="birthdate" value="{{ $athlete->birthdate }}" class="w-full border px-3 py-2 rounded">
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Gender</label>
                                <select name="gender" class="w-full border px-3 py-2 rounded">
                                    <option value="Male" {{ $athlete->gender=='Male'?'selected':'' }}>Male</option>
                                    <option value="Female" {{ $athlete->gender=='Female'?'selected':'' }}>Female</option>
                                    <option value="Other" {{ $athlete->gender=='Other'?'selected':'' }}>Other</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Year Level</label>
                                <select name="year_level" class="w-full border px-3 py-2 rounded">
                                    <option value="1st year" {{ $athlete->year_level=='1st year'?'selected':'' }}>1st Year</option>
                                    <option value="2nd year" {{ $athlete->year_level=='2nd year'?'selected':'' }}>2nd Year</option>
                                    <option value="3rd year" {{ $athlete->year_level=='3rd year'?'selected':'' }}>3rd Year</option>
                                    <option value="4th year" {{ $athlete->year_level=='4th year'?'selected':'' }}>4th Year</option>
                                </select>
                            </div>

                            <!-- Status Field -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Status</label>
                                <select name="conditions" class="w-full border px-3 py-2 rounded">
                                    <option value="active" {{ $athlete->conditions=='active'?'selected':'' }}>Active</option>
                                    <option value="injured" {{ $athlete->conditions=='injured'?'selected':'' }}>Injured</option>
                                    <option value="graduate" {{ $athlete->conditions=='graduate'?'selected':'' }}>Graduated</option>
                                </select>
                            </div>

                            <!-- Sport Field (if staff can change sport) -->
                            @if(isset($sports))
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Sport</label>
                                <select name="sport_id" class="w-full border px-3 py-2 rounded">
                                    <option value="">Select Sport</option>
                                    @foreach($sports as $sport)
                                        <option value="{{ $sport->sport_id }}" {{ $athlete->sport_id == $sport->sport_id ? 'selected' : '' }}>
                                            {{ $sport->sport_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <!-- Email Field -->
                            @if($athlete->user)
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" name="email" value="{{ $athlete->user->email }}" class="w-full border px-3 py-2 rounded">
                            </div>
                            @endif

                            <!-- Password Section -->
                            <div class="mb-3">
                                <label class="block text-sm font-medium mb-1">Password (leave blank if unchanged)</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password" 
                                           id="password-{{ $athlete->athlete_id }}"
                                           class="w-full border px-3 py-2 rounded pr-10"
                                           placeholder="New password"
                                           onkeyup="checkPasswordStrength({{ $athlete->athlete_id }})">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('password-{{ $athlete->athlete_id }}', 'toggle-password-{{ $athlete->athlete_id }}')" 
                                            id="toggle-password-{{ $athlete->athlete_id }}" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                                        👁️
                                    </button>
                                </div>
                                
                                <!-- Password Strength Indicator -->
                                <div class="mt-2">
                                    <div class="flex items-center mb-1">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="strength-bar-{{ $athlete->athlete_id }}" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                        </div>
                                        <span id="strength-text-{{ $athlete->athlete_id }}" class="ml-2 text-sm">No password</span>
                                    </div>
                                    <div id="criteria-{{ $athlete->athlete_id }}" class="text-xs text-gray-600 space-y-1 mt-2">
                                        <div id="length-{{ $athlete->athlete_id }}" class="flex items-center">
                                            <span class="mr-1">⬜</span> 8+ characters
                                        </div>
                                        <div id="lowercase-{{ $athlete->athlete_id }}" class="flex items-center">
                                            <span class="mr-1">⬜</span> Lowercase letter
                                        </div>
                                        <div id="uppercase-{{ $athlete->athlete_id }}" class="flex items-center">
                                            <span class="mr-1">⬜</span> Uppercase letter
                                        </div>
                                        <div id="number-{{ $athlete->athlete_id }}" class="flex items-center">
                                            <span class="mr-1">⬜</span> Contains number
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="confirm-{{ $athlete->athlete_id }}"
                                           class="w-full border px-3 py-2 rounded pr-10"
                                           placeholder="Confirm password"
                                           onkeyup="checkPasswordMatch({{ $athlete->athlete_id }})">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('confirm-{{ $athlete->athlete_id }}', 'toggle-confirm-{{ $athlete->athlete_id }}')" 
                                            id="toggle-confirm-{{ $athlete->athlete_id }}" 
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                                        👁️
                                    </button>
                                </div>
                                <div id="match-message-{{ $athlete->athlete_id }}" class="text-sm mt-1"></div>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="closeModal({{ $athlete->athlete_id }})" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-600">Cancel</button>
                                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($athletes->hasPages())
    <div class="mt-6">
        {{ $athletes->appends(request()->query())->links() }}
    </div>
    @endif
    
    {{-- Show message if no results --}}
    @if($athletes->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 text-lg">No athletes found matching your criteria.</p>
            <a href="{{ route('staff.athletes.index') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                Clear filters
            </a>
        </div>
    @endif
</div>

<script>
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
        document.body.style.overflow = 'auto'; // Restore scrolling
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        document.querySelectorAll('[id^="modal-"]').forEach(modal => {
            if (event.target === modal) {
                const id = modal.id.split('-')[1];
                closeModal(id);
            }
        });
    }

    // Add loading indicator when changing status
    document.addEventListener('DOMContentLoaded', function() {
        const statusForms = document.querySelectorAll('form[action*="update-status"]');
        statusForms.forEach(form => {
            const select = form.querySelector('select[name="status"]');
            if (select) {
                select.addEventListener('change', function() {
                    // Show loading on the select
                    this.disabled = true;
                    this.style.opacity = '0.5';
                    
                    // Submit the form
                    form.submit();
                });
            }
        });
    });

    // Password strength checking functions
    function checkPasswordStrength(athleteId) {
        const password = document.getElementById('password-' + athleteId).value;
        const strengthBar = document.getElementById('strength-bar-' + athleteId);
        const strengthText = document.getElementById('strength-text-' + athleteId);
        
        // Reset checks
        resetCheck('length-' + athleteId, '8+ characters');
        resetCheck('lowercase-' + athleteId, 'Lowercase letter');
        resetCheck('uppercase-' + athleteId, 'Uppercase letter');
        resetCheck('number-' + athleteId, 'Contains number');
        
        if (password.length === 0) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
            strengthText.textContent = 'No password';
            strengthText.className = 'ml-2 text-sm text-gray-600';
            return;
        }
        
        let score = 0;
        const totalChecks = 4;
        
        // Check criteria
        const hasLength = password.length >= 8;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        // Update checkmarks
        updateCheck('length-' + athleteId, hasLength, '8+ characters');
        updateCheck('lowercase-' + athleteId, hasLowercase, 'Lowercase letter');
        updateCheck('uppercase-' + athleteId, hasUppercase, 'Uppercase letter');
        updateCheck('number-' + athleteId, hasNumber, 'Contains number');
        
        // Calculate score
        score = [hasLength, hasLowercase, hasUppercase, hasNumber]
            .filter(Boolean).length;
        
        // Calculate percentage
        const percentage = (score / totalChecks) * 100;
        strengthBar.style.width = percentage + '%';
        
        // Update strength indicator
        if (score === 0) {
            strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
            strengthText.textContent = 'Very Weak';
            strengthText.className = 'ml-2 text-sm text-red-600';
        } else if (score <= 1) {
            strengthBar.className = 'h-2 rounded-full bg-red-500 transition-all duration-300';
            strengthText.textContent = 'Weak';
            strengthText.className = 'ml-2 text-sm text-red-500';
        } else if (score === 2) {
            strengthBar.className = 'h-2 rounded-full bg-yellow-500 transition-all duration-300';
            strengthText.textContent = 'Fair';
            strengthText.className = 'ml-2 text-sm text-yellow-600';
        } else if (score === 3) {
            strengthBar.className = 'h-2 rounded-full bg-blue-500 transition-all duration-300';
            strengthText.textContent = 'Good';
            strengthText.className = 'ml-2 text-sm text-blue-600';
        } else {
            strengthBar.className = 'h-2 rounded-full bg-green-500 transition-all duration-300';
            strengthText.textContent = 'Strong';
            strengthText.className = 'ml-2 text-sm text-green-600';
        }
        
        // Also check password match
        checkPasswordMatch(athleteId);
    }

    function resetCheck(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = '<span class="mr-1">⬜</span>' + text;
            element.className = 'flex items-center text-gray-600';
        }
    }

    function updateCheck(elementId, isValid, text) {
        const element = document.getElementById(elementId);
        if (element) {
            if (isValid) {
                element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + text;
                element.className = 'flex items-center text-green-600';
            } else {
                element.innerHTML = '<span class="mr-1 text-red-500">✗</span>' + text;
                element.className = 'flex items-center text-red-600';
            }
        }
    }

    function checkPasswordMatch(athleteId) {
        const password = document.getElementById('password-' + athleteId).value;
        const confirmPassword = document.getElementById('confirm-' + athleteId).value;
        const messageElement = document.getElementById('match-message-' + athleteId);
        
        if (!messageElement) return;
        
        if (password === '' && confirmPassword === '') {
            messageElement.textContent = '';
            messageElement.className = 'text-sm mt-1';
            return;
        }
        
        if (confirmPassword === '') {
            messageElement.textContent = 'Please confirm password';
            messageElement.className = 'text-sm mt-1 text-yellow-600';
            return;
        }
        
        if (password === confirmPassword) {
            messageElement.textContent = 'Passwords match ✓';
            messageElement.className = 'text-sm mt-1 text-green-600';
        } else {
            messageElement.textContent = 'Passwords do not match ✗';
            messageElement.className = 'text-sm mt-1 text-red-600';
        }
    }

    function togglePasswordVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        
        if (input && button) {
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }
    }

    // Add form validation for each modal
    document.querySelectorAll('[id^="form-"]').forEach(form => {
        form.addEventListener('submit', function(event) {
            const athleteId = this.id.split('-')[1];
            const password = document.getElementById('password-' + athleteId).value;
            const confirmPassword = document.getElementById('confirm-' + athleteId).value;
            
            // Only validate if password is being changed
            if (password) {
                // Check if passwords match
                if (password !== confirmPassword) {
                    event.preventDefault();
                    alert('Passwords do not match. Please confirm your password.');
                    return false;
                }
                
                // Optional: Check password strength before allowing submission
                if (password.length > 0 && password.length < 8) {
                    event.preventDefault();
                    alert('Password must be at least 8 characters long.');
                    return false;
                }
            }
            
            return true;
        });
    });
</script>

<style>
/* Modal animations */
[id^="modal-"] > div {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Scrollable table styling */
.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f7fafc;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e0;
    border-radius: 4px;
}

/* Password strength bar styling */
[id^="strength-bar-"] {
    transition: width 0.3s ease, background-color 0.3s ease;
}

/* Make modal responsive */
@media (max-width: 640px) {
    .max-w-lg {
        margin: 1rem;
        width: calc(100% - 2rem);
    }
}
</style>
@endsection