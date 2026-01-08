@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>

    @if(session('success'))
        <div class="bg-green-500 text-white p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('staff.profile.update') }}" id="profileForm">
        @csrf
        @method('PATCH')

        <!-- Username -->
        <div class="mb-3">
            <label class="block text-gray-700">Username</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                class="w-full border rounded p-2">
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label class="block text-gray-700">Password (leave blank if unchanged)</label>
            <input type="password" name="password" id="password" 
                class="w-full border rounded p-2"
                placeholder="Enter new password"
                onkeyup="checkPasswordStrength()">
            
            <div class="mt-2">
                <div class="flex items-center mb-1">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="passwordStrengthBar" class="h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                    <span id="passwordStrengthText" class="ml-2 text-sm">No password</span>
                </div>
                <div id="passwordCriteria" class="text-xs text-gray-600 space-y-1 mt-2">
                    <div id="lengthCheck" class="flex items-center">
                        <span class="mr-1">⬜</span> At least 8 characters
                    </div>
                    <div id="lowercaseCheck" class="flex items-center">
                        <span class="mr-1">⬜</span> Contains lowercase letter
                    </div>
                    <div id="uppercaseCheck" class="flex items-center">
                        <span class="mr-1">⬜</span> Contains uppercase letter
                    </div>
                    <div id="numberCheck" class="flex items-center">
                        <span class="mr-1">⬜</span> Contains number
                    </div>
                    <div id="specialCheck" class="flex items-center">
                        <span class="mr-1">⬜</span> Contains special character (!@#$%^&*)
                    </div>
                </div>
            </div>
            
            <input type="password" name="password_confirmation" id="passwordConfirmation"
                placeholder="Confirm password"
                class="w-full border rounded p-2 mt-2"
                onkeyup="checkPasswordMatch()">
            <div id="passwordMatchMessage" class="text-sm mt-1"></div>
        </div>

        <!-- Full Name -->
        <div class="mb-3">
            <label class="block text-gray-700">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name', $staff->full_name) }}"
                class="w-full border rounded p-2">
        </div>

        <!-- Position -->
        <div class="mb-3">
            <label class="block text-gray-700">Position</label>
            <input type="text" name="position" value="{{ old('position', $staff->position) }}"
                class="w-full border rounded p-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            Save Changes
        </button>
    </form>
</div>

<script>
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    
    // Reset checks
    document.getElementById('lengthCheck').innerHTML = '<span class="mr-1">⬜</span> At least 8 characters';
    document.getElementById('lowercaseCheck').innerHTML = '<span class="mr-1">⬜</span> Contains lowercase letter';
    document.getElementById('uppercaseCheck').innerHTML = '<span class="mr-1">⬜</span> Contains uppercase letter';
    document.getElementById('numberCheck').innerHTML = '<span class="mr-1">⬜</span> Contains number';
    document.getElementById('specialCheck').innerHTML = '<span class="mr-1">⬜</span> Contains special character (!@#$%^&*)';
    
    if (password.length === 0) {
        strengthBar.style.width = '0%';
        strengthBar.className = 'h-2.5 rounded-full bg-gray-300';
        strengthText.textContent = 'No password';
        strengthText.className = 'ml-2 text-sm text-gray-600';
        return;
    }
    
    let score = 0;
    let totalChecks = 5;
    
    // Check criteria
    const hasLength = password.length >= 8;
    const hasLowercase = /[a-z]/.test(password);
    const hasUppercase = /[A-Z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*]/.test(password);
    
    // Update checkmarks
    updateCheck('lengthCheck', hasLength);
    updateCheck('lowercaseCheck', hasLowercase);
    updateCheck('uppercaseCheck', hasUppercase);
    updateCheck('numberCheck', hasNumber);
    updateCheck('specialCheck', hasSpecial);
    
    // Calculate score
    score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
        .filter(Boolean).length;
    
    // Calculate percentage
    const percentage = (score / totalChecks) * 100;
    strengthBar.style.width = percentage + '%';
    
    // Update strength indicator
    if (score === 0) {
        strengthBar.className = 'h-2.5 rounded-full bg-gray-300';
        strengthText.textContent = 'Very Weak';
        strengthText.className = 'ml-2 text-sm text-red-600';
    } else if (score <= 2) {
        strengthBar.className = 'h-2.5 rounded-full bg-red-500';
        strengthText.textContent = 'Weak';
        strengthText.className = 'ml-2 text-sm text-red-500';
    } else if (score === 3) {
        strengthBar.className = 'h-2.5 rounded-full bg-yellow-500';
        strengthText.textContent = 'Fair';
        strengthText.className = 'ml-2 text-sm text-yellow-600';
    } else if (score === 4) {
        strengthBar.className = 'h-2.5 rounded-full bg-blue-500';
        strengthText.textContent = 'Good';
        strengthText.className = 'ml-2 text-sm text-blue-600';
    } else {
        strengthBar.className = 'h-2.5 rounded-full bg-green-500';
        strengthText.textContent = 'Strong';
        strengthText.className = 'ml-2 text-sm text-green-600';
    }
    
    // Also check password match
    checkPasswordMatch();
}

function updateCheck(elementId, isValid) {
    const element = document.getElementById(elementId);
    if (isValid) {
        element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + element.textContent.substring(element.textContent.indexOf(' ') + 1);
        element.className = 'flex items-center text-green-600';
    } else {
        element.innerHTML = '<span class="mr-1">❌</span>' + element.textContent.substring(element.textContent.indexOf(' ') + 1);
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

// Add form validation
document.getElementById('profileForm').addEventListener('submit', function(event) {
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
    
    // Add show/hide password toggle
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('passwordConfirmation');
    
    // Create show/hide buttons
    const togglePassword = createToggleButton(passwordInput);
    const toggleConfirm = createToggleButton(confirmInput);
    
    passwordInput.parentNode.insertBefore(togglePassword, passwordInput.nextSibling);
    confirmInput.parentNode.insertBefore(toggleConfirm, confirmInput.nextSibling);
});

function createToggleButton(inputElement) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-600';
    button.innerHTML = '👁️';
    button.style.cssText = 'background: none; border: none; cursor: pointer; font-size: 16px;';
    
    // Position parent
    inputElement.parentNode.style.position = 'relative';
    
    button.addEventListener('click', function() {
        if (inputElement.type === 'password') {
            inputElement.type = 'text';
            button.innerHTML = '🙈';
        } else {
            inputElement.type = 'password';
            button.innerHTML = '👁️';
        }
    });
    
    return button;
}
</script>

<style>
/* Additional styling */
#passwordStrengthBar {
    transition: width 0.3s ease, background-color 0.3s ease;
}

#passwordCriteria div {
    transition: color 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .max-w-xl {
        margin: 1rem;
        padding: 1rem;
    }
}
</style>
@endsection