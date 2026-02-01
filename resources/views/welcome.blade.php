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
      .loading-ocr {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #8c2c08;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
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
                      class="mt-1 bg-[#8c2c08] text-white py-1 rounded hover:bg-[#7a2507] transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                      <span id="scanText">📷 Scan ID</span>
                      <span id="scanLoader" class="loading-ocr hidden"></span>
                    </button>
                    <p id="ocr-status" class="text-xs mt-1"></p>
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

    <script>
      let currentEnhancement = 'original';
      let originalImageData = null;

      const formsWrapper = document.getElementById("formsWrapper");
      const showSignup = document.getElementById("showSignup");
      const showLogin = document.getElementById("showLogin");

      showSignup.addEventListener("click", (e) => {
        e.preventDefault();
        formsWrapper.style.transform = "translateX(-50%)";
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
        const enhancementOptions = document.getElementById('enhancementOptions');
        
        errorElement.classList.add('hidden');
        input.classList.remove('file-input-error');
        
        if (!file) {
          filePreview.classList.add('hidden');
          enhancementOptions.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = false;
          return;
        }
        
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
          errorElement.textContent = 'Invalid file type. Please upload only JPG, JPEG, PNG, or WEBP images.';
          errorElement.classList.remove('hidden');
          input.classList.add('file-input-error');
          input.value = '';
          filePreview.classList.add('hidden');
          enhancementOptions.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = true;
          return;
        }
        
        const maxSize = 5 * 1024 * 1024;
        if (file.size > maxSize) {
          errorElement.textContent = 'File size too large. Maximum size is 5MB.';
          errorElement.classList.remove('hidden');
          input.classList.add('file-input-error');
          input.value = '';
          filePreview.classList.add('hidden');
          enhancementOptions.classList.add('hidden');
          scanBtn.disabled = true;
          signupBtn.disabled = true;
          return;
        }
        
        errorElement.classList.add('hidden');
        input.classList.remove('file-input-error');
        scanBtn.disabled = false;
        signupBtn.disabled = false;
        enhancementOptions.classList.remove('hidden');
        
        // Store original image data for enhancement
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          originalImageData = e.target.result;
          filePreview.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
      }
      
      // Image enhancement for better OCR
      function applyImageEnhancement(type) {
        currentEnhancement = type;
        const previewImage = document.getElementById('previewImage');
        
        if (!originalImageData) return;
        
        // Reset to original first
        previewImage.src = originalImageData;
        
        if (type === 'grayscale') {
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          const img = new Image();
          
          img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            
            for (let i = 0; i < data.length; i += 4) {
              const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
              data[i] = avg;     // red
              data[i + 1] = avg; // green
              data[i + 2] = avg; // blue
            }
            
            ctx.putImageData(imageData, 0, 0);
            previewImage.src = canvas.toDataURL();
          };
          
          img.src = originalImageData;
        } else if (type === 'contrast') {
          const canvas = document.createElement('canvas');
          const ctx = canvas.getContext('2d');
          const img = new Image();
          
          img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            const contrast = 1.5;
            
            for (let i = 0; i < data.length; i += 4) {
              data[i] = ((data[i] - 128) * contrast) + 128;
              data[i + 1] = ((data[i + 1] - 128) * contrast) + 128;
              data[i + 2] = ((data[i + 2] - 128) * contrast) + 128;
              
              // Clamp values
              data[i] = Math.min(255, Math.max(0, data[i]));
              data[i + 1] = Math.min(255, Math.max(0, data[i + 1]));
              data[i + 2] = Math.min(255, Math.max(0, data[i + 2]));
            }
            
            ctx.putImageData(imageData, 0, 0);
            previewImage.src = canvas.toDataURL();
          };
          
          img.src = originalImageData;
        }
        
        // Update OCR status
        document.getElementById('ocr-status').innerText = `✅ Applied ${type} enhancement. Try scanning again.`;
        document.getElementById('ocr-status').className = 'text-xs mt-1 text-green-600';
      }
      
      function removeFilePreview() {
        const input = document.getElementById('school_id_image');
        const filePreview = document.getElementById('filePreview');
        const scanBtn = document.getElementById('scanOCR');
        const signupBtn = document.getElementById('signupSubmit');
        const enhancementOptions = document.getElementById('enhancementOptions');
        
        input.value = '';
        filePreview.classList.add('hidden');
        enhancementOptions.classList.add('hidden');
        scanBtn.disabled = true;
        signupBtn.disabled = false;
        originalImageData = null;
        currentEnhancement = 'original';
        
        document.getElementById('ocr-status').innerText = '';
      }

      // Password visibility toggle
      function togglePasswordVisibility(inputId, buttonId) {
        const input = document.getElementById(inputId);
        const button = document.getElementById(buttonId);
        
        if (input && button) {
          if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = `
              <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"/>
                <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"/>
              </svg>
            `;
          } else {
            input.type = 'password';
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
        
        if (password.length === 0) {
          strengthBar.style.width = '0%';
          strengthBar.className = 'h-2 rounded-full password-strength-bar bg-gray-300';
          strengthText.textContent = 'weak';
          strengthText.className = 'ml-2 text-sm text-gray-600';
          return;
        }
        
        let score = 0;
        const totalChecks = 5;
        
        const hasLength = password.length >= 8;
        const hasLowercase = /[a-z]/.test(password);
        const hasUppercase = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
        
        score = [hasLength, hasLowercase, hasUppercase, hasNumber, hasSpecial]
          .filter(Boolean).length;
        
        const percentage = (score / totalChecks) * 100;
        strengthBar.style.width = percentage + '%';
        
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
        
        checkSignupPasswordMatch();
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

      // Initialize when page loads
      document.addEventListener('DOMContentLoaded', function() {
        checkSignupPasswordStrength();
        checkSignupPasswordMatch();
        document.getElementById('scanOCR').disabled = true;
      });
    </script>

    <script>
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();

        document.getElementById('username-error').innerText = '';
        document.getElementById('password-error').innerText = '';

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
                window.location.href = data.redirect_url;
            } else {
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
              'Accept': 'application/json'
          }
      })
      .then(response => response.json())
      .then(data => {
          if (data.success) {
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
  document.getElementById('scanOCR').addEventListener('click', async function () {
    const fileInput = document.getElementById('school_id_image');
    const status = document.getElementById('ocr-status');
    const fullName = document.getElementById('full_name');
    const schoolId = document.getElementById('school_id');
    const scanBtn = this;
    const scanText = document.getElementById('scanText');
    const scanLoader = document.getElementById('scanLoader');

    if (!fileInput.files.length) {
        status.innerText = '❌ Please upload your CTU ID image first.';
        status.className = 'text-xs mt-1 text-red-600';
        return;
    }

    const file = fileInput.files[0];
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
    const allowedExtensions = ['.jpg', '.jpeg', '.png', '.webp'];
    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
    
    if (!allowedTypes.includes(file.type) && !allowedExtensions.includes(fileExtension)) {
        status.innerText = '❌ Invalid file type for OCR.';
        status.className = 'text-xs mt-1 text-red-600';
        return;
    }

    scanBtn.disabled = true;
    scanText.innerText = 'Scanning...';
    scanLoader.classList.remove('hidden');
    status.innerText = '🔍 Scanning ID... Processing image...';
    status.className = 'text-xs mt-1 text-blue-600';

    const formData = new FormData();
    formData.append('school_id_image', fileInput.files[0]);
    formData.append('enhancement', currentEnhancement);

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
    .then(res => {
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    })
    .then(data => {
        scanBtn.disabled = false;
        scanText.innerText = '📷 Scan ID';
        scanLoader.classList.add('hidden');

        // Process raw text if available
        let frontendExtracted = false;
        if (data.raw_text) {
            const lines = data.raw_text.split('\n').map(line => line.trim()).filter(line => line.length > 0);
            
            // Try to extract data from raw text using frontend parser
            const extractedData = extractDataFromOCR(lines);
            
            if (extractedData.full_name || extractedData.school_id) {
                frontendExtracted = true;
                
                // Update form fields with extracted data (frontend takes priority)
                if (extractedData.full_name && extractedData.full_name !== 'Not Detected') {
                    fullName.value = extractedData.full_name;
                }
                if (extractedData.school_id && extractedData.school_id !== 'Not Detected') {
                    schoolId.value = extractedData.school_id;
                }
            }
        }

        // If backend provided data and frontend didn't extract, use backend data
        if (!frontendExtracted && data.success) {
            if (data.full_name !== 'Not Detected') {
                fullName.value = data.full_name;
            }
            if (data.school_id !== 'Not Detected') {
                schoolId.value = data.school_id;
            }
        }

        // Determine final status message
        const finalName = fullName.value;
        const finalId = schoolId.value;
        
        if (finalName && finalId) {
            status.innerText = `✅ OCR successful! Name: ${finalName}, ID: ${finalId}`;
            status.className = 'text-xs mt-1 text-green-600';
        } else if (finalName || finalId) {
            status.innerText = `⚠️ Partial extraction. Name: ${finalName || 'N/A'}, ID: ${finalId || 'N/A'}`;
            status.className = 'text-xs mt-1 text-yellow-600';
        } else {
            status.innerText = '❌ No text detected. Try a clearer image or different enhancement.';
            status.className = 'text-xs mt-1 text-red-600';
        }
    })
    .catch(err => {
        scanBtn.disabled = false;
        scanText.innerText = '📷 Scan ID';
        scanLoader.classList.add('hidden');
        console.error('OCR Error:', err);
        status.innerText = '❌ OCR error occurred. Please try again.';
        status.className = 'text-xs mt-1 text-red-600';
    });
});

// Function to extract data from OCR raw text with improved pattern matching
function extractDataFromOCR(lines) {
    const result = {
        full_name: 'Not Detected',
        school_id: 'Not Detected',
    };
    
    // Clean lines - remove empty lines and trim
    const cleanLines = lines.map(line => line.trim()).filter(line => line.length > 0);
    
    // STRATEGY 1: Look for school ID patterns
    // Multiple patterns to catch different ID formats
    const idPatterns = [
        /ID\s*(?:NO\.?|NUMBER)?\s*:?\s*(\d{6,8})/i,  // "ID NO.: 1333788" or "ID NO:1333788"
        /ID\s*NO\.?\s*(\d{6,8})/i,                     // "ID NO.1333788"
        /ID\s*:\s*(\d{6,8})/i,                         // "ID: 1333788"
        /^(\d{6,8})$/,                                 // Just numbers on a line
        /NO[\.:]?\s*(\d{6,8})/i                        // "NO.:1333788" or "NO:1333788"
    ];
    
    // Check each line for ID patterns
    for (let i = 0; i < cleanLines.length; i++) {
        const line = cleanLines[i];
        
        for (const pattern of idPatterns) {
            const match = line.match(pattern);
            if (match && match[1]) {
                result.school_id = match[1];
                break;
            }
        }
        
        // Check if this line is just numbers and next line has "ID" or previous line has "ID"
        if (line.match(/^\d{6,8}$/)) {
            // Check previous line for ID reference
            if (i > 0 && cleanLines[i-1].toUpperCase().includes('ID')) {
                result.school_id = line;
                break;
            }
            // Check if line looks like it could be an ID (6-8 digits)
            else if (line.length >= 6 && line.length <= 8 && /^\d+$/.test(line)) {
                result.school_id = line;
                break;
            }
        }
        
        if (result.school_id !== 'Not Detected') break;
    }
    
    // STRATEGY 2: Look for full name patterns
    // CTU IDs usually have names in specific formats
    
    // Pattern 1: Two words with capital letters (First Last)
    const namePattern1 = /^[A-Z][a-z]+ [A-Z][a-z]+$/;
    // Pattern 2: Multiple words with capital letters
    const namePattern2 = /^[A-Z][a-z]+(?: [A-Z][a-z]+)+$/;
    // Pattern 3: All caps name (FIRST LAST)
    const namePattern3 = /^[A-Z]+ [A-Z]+$/;
    // Pattern 4: Name with middle initial (First M. Last)
    const namePattern4 = /^[A-Z][a-z]+ [A-Z]\. [A-Z][a-z]+$/;
    
    // Common non-name words to exclude
    const excludeWords = ['UNIVERSITY', 'TECHNOLOGICAL', 'CEBU', 'CAMPUS', 'AVENUE', 
                         'STREET', 'CITY', 'PHILIPPINES', 'REPUBLIC', 'MAIN', 'CORNER',
                         'COURSE', 'SYSTEM', 'MANAGEMENT', 'CERTIFIED', 'CERTIFILO',
                         'TUV', 'RHEINLAND', 'ISO', 'BSIS', 'BSMX', 'ID', 'NO', 'BAN',
                         'CAFÉS', 'QUẢNG', 'NINH', 'SOUTHERN', 'VIETNAM', 'TELEPHONE',
                         'CEBA', 'CESE', 'TECHNOEARIE', 'INVERSITY', 'BAALAMPN', 'MT'];
    
    for (let i = 0; i < cleanLines.length; i++) {
        const line = cleanLines[i];
        
        // Skip lines that are obviously not names
        if (excludeWords.some(word => line.toUpperCase().includes(word))) {
            continue;
        }
        
        // Check if line matches name patterns
        if (namePattern1.test(line) || namePattern2.test(line) || 
            namePattern3.test(line) || namePattern4.test(line)) {
            
            // Additional validation: name shouldn't be too short or too long
            const words = line.split(' ');
            if (words.length >= 2 && words.length <= 4) {
                // Check if it looks like a real name (not random OCR garbage)
                const hasReasonableLength = words.every(word => word.length >= 2 && word.length <= 15);
                const hasNoNumbers = !/\d/.test(line);
                
                if (hasReasonableLength && hasNoNumbers) {
                    result.full_name = line;
                    break;
                }
            }
        }
        
        // Special case: Names split across lines (like "EURIES P." and "ARCHES")
        if (i + 1 < cleanLines.length) {
            const currentLine = line;
            const nextLine = cleanLines[i + 1];
            
            // Check if current line looks like first part of name and next line looks like last name
            if (currentLine.match(/^[A-Z][A-Za-z]+ [A-Z]\.?$/) && 
                nextLine.match(/^[A-Z][A-Za-z]+$/)) {
                const combinedName = `${currentLine} ${nextLine}`;
                result.full_name = combinedName;
                break;
            }
        }
    }
    
    // STRATEGY 3: Fallback - look for lines that could be names based on position
    if (result.full_name === 'Not Detected') {
        // Try to find lines that are likely names based on their position
        // In CTU IDs, names often appear after university name and before course/ID
        
        for (let i = 0; i < cleanLines.length; i++) {
            const line = cleanLines[i];
            
            // Skip if line contains excluded words or numbers
            if (excludeWords.some(word => line.toUpperCase().includes(word)) || /\d/.test(line)) {
                continue;
            }
            
            // Look for lines that have 2-3 words, all starting with capital letters
            const words = line.split(' ');
            if (words.length >= 2 && words.length <= 3) {
                const allStartWithCapital = words.every(word => /^[A-Z]/.test(word));
                const reasonableLength = words.every(word => word.length >= 2 && word.length <= 12);
                
                if (allStartWithCapital && reasonableLength) {
                    // Check if next line contains ID or course info
                    const hasIdNearby = false;
                    for (let j = Math.max(0, i-2); j < Math.min(cleanLines.length, i+3); j++) {
                        if (j !== i && (cleanLines[j].includes('ID') || /\d{6,8}/.test(cleanLines[j]))) {
                            hasIdNearby = true;
                            break;
                        }
                    }
                    
                    if (hasIdNearby) {
                        result.full_name = line;
                        break;
                    }
                }
            }
        }
    }
    
    return result;
}
  </script>

  </body>
</html>