<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AthletiX - Verify OTP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg w-[400px]">
        <h2 class="text-xl font-semibold text-[#8c2c08] mb-6 text-center">
            Verify Your Email
        </h2>

        <form id="otp-form" method="POST" action="{{ route('verify.otp') }}" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="pending_id" value="{{ $pending_id }}">

            <div class="flex items-center border border-[#8c2c08] rounded px-2">
                <input
                    type="text"
                    name="otp"
                    placeholder="Enter OTP"
                    required
                    class="flex-1 px-2 py-2 text-sm text-[#8c2c08] bg-transparent outline-none"
                />
            </div>

            <button
                type="submit"
                class="bg-[#8c2c08] text-white py-2 rounded-full border border-[#8c2c08] hover:bg-[#7a2507] transition duration-200"
            >
                Verify OTP
            </button>
        </form>
        <button
            type="button"
            id="resend-otp-btn"
            class="mt-3 w-full bg-transparent text-[#8c2c08] py-2 rounded-full border border-[#8c2c08] hover:bg-[#f8e4e1] transition duration-200"
        >
            Resend OTP
        </button>


        <p id="otp-error" class="text-red-500 text-sm mt-2"></p>
    </div>

    <script>
        document.getElementById('otp-form').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('verify.otp') }}", {
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
                    window.location.href = data.redirect_url; // go to dashboard/announcement page
                } else {
                    document.getElementById('otp-error').innerText = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
        document.getElementById('resend-otp-btn').addEventListener('click', function () {
            let pendingId = document.querySelector('input[name="pending_id"]').value;

            fetch("{{ route('resend.otp') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ pending_id: pendingId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('otp-error').innerText = "OTP has been resent to your email.";
                    document.getElementById('otp-error').classList.remove("text-red-500");
                    document.getElementById('otp-error').classList.add("text-green-600");
                } else {
                    document.getElementById('otp-error').innerText = data.message;
                    document.getElementById('otp-error').classList.remove("text-green-600");
                    document.getElementById('otp-error').classList.add("text-red-500");
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    </script>
</body>
</html>
