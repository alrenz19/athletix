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
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
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
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600 hover:text-gray-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
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
                <!-- Full Name now takes half width instead of full -->
                <div>
                    <label class="block text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', $athlete?->full_name) }}" class="w-full border rounded p-2">
                    @error('full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <!-- School ID aligned next to Full Name -->
                <div>
                    <label class="block text-gray-700 mb-2">School ID</label>
                    <input type="text" readonly name="school_id" value="{{ old('school_id', $athlete?->school_id) }}" class="w-full border rounded p-2 bg-gray-50">
                    @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">School ID cannot be changed</p>
                </div>

                <!-- Profile Image -->
                <div class="md:col-span-2 mb-3">
                    <label for="profile_image" class="block text-gray-700 mb-2">Profile Image</label>
                    
                    <div id="currentImageContainer" class="mb-4">
                        @php
                            $hasImage = false;
                            $imageUrl = '';
                            
                            if (isset($athlete) && $athlete->profile_url) {
                                $hasImage = true;
                                $imageUrl = $athlete->profile_url;
                            } elseif (isset($athlete) && $athlete->profile_image) {
                                $hasImage = true;
                                $imageUrl = asset('storage/'.$athlete->profile_image);
                            }
                        @endphp
                        
                        @if($hasImage)
                            <div class="relative inline-block">
                                <img id="currentImage" src="{{ $imageUrl }}" alt="Current Profile Image" 
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                                <div class="mt-2 text-sm text-gray-600">
                                    Current profile image
                                </div>
                            </div>
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500">No profile image</span>
                            </div>
                        @endif
                    </div>

                    <!-- New Image Upload -->
                    <div class="mt-1">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-500 transition-colors duration-200">
                            <div id="uploadArea" class="cursor-pointer">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600">Click to upload a new profile image</p>
                                <p class="text-xs text-gray-500 mt-1">Supported formats: JPG, JPEG, PNG, WEBP (Max: 2MB)</p>
                                <input type="file" name="profile_image" id="profile_image" 
                                       class="hidden" 
                                       accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                       onchange="validateAndPreviewImage(this)">
                            </div>
                        </div>
                        
                        <!-- Error Message -->
                        <div id="imageError" class="text-red-500 text-sm mt-2 hidden"></div>
                        
                        <!-- Preview Container -->
                        <div id="imagePreviewContainer" class="mt-4 hidden">
                            <p class="text-sm text-gray-700 mb-2">New Image Preview:</p>
                            <div class="relative inline-block">
                                <img id="imagePreview" src="" alt="Image Preview" 
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500">
                                <button type="button" onclick="removeImagePreview()" 
                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                                    ×
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    @error('profile_image') 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Birthdate</label>
                    <input type="date" name="birthdate" id="birthdate" 
                           value="{{ old('birthdate', $athlete?->birthdate) }}" 
                           class="w-full border rounded p-2"
                           onchange="calculateAge()">
                    @error('birthdate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Age</label>
                    <input type="number" name="age" id="age" 
                           value="{{ old('age', $athlete?->age) }}" 
                           class="w-full border rounded p-2 bg-gray-50" 
                           readonly>
                    @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Automatically calculated from birthdate</p>
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
            <button type="submit" id="submitButton" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
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
    
    if (input && button) {
        if (input.type === 'password') {
            input.type = 'text';
            // Change to eye-slash icon
            button.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
                </svg>
            `;
        } else {
            input.type = 'password';
            // Change back to eye icon
            button.innerHTML = `
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                </svg>
            `;
        }
    }
}

// Calculate age from birthdate
function calculateAge() {
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');
    
    if (!birthdateInput.value) {
        ageInput.value = '';
        return;
    }
    
    const birthDate = new Date(birthdateInput.value);
    const today = new Date();
    
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    // If birthday hasn't occurred yet this year, subtract 1
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    // Validate age range (15-40 as per original min/max attributes)
    if (age < 15) {
        ageInput.value = '';
        alert('Athlete must be at least 15 years old');
        birthdateInput.focus();
        birthdateInput.value = '';
        return;
    }
    
    if (age > 40) {
        ageInput.value = '';
        alert('Athlete must be 40 years old or younger');
        birthdateInput.focus();
        birthdateInput.value = '';
        return;
    }
    
    ageInput.value = age;
}

// Image upload validation and preview
function validateAndPreviewImage(input) {
    const file = input.files[0];
    const errorElement = document.getElementById('imageError');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const previewImage = document.getElementById('imagePreview');
    const uploadArea = document.getElementById('uploadArea');
    const submitButton = document.getElementById('submitButton');
    
    // Reset states
    errorElement.classList.add('hidden');
    errorElement.textContent = '';
    uploadArea.classList.remove('border-red-500');
    
    if (!file) {
        previewContainer.classList.add('hidden');
        return;
    }
    
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
    
    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        errorElement.textContent = 'Invalid file type. Please upload only JPG, JPEG, PNG, or WEBP images.';
        errorElement.classList.remove('hidden');
        uploadArea.classList.add('border-red-500');
        input.value = '';
        previewContainer.classList.add('hidden');
        submitButton.disabled = true;
        return;
    }
    
    // Check file size (2MB max - matching controller validation)
    const maxSize = 2 * 1024 * 1024; // 2MB in bytes
    if (file.size > maxSize) {
        errorElement.textContent = 'File size too large. Maximum size is 2MB.';
        errorElement.classList.remove('hidden');
        uploadArea.classList.add('border-red-500');
        input.value = '';
        previewContainer.classList.add('hidden');
        submitButton.disabled = true;
        return;
    }
    
    // Valid file - show preview
    errorElement.classList.add('hidden');
    uploadArea.classList.remove('border-red-500');
    submitButton.disabled = false;
    
    // Create preview
    const reader = new FileReader();
    reader.onload = function(e) {
        previewImage.src = e.target.result;
        previewContainer.classList.remove('hidden');
    }
    reader.readAsDataURL(file);
}

function removeImagePreview() {
    const input = document.getElementById('profile_image');
    const previewContainer = document.getElementById('imagePreviewContainer');
    const errorElement = document.getElementById('imageError');
    
    input.value = '';
    previewContainer.classList.add('hidden');
    errorElement.classList.add('hidden');
}

// Make upload area clickable
document.getElementById('uploadArea').addEventListener('click', function() {
    document.getElementById('profile_image').click();
});

// Add form validation
document.getElementById('athleteProfileForm').addEventListener('submit', function(event) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('passwordConfirmation').value;
    
    // Check file input if there's a file selected
    const fileInput = document.getElementById('profile_image');
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        const maxSize = 2 * 1024 * 1024;
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            event.preventDefault();
            alert('Invalid file type. Please upload only JPG, JPEG, PNG, or WEBP images.');
            return false;
        }
        
        if (file.size > maxSize) {
            event.preventDefault();
            alert('File size too large. Maximum size is 2MB.');
            return false;
        }
    }
    
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
    
    // Calculate age if birthdate is already set
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput && birthdateInput.value) {
        calculateAge();
    }
    
    // Also trigger calculation when the form is loaded with old input
    if (birthdateInput && birthdateInput.value) {
        setTimeout(() => {
            calculateAge();
        }, 100);
    }
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

/* Image preview styling */
#imagePreview {
    max-width: 150px;
    max-height: 150px;
    object-fit: cover;
}

/* Upload area hover effect */
#uploadArea:hover {
    background-color: #f9fafb;
}

/* Age field styling to indicate it's read-only */
#age {
    background-color: #f9fafb;
    cursor: not-allowed;
}
</style>
@endsection