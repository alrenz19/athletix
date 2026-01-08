@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6">Edit Profile</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('athlete.profile.update') }}" enctype="multipart/form-data" id="athleteProfileForm" class="space-y-6">
        @csrf
        @method('PATCH')

        <!-- Account Information -->
        <div class="border-b pb-4">
            <h3 class="text-lg font-medium mb-4">Account Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full border rounded p-2">
                    @error('username') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- Password -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Password (leave blank if unchanged)</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                            placeholder="New password" 
                            class="w-full border rounded p-2 pr-10"
                            onkeyup="checkPasswordStrength()">
                        <button type="button" onclick="togglePasswordVisibility('password', 'passwordToggle')" 
                                id="passwordToggle" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                            👁️
                        </button>
                    </div>
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="flex items-center mb-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="passwordStrengthBar" class="h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <span id="passwordStrengthText" class="ml-2 text-sm">No password</span>
                        </div>
                        <div id="passwordCriteria" class="text-xs text-gray-600 grid grid-cols-2 md:grid-cols-3 gap-1 mt-2">
                            <div id="lengthCheck" class="flex items-center">
                                <span class="mr-1">⬜</span> 8+ characters
                            </div>
                            <div id="lowercaseCheck" class="flex items-center">
                                <span class="mr-1">⬜</span> Lowercase letter
                            </div>
                            <div id="uppercaseCheck" class="flex items-center">
                                <span class="mr-1">⬜</span> Uppercase letter
                            </div>
                            <div id="numberCheck" class="flex items-center">
                                <span class="mr-1">⬜</span> Contains number
                            </div>
                            <div id="specialCheck" class="flex items-center">
                                <span class="mr-1">⬜</span> Special character
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="passwordConfirmation" 
                            placeholder="Confirm new password"
                            class="w-full border rounded p-2 pr-10"
                            onkeyup="checkPasswordMatch()">
                        <button type="button" onclick="togglePasswordVisibility('passwordConfirmation', 'confirmToggle')" 
                                id="confirmToggle" 
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                            👁️
                        </button>
                    </div>
                    <div id="passwordMatchMessage" class="text-sm mt-1"></div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="border-b pb-4">
            <h3 class="text-lg font-medium mb-4">Personal Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $athlete?->full_name) }}" class="w-full border rounded p-2">
                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2 mb-3">
                    <label for="profile_image" class="block text-gray-700 mb-2">Profile Image</label>
                    @if(isset($athlete) && $athlete->profile_url)
                        <img id="preview" src="{{ $athlete->profile_url }}" alt="{{ $athlete->full_name }}" width="100" height="100" class="mb-2 rounded">
                    @else
                        <img id="preview" src="{{ asset('storage/'.$athlete?->profile_image) }}" alt="Profile Image" width="100" height="100" class="mb-2 rounded">
                    @endif
                    <input type="file" name="profile_image" id="profile_image" class="form-control mt-2" accept="image/*" onchange="previewImage(event)">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Birthdate</label>
                    <input type="date" name="birthdate" value="{{ old('birthdate', $athlete?->birthdate) }}" class="w-full border rounded p-2">
                    @error('birthdate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Age</label>
                    <input type="number" name="age" value="{{ old('age', $athlete?->age) }}" class="w-full border rounded p-2" min="15" max="40">
                    @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Gender</label>
                    <select name="gender" class="w-full border rounded p-2">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ old('gender', $athlete?->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $athlete?->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $athlete?->gender) == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">School ID</label>
                    <input type="text" name="school_id" value="{{ old('school_id', $athlete?->school_id) }}" class="w-full border rounded p-2">
                    @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="border-b pb-4">
            <h3 class="text-lg font-medium mb-4">Academic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Year Level</label>
                    <select name="year_level" class="w-full border rounded p-2">
                        <option value="">Select Year Level</option>
                        <option value="1st Year" {{ old('year_level', $athlete?->year_level) == '1st Year' ? 'selected' : '' }}>1st Year</option>
                        <option value="2nd Year" {{ old('year_level', $athlete?->year_level) == '2nd Year' ? 'selected' : '' }}>2nd Year</option>
                        <option value="3rd Year" {{ old('year_level', $athlete?->year_level) == '3rd Year' ? 'selected' : '' }}>3rd Year</option>
                        <option value="4th Year" {{ old('year_level', $athlete?->year_level) == '4th Year' ? 'selected' : '' }}>4th Year</option>
                        <option value="Alumni" {{ old('year_level', $athlete?->year_level) == 'Alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                    @error('year_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Academic Course</label>
                    <input type="text" name="academic_course" value="{{ old('academic_course', $athlete?->academic_course) }}" class="w-full border rounded p-2" placeholder="e.g., BS Computer Science">
                    @error('academic_course') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Section</label>
                    <select name="section_id" class="w-full border rounded p-2">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->section_id }}" {{ old('section_id', $athlete?->section_id) == $section->section_id ? 'selected' : '' }}>
                                {{ $section->section_name }} - {{ $section->course->course_name ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Sports & Competition Information -->
        <div class="border-b pb-4">
            <h3 class="text-lg font-medium mb-4">Sports & Competition Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Sport</label>
                    <select name="sport_id" class="w-full border rounded p-2">
                        <option value="">Select Sport</option>
                        @foreach($sports as $sport)
                            <option value="{{ $sport->sport_id }}" {{ old('sport_id', $athlete?->sport_id) == $sport->sport_id ? 'selected' : '' }}>
                                {{ $sport->sport_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('sport_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Highest Competition Level</label>
                    <select name="highest_competition_level" class="w-full border rounded p-2">
                        <option value="">Select Level</option>
                        @foreach(['Intramurals','University','Local','Regional','National','International'] as $level)
                            <option value="{{ $level }}" {{ old('highest_competition_level', $athlete?->highest_competition_level) == $level ? 'selected' : '' }}>{{ $level }}</option>
                        @endforeach
                    </select>
                    @error('highest_competition_level') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">International Competition Name (if applicable)</label>
                    <input type="text" name="international_competition_name" value="{{ old('international_competition_name', $athlete?->international_competition_name) }}" class="w-full border rounded p-2" placeholder="e.g., SEA Games, Asian Games">
                    @error('international_competition_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Highest Accomplishment as an Athlete</label>
                    <textarea name="highest_accomplishment" rows="3" class="w-full border rounded p-2">{{ old('highest_accomplishment', $athlete?->highest_accomplishment) }}</textarea>
                    @error('highest_accomplishment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Training Information -->
        <div class="border-b pb-4">
            <h3 class="text-lg font-medium mb-4">Training Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Training Frequency (days per week)</label>
                    <input type="number" name="training_frequency_days" value="{{ old('training_frequency_days', $athlete?->training_frequency_days) }}" class="w-full border rounded p-2" min="1" max="7">
                    @error('training_frequency_days') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Training Hours Per Day</label>
                    <input type="number" name="training_hours_per_day" step="0.5" value="{{ old('training_hours_per_day', $athlete?->training_hours_per_day) }}" class="w-full border rounded p-2" min="0.5" max="8">
                    @error('training_hours_per_day') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 mb-2">Special Training & Seminars Attended</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach(['regional','national','international'] as $seminar)
                        <label class="flex items-center">
                            <input type="checkbox" name="training_seminars_{{ $seminar }}" value="1" {{ old('training_seminars_'.$seminar, $athlete?->{'training_seminars_'.$seminar}) ? 'checked' : '' }} class="mr-2">
                            {{ ucfirst($seminar) }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
// Password strength checking functions
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    // Reset checks
    resetCheck('lengthCheck', '8+ characters');
    resetCheck('lowercaseCheck', 'Lowercase letter');
    resetCheck('uppercaseCheck', 'Uppercase letter');
    resetCheck('numberCheck', 'Contains number');
    resetCheck('specialCheck', 'Special character');
    
    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
        strengthText.textContent = 'No password';
        strengthText.className = 'ml-2 text-sm text-gray-600';
        return;
    }
    
    let score = 0;
    const totalChecks = 5;
    
    // Check criteria
    const hasLength = password.length >= 8;
    const hasLowercase = /[a-z]/.test(password);
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
    
    // Update checkmarks
    updateCheck('lengthCheck', hasLength, '8+ characters');
    updateCheck('lowercaseCheck', hasLowercase, 'Lowercase letter');
    updateCheck('uppercaseCheck', hasUppercase, 'Uppercase letter');
    updateCheck('numberCheck', hasNumber, 'Contains number');
    updateCheck('specialCheck', hasSpecial, 'Special character');
    
    // Calculate score
    score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
        .filter(Boolean).length;
    
    // Calculate percentage
    const percentage = (score / totalChecks) * 100;
    strengthBar.style.width = percentage + '%';
    
    // Update strength indicator
    if (score === 0) {
        strengthBar.className = 'h-2 rounded-full bg-gray-300 transition-all duration-300';
        strengthText.textContent = 'Very Weak';
        strengthText.className = 'ml-2 text-sm text-red-600';
    } else if (score <= 2) {
        strengthBar.className = 'h-2 rounded-full bg-red-500 transition-all duration-300';
        strengthText.textContent = 'Weak';
        strengthText.className = 'ml-2 text-sm text-red-500';
    } else if (score === 3) {
        strengthBar.className = 'h-2 rounded-full bg-yellow-500 transition-all duration-300';
        strengthText.textContent = 'Fair';
        strengthText.className = 'ml-2 text-sm text-yellow-600';
    } else if (score === 4) {
        strengthBar.className = 'h-2 rounded-full bg-blue-500 transition-all duration-300';
        strengthText.textContent = 'Good';
        strengthText.className = 'ml-2 text-sm text-blue-600';
    } else {
        strengthBar.className = 'h-2 rounded-full bg-green-500 transition-all duration-300';
        strengthText.textContent = 'Strong';
        strengthText.className = 'ml-2 text-sm text-green-600';
    }
    
    // Also check password match
    checkPasswordMatch();
}

function resetCheck(elementId, text) {
    const element = document.getElementById(elementId);
    element.innerHTML = '<span class="mr-1">⬜</span>' + text;
    element.className = 'flex items-center text-gray-600';
}

function updateCheck(elementId, isValid, text) {
    const element = document.getElementById(elementId);
    if (isValid) {
        element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + text;
        element.className = 'flex items-center text-green-600';
    } else {
        element.innerHTML = '<span class="mr-1 text-red-500">✗</span>' + text;
        element.className = 'flex items-center text-red-600';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('passwordConfirmation').value;
    const messageElement = document.getElementById('passwordMatchMessage');
    
    if (password === '' && confirmPassword === '') {
        messageElement.textContent = '';
        messageElement.className = 'text-sm mt-1';
        return;
    }
    
    if (confirmPassword === '') {
        messageElement.textContent = 'Please confirm your password';
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
    
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '🙈';
    } else {
        input.type = 'password';
        button.textContent = '👁️';
    }
}

// Image preview function
function previewImage(event) {
    const output = document.getElementById('preview');
    output.src = URL.createObjectURL(event.target.files[0]);
    output.onload = () => URL.revokeObjectURL(output.src); // free memory
}

// Add form validation
document.getElementById('athleteProfileForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('passwordConfirmation').value;
    
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

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    checkPasswordStrength();
    checkPasswordMatch();
});
</script>

<style>
#passwordCriteria div {
    transition: color 0.3s ease;
}

#passwordStrengthBar {
    transition: width 0.3s ease, background-color 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    #passwordCriteria {
        grid-template-columns: 1fr;
    }
}

#preview {
    max-width: 150px;
    max-height: 150px;
    object-fit: cover;
    border-radius: 8px;
}
</style>
@endsection