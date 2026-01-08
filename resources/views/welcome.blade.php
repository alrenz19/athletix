<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AthletiX - Login</title>
    <link rel="icon" href="https://c.animaapp.com/mevbdbzo2I14VB/img/logo.png" type="image/x-icon" />
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <div class="flex items-center border border-[#8c2c08] rounded px-2">
                      <input
                        type="password"
                        name="password"
                        id="password"
                        placeholder="Password"
                        required
                        class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                      />
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
                    Don’t have an account?
                    <a href="#" id="showSignup" class="text-[#8c2c08] font-semibold hover:underline">Click here</a>
                  </p>
                </form>
              </div>

              <!-- Sign Up Form -->
              <div class="w-1/2 p-6 flex flex-col justify-center shrink-0">
                <h2 class="text-xl font-semibold text-[#8c2c08] mb-6 text-center">
                  Sign-Up
                </h2>

                <form id="signup-form" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="flex flex-col gap-4">
                  @csrf

                  <!-- School ID Upload + OCR -->
                  <div class="flex flex-col border border-[#8c2c08] rounded px-2 py-2">
                    <label class="text-sm text-gray-700 font-semibold mb-1">Upload CTU School ID</label>
                    <input type="file" id="school_id_image" name="school_id_image" accept="image/*" required />
                    <button type="button" id="scanOCR"
                      class="mt-2 bg-[#8c2c08] text-white py-1 rounded hover:bg-[#7a2507] transition">
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
                      placeholder="Email"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- Password -->
                  <div class="flex items-center border border-[#8c2c08] rounded px-2">
                    <input
                      type="password"
                      name="password"
                      placeholder="Password"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- Confirm Password -->
                  <div class="flex items-center border border-[#8c2c08] rounded px-2">
                    <input
                      type="password"
                      name="password_confirmation"
                      placeholder="Confirm Password"
                      required
                      class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                    />
                  </div>

                  <!-- Submit -->
                  <button
                    type="submit"
                    class="bg-[#8c2c08] text-white py-2 rounded-full border border-[#8c2c08] hover:bg-[#7a2507] transition duration-200"
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
      const formsWrapper = document.getElementById("formsWrapper");
      const showSignup = document.getElementById("showSignup");
      const showLogin = document.getElementById("showLogin");

      showSignup.addEventListener("click", (e) => {
        e.preventDefault();
        formsWrapper.style.transform = "translateX(-50%)";
      });

      showLogin.addEventListener("click", (e) => {
        e.preventDefault();
        formsWrapper.style.transform = "translateX(0)";
      });
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
