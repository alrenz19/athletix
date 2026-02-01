<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AthletiX - Login</title>
    <link rel="icon" href="https://c.animaapp.com/mevbdbzo2I14VB/img/logo.png" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      .password-strength-bar {
        transition: width 0.3s ease, background-color 0.3s ease;
      }
      .criteria-check {
        transition: color 0.3s ease;
      }
      .file-input-error {
        border-color: #dc2626;
        animation: shake 0.5s;
      }
      @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
      }
    </style>
  </head>
  <body class="bg-[#ffffffde]">
    <main class="flex justify-center items-center min-h-screen p-4">
      <!-- Outer Card -->
      <div class="bg-white w-full max-w-5xl rounded-lg flex justify-center items-center">
        
        <!-- Login Section -->
        <section
          class="border border-[#8c2c08] rounded-xl shadow-md bg-[#fffbfa] flex flex-col md:flex-row w-full max-w-3xl overflow-hidden"
        >
          <!-- Left Side -->
          <div
            class="md:w-1/2 w-full h-48 md:h-auto relative bg-cover bg-no-repeat bg-center"
            style="background-image: url('/images/logoBackground.png');"
          >
            <img
              src="https://c.animaapp.com/mevbdbzo2I14VB/img/logo.png"
              alt="AthletiX logo"
              class="absolute w-28 h-28 md:w-40 md:h-40 top-4 left-1/2 -translate-x-1/2"
            />
            <h1
              class="absolute w-full text-center bottom-4 md:bottom-10 text-lg md:text-2xl text-black font-serif px-2"
            >
              Welcome to AthletiX!
            </h1>
          </div>

          <!-- Forms Container -->
          <div class="relative md:w-1/2 w-full overflow-hidden">
            <div
              id="formsWrapper"
              class="flex w-[200%] transition-transform duration-500"
            >
              <!-- Login Form -->
              <div class="w-1/2 p-6 flex flex-col justify-center shrink-0">
                <h2 class="text-xl font-semibold text-[#8c2c08] mb-6 text-center">
                  Log-In
                </h2>
                <form id="login-form" method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                  @csrf
                  <!-- Username -->
                  <div>
                    <div class="flex items-center border border-[#8c2c08] rounded px-2">
                      <input
                        type="email"
                        name="username"
                        id="username"
                        placeholder="Email"
                        required
                        class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                      />
                    </div>
                    <div id="username-error" class="text-red-500 text-sm mt-2"></div>
                  </div>

                  <!-- Password -->
                  <div>
                    <div class="flex items-center border border-[#8c2c08] rounded px-2 relative">
                      <input
                        type="password"
                        name="password"
                        id="loginPassword"
                        placeholder="Password"
                        required
                        class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none pr-10"
                      />
                      <button type="button" 
                              onclick="togglePasswordVisibility('loginPassword', 'loginToggle')"
                              id="loginToggle"
                              class="absolute right-3 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                      </button>
                    </div>
                    <div id="password-error" class="text-red-500 text-sm mt-2"></div>
                  </div>

                  <!-- Submit -->
                  <button
                    type="submit"
                    class="bg-[#8c2c08] text-white py-2 rounded-full border border-[#8c2c08] hover:bg-[#7a2507] transition duration-200"
                  >
                    Log-In
                  </button>

                  <p class="text-sm text-center text-gray-600 mt-2">
                    Don't have an account?
                    <a href="#" id="showSignup" class="text-[#8c2c08] font-semibold hover:underline">Click here</a>
                  </p>
                </form>
              </div>

              <!-- Sign Up Form -->
              <div class="w-1/2 p-6 flex flex-col justify-center shrink-0">
                <form id="signup-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                  @csrf

                  <!-- School ID Upload + OCR -->
                  <div class="flex flex-col border border-[#8c2c08] rounded px-2 py-2">
                    <label class="text-sm text-gray-700 font-semibold mb-1">Upload CTU School ID</label>
                    <div id="fileUploadContainer" class="mb-2">
                      <input type="file" 
                             id="school_id_image" 
                             name="school_id_image" 
                             accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" 
                             class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-[#8c2c08] file:text-white hover:file:bg-[#7a2507] file:cursor-pointer"
                             onchange="validateFileUpload(this)"
                      />
                      <div id="fileError" class="text-red-500 text-xs mt-1 hidden"></div>
                      <div id="filePreview" class="mt-2 hidden">
                        <img id="previewImage" class="max-w-full max-h-40 rounded border" alt="Preview">
                        <button type="button" onclick="removeFilePreview()" class="mt-1 text-xs text-red-600 hover:text-red-800">
                          Remove image
                        </button>
                      </div>
                    </div>
                    <button type="button" id="scanOCR"
                      class="mt-1 bg-[#8c2c08] text-white py-1 rounded hover:bg-[#7a2507] transition disabled:opacity-50 disabled:cursor-not-allowed">
                      📷 Scan ID
                    </button>
                    <p id="ocr-status" class="text-xs text-gray-600 mt-1"></p>
                  </div>

                  <!-- Full Name -->
                  <div class="flex items-center border border-[#8c2c08] rounded px-2">
                    <input
                      type="text"
                      id="full_name"
                      name="full_name"
                      placeholder="Full Name"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- School ID -->
                  <div class="flex items-center border border-[#8c2c08] rounded px-2">
                    <input
                      type="text"
                      id="school_id"
                      name="school_id"
                      placeholder="School ID"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- Email -->
                  <div class="flex items-center border border-[#8c2c08] rounded px-2">
                    <input
                      type="email"
                      name="username"
                      id="signupEmail"
                      placeholder="Email"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- Password -->
                  <div>
                    <div class="flex items-center border border-[#8c2c08] rounded px-2 relative">
                      <input
                        type="password"
                        name="password"
                        id="signupPassword"
                        placeholder="Password"
                        required
                        class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none pr-10"
                        onkeyup="checkSignupPasswordStrength()"
                      />
                      <button type="button" 
                              onclick="togglePasswordVisibility('signupPassword', 'signupToggle')"
                              id="signupToggle"
                              class="absolute right-3 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                      </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                      <div class="flex items-center mb-1">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                          <div id="signupStrengthBar" class="h-2 rounded-full password-strength-bar" style="width: 0%"></div>
                        </div>
                        <span id="signupPasswordStrength" class="ml-2 text-sm">password</span>
                      </div>
                    </div>
                  </div>

                  <!-- Confirm Password -->
                  <div>
                    <div class="flex items-center border border-[#8c2c08] rounded px-2 relative">
                      <input
                        type="password"
                        name="password_confirmation"
                        id="signupPasswordConfirm"
                        placeholder="Confirm Password"
                        required
                        class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none pr-10"
                        onkeyup="checkSignupPasswordMatch()"
                      />
                      <button type="button" 
                              onclick="togglePasswordVisibility('signupPasswordConfirm', 'signupConfirmToggle')"
                              id="signupConfirmToggle"
                              class="absolute right-3 text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                          <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                      </button>
                    </div>
                    <div id="signupPasswordMatch" class="text-sm mt-1"></div>
                  </div>

                  <!-- Submit -->
                  <button
                    type="submit"
                    id="signupSubmit"
                    class="bg-[#8c2c08] text-white py-2 rounded-full border border-[#8c2c08] hover:bg-[#7a2507] transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Sign-Up
                  </button>

                  <p class="text-sm text-center text-gray-600 mt-2">
                    Already have an account?
                    <a href="#" id="showLogin" class="text-[#8c2c08] font-semibold hover:underline">Log-In</a>
                  </p>
                </form>
              </div>

            </div>
          </div>
        </section>
      </div>
    </main>

    <!-- Password Tips Modal -->
    <div id="passwordTipsModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-lg text-[#8c2c08]">Password Security Tips</h3>
                <button onclick="closeModal('passwordTipsModal')" class="text-gray-600 hover:text-gray-800 text-xl">&times;</button>
            </div>
            <div class="space-y-3 text-sm">
                <p><strong class="text-[#8c2c08]">✓ Strong passwords should include:</strong></p>
                <ul class="list-disc pl-5 space-y-1 text-gray-700">
                    <li>At least 8 characters (12+ recommended)</li>
                    <li>Mix of uppercase and lowercase letters</li>
                    <li>Numbers (0-9)</li>
                    <li>Special characters (!@#$%^&* etc.)</li>
                    <li>Avoid common words or personal information</li>
                </ul>
                <p><strong class="text-[#8c2c08]">✓ Best practices:</strong></p>
                <ul class="list-disc pl-5 space-y-1 text-gray-700">
                    <li>Use a different password for each account</li>
                    <li>Consider using a password manager</li>
                    <li>Change passwords regularly</li>
                    <li>Never share passwords via email or chat</li>
                </ul>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-yellow-800"><strong>Note:</strong> Click "Generate Secure Password" for a strong, random password.</p>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" class="px-4 py-2 bg-[#8c2c08] text-white rounded hover:bg-[#7a2507]" onclick="closeModal('passwordTipsModal')">Close</button>
            </div>
        </div>
    </div>

    <script>
      const formsWrapper = document.getElementById("formsWrapper");
      const showSignup = document.getElementById("showSignup");
      const showLogin = document.getElementById("showLogin");

      showSignup.addEventListener("click", (e) => {
        e.preventDefault();
        formsWrapper.style.transform = "translateX(-50%)";
        // Initialize password strength when switching to signup
        setTimeout(() => {
          checkSignupPasswordStrength();
          checkSignupPasswordMatch();
        }, 100);
      });

      showLogin.addEventListener("click", (e) => {
        e.preventDefault();
        formsWrapper.style.transform = "translateX(0)";
      });

      // File upload validation
      function validateFileUpload(input) {
        const file = input.files[0];
        const errorElement = document.getElementById('fileError');
        const filePreview = document.getElementById('filePreview');
        const previewImage = document.getElementById('previewImage');
        const scanBtn = document.getElementById('scanOCR');
        const signupBtn = document.getElementById('signupSubmit');
        
        // Reset states
        errorElement.classList.add('hidden');
        input.classList.remove('file-input-error');
        
        if (!file) {
          filePreview.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = false;
          return;
        }
        
        // Check file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
          errorElement.textContent = 'Invalid file type. Please upload only JPG, JPEG, PNG, or WEBP images.';
          errorElement.classList.remove('hidden');
          input.classList.add('file-input-error');
          input.value = '';
          filePreview.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = true;
          return;
        }
        
        // Check file size (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        if (file.size > maxSize) {
          errorElement.textContent = 'File size too large. Maximum size is 5MB.';
          errorElement.classList.remove('hidden');
          input.classList.add('file-input-error');
          input.value = '';
          filePreview.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = true;
          return;
        }
        
        // Valid file - show preview and enable buttons
        errorElement.classList.add('hidden');
        input.classList.remove('file-input-error');
        scanBtn.disabled = false;
        signupBtn.disabled = false;
        
        // Show preview for image files
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function(e) {
            previewImage.src = e.target.result;
            filePreview.classList.remove('hidden');
          }
          reader.readAsDataURL(file);
        }
      }
      
      function removeFilePreview() {
        const input = document.getElementById('school_id_image');
        const filePreview = document.getElementById('filePreview');
        const scanBtn = document.getElementById('scanOCR');
        const signupBtn = document.getElementById('signupSubmit');
        
        input.value = '';
        filePreview.classList.add('hidden');
        scanBtn.disabled = true;
        signupBtn.disabled = false;
        
        // Also clear OCR status
        document.getElementById('ocr-status').innerText = '';
      }

      // Password visibility toggle
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

      // Password strength checker for signup
      function checkSignupPasswordStrength() {
        const password = document.getElementById('signupPassword').value;
        const strengthBar = document.getElementById('signupStrengthBar');
        const strengthText = document.getElementById('signupPasswordStrength');
        
        // Reset checks
        resetCheck('signupLength', '8+ characters');
        resetCheck('signupLowercase', 'Lowercase letter');
        resetCheck('signupUppercase', 'Uppercase letter');
        resetCheck('signupNumber', 'Contains number');
        resetCheck('signupSpecial', 'Special character');
        
        if (password.length === 0) {
          strengthBar.style.width = '0%';
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-gray-300';
          strengthText.textContent = 'weak';
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
        updateCheck('signupLength', hasLength, '8+ characters');
        updateCheck('signupLowercase', hasLowercase, 'Lowercase letter');
        updateCheck('signupUppercase', hasUppercase, 'Uppercase letter');
        updateCheck('signupNumber', hasNumber, 'Contains number');
        updateCheck('signupSpecial', hasSpecial, 'Special character');
        
        // Calculate score
        score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
          .filter(Boolean).length;
        
        // Calculate percentage
        const percentage = (score / totalChecks) * 100;
        strengthBar.style.width = percentage + '%';
        
        // Update strength indicator
        if (score === 0) {
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-gray-300';
          strengthText.textContent = 'Very Weak';
          strengthText.className = 'ml-2 text-sm text-red-600';
        } else if (score <= 2) {
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-red-500';
          strengthText.textContent = 'Weak';
          strengthText.className = 'ml-2 text-sm text-red-500';
        } else if (score === 3) {
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-yellow-500';
          strengthText.textContent = 'Fair';
          strengthText.className = 'ml-2 text-sm text-yellow-600';
        } else if (score === 4) {
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-blue-500';
          strengthText.textContent = 'Good';
          strengthText.className = 'ml-2 text-sm text-blue-600';
        } else {
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-green-500';
          strengthText.textContent = 'Strong';
          strengthText.className = 'ml-2 text-sm text-green-600';
        }
        
        // Also check password match
        checkSignupPasswordMatch();
      }

      function resetCheck(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
          element.innerHTML = '<span class="mr-1">⬜</span>' + text;
          element.className = 'flex items-center criteria-check text-gray-600';
        }
      }

      function updateCheck(elementId, isValid, text) {
        const element = document.getElementById(elementId);
        if (element) {
          if (isValid) {
            element.innerHTML = '<span class="mr-1 text-green-500">✓</span>' + text;
            element.className = 'flex items-center criteria-check text-green-600';
          } else {
            element.innerHTML = '<span class="mr-1 text-red-500">✗</span>' + text;
            element.className = 'flex items-center criteria-check text-red-600';
          }
        }
      }

      function checkSignupPasswordMatch() {
        const password = document.getElementById('signupPassword').value;
        const confirmPassword = document.getElementById('signupPasswordConfirm').value;
        const messageElement = document.getElementById('signupPasswordMatch');
        
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
          messageElement.textContent = '✓ Passwords match';
          messageElement.className = 'text-sm mt-1 text-green-600';
        } else {
          messageElement.textContent = '✗ Passwords do not match';
          messageElement.className = 'text-sm mt-1 text-red-600';
        }
      }

      // Password generation
      function generateSignupPassword(length = 12) {
        const upper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        const lower = "abcdefghijklmnopqrstuvwxyz";
        const numbers = "0123456789";
        const symbols = "!@#$%^&*()_+-=[]{}|;:,.<>?";

        const allChars = upper + lower + numbers + symbols;

        let password = [
          upper[randomIndex(upper)],
          lower[randomIndex(lower)],
          numbers[randomIndex(numbers)],
          symbols[randomIndex(symbols)]
        ];

        for (let i = password.length; i < length; i++) {
          password.push(allChars[randomIndex(allChars)]);
        }

        // Shuffle the password array
        password = shuffleArray(password);

        const passwordField = document.getElementById('signupPassword');
        passwordField.value = password.join('');
        passwordField.type = 'text';
        
        // Update toggle button
        const toggleBtn = document.getElementById('signupToggle');
        if (toggleBtn) {
          toggleBtn.innerHTML = `
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
              <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
            </svg>
          `;
        }
        
        // Trigger strength check
        checkSignupPasswordStrength();
        
        // Also update confirm field if empty
        const confirmField = document.getElementById('signupPasswordConfirm');
        if (!confirmField.value) {
          confirmField.value = password.join('');
          confirmField.type = 'text';
          const confirmToggleBtn = document.getElementById('signupConfirmToggle');
          if (confirmToggleBtn) {
            confirmToggleBtn.innerHTML = `
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
              </svg>
            `;
          }
          checkSignupPasswordMatch();
        }
      }

      function randomIndex(str) {
        return crypto.getRandomValues(new Uint32Array(1))[0] % str.length;
      }

      function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
      }

      // Modal functions
      function showPasswordTips() {
        document.getElementById('passwordTipsModal').classList.remove('hidden');
      }

      function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
      }

      // Form validation
      document.getElementById('signup-form')?.addEventListener('submit', function(event) {
        // Check if valid image is uploaded
        const fileInput = document.getElementById('school_id_image');
        const file = fileInput.files[0];
        
        if (file) {
          // Validate file type on submission (extra safety)
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
          const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
          const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
          
          if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
            event.preventDefault();
            alert('Invalid file type. Please upload only JPG, JPEG, PNG, or WEBP images.');
            return false;
          }
          
          // Check file size (5MB max)
          const maxSize = 5 * 1024 * 1024;
          if (file.size > maxSize) {
            event.preventDefault();
            alert('File size too large. Maximum size is 5MB.');
            return false;
          }
        }
        
        // Check password strength
        const password = document.getElementById('signupPassword').value;
        const confirmPassword = document.getElementById('signupPasswordConfirm').value;
        
        const hasLength = password.length >= 8;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        if (!hasLength || !hasLowercase || !hasUppercase || !hasNumber) {
          event.preventDefault();
          alert('Password must be at least 8 characters long and contain uppercase, lowercase letters, and numbers.');
          return false;
        }
        
        if (password !== confirmPassword) {
          event.preventDefault();
          alert('Passwords do not match. Please confirm your password.');
          return false;
        }
        
        return true;
      });

      // Initialize when page loads
      document.addEventListener('DOMContentLoaded', function() {
        checkSignupPasswordStrength();
        checkSignupPasswordMatch();
        
        // Disable scan button initially
        document.getElementById('scanOCR').disabled = true;
      });

      // Close modal when clicking outside
      window.onclick = function(event) {
        if (event.target === document.getElementById('passwordTipsModal')) {
          closeModal('passwordTipsModal');
        }
      };
    </script>

    <script>
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent normal form submission

        // Clear previous errors
        document.getElementById('username-error').innerText = '';
        document.getElementById('password-error').innerText = '';

        // Get form data
        let formData = new FormData(this);

        fetch("{{ route('login.attempt') }}", {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect on success
                window.location.href = data.redirect_url; // redirect to announcement page
            } else {
                // Display validation errors
                if (data.errors.username) {
                    document.getElementById('username-error').innerText = data.errors.username[0];
                }
                if (data.errors.password) {
                    document.getElementById('password-error').innerText = data.errors.password[0];
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>

  <script>
  document.getElementById('signup-form').addEventListener('submit', function(e) {
      e.preventDefault();

      let formData = new FormData(this);

      fetch("{{ route('register') }}", {
          method: 'POST',
          body: formData,
          headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'  // 👈 this forces JSON instead of redirect
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
              // ✅ redirect straight to OTP page
              window.location.href = data.redirect_url;
          } else {
              alert(data.message);
          }
      })
      .catch(error => {
          console.error('Error:', error);
      });
  });
  </script>
<script>
document.getElementById('scanOCR').addEventListener('click', function () {
  const fileInput = document.getElementById('school_id_image');
  const status = document.getElementById('ocr-status');
  const fullName = document.getElementById('full_name');
  const schoolId = document.getElementById('school_id');
  const scanBtn = this;

  if (!fileInput.files.length) {
    alert('Please upload your CTU ID image first.');
    return;
  }

  // Double-check file type before scanning
  const file = fileInput.files[0];
  const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
  const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
  const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
  
  if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
    alert('Invalid file type for OCR. Please upload only JPG, JPEG, PNG, or WEBP images.');
    return;
  }

  scanBtn.disabled = true;
  status.innerText = '🔍 Scanning ID... please wait.';

  const formData = new FormData();
  formData.append('school_id_image', fileInput.files[0]);

  fetch("{{ route('ocr.extract') }}", {
    method: 'POST',
    body: formData,
    headers: {
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content'),
      'Accept': 'application/json'
    }
  })
  .then(res => res.json())
  .then(data => {
    scanBtn.disabled = false;

    if (data.success) {
      fullName.value = data.full_name !== 'Not Detected' ? data.full_name : '';
      schoolId.value = data.school_id !== 'Not Detected' ? data.school_id : '';
      status.innerText = '✅ OCR complete! Please verify.';
    } else {
      status.innerText = '❌ OCR failed: ' + data.message;
    }
  })
  .catch(err => {
    scanBtn.disabled = false;
    console.error(err);
    status.innerText = '❌ OCR error occurred.';
  });
});
</script>

  </body>
</html>