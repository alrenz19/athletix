<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\Notification;
use App\Models\Athlete;
use thiagoalessio\TesseractOCR\TesseractOCR;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'school_id' => 'required|string|max:50',
            'username'  => 'required|email|unique:users,username|unique:pending_registrations,username',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Hash password for security
        $hashedPassword = Hash::make($request->password);

        // Store pending registration
        $pendingId = DB::table('pending_registrations')->insertGetId([
            'full_name'   => $request->full_name,
            'school_id'   => $request->school_id,
            'username'    => $request->username,
            'password'    => $hashedPassword,
            'otp'         => $otp,
            'expires_at'  => now()->addMinutes(10),
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Send OTP via email
        Mail::to($request->username)->send(new OtpMail($otp));

        // ✅ Return JSON response with pending ID (for OTP verification page)
        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email. Please verify to complete registration.',
            'redirect_url' => route('verify.otp.page', ['pending_id' => $pendingId])
        ]);
    }


    // Login user
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            
            // Check if athlete is rejected
            if ($user->role === 'Athlete' && $user->athlete && $user->athlete->status === 'rejected') {
                // Failed login notification for rejected athlete
                $this->notifySuperAdmins(
                    'Rejected Athlete Login Attempt',
                    "Rejected athlete attempted to login with username: {$request->username}",
                    'warning'
                );

                return response()->json([
                    'success' => false,
                    'errors' => [
                        'username' => ['Your account has been rejected. Please contact administrator.'],
                    ]
                ]);
            }

            Auth::login($user);

            // Redirect URL based on role
            switch ($user->role) {
                case 'SuperAdmin':
                    $redirectUrl = route('dashboard');
                    break;

                case 'Staff':
                    $redirectUrl = route('staff.dashboard.index');
                    break;
                    
                case 'Admin':
                    $redirectUrl = route('staff.dashboard.index');
                    break;

                case 'Coach':
                    $redirectUrl = route('coach.dashboard.index');
                    break;

                case 'Athlete':
                    $redirectUrl = route('athlete.dashboard');
                    break;

                default:
                    $redirectUrl = route('dashboard'); // fallback
            }

            return response()->json([
                'success' => true,
                'redirect_url' => $redirectUrl
            ]);
        }

        // Failed login notification
        $this->notifySuperAdmins(
            'Failed Login Attempt',
            "Failed login attempt with username: {$request->username}",
            'warning'
        );

        return response()->json([
            'success' => false,
            'errors' => [
                'username' => ['These credentials do not match our records.'],
            ]
        ]);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'pending_id' => 'required|integer',
            'otp'        => 'required|digits:6',
        ]);

        $pending = DB::table('pending_registrations')
            ->where('id', $request->pending_id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$pending) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.',
            ], 400);
        }

        // ✅ Create user
        $user = User::create([
            'username' => $pending->username,
            'password' => $pending->password,
            'role' => 'Athlete'
        ]);

        // ✅ Create athlete record
        Athlete::create([
            'user_id'   => $user->user_id,
            'full_name' => $pending->full_name,
            'school_id' => $pending->school_id,
            'status'    => 'pending',
            'removed'   => 0,
        ]);

        // ✅ Delete pending record
        DB::table('pending_registrations')->where('id', $pending->id)->delete();

        // ✅ Notify SuperAdmins
        $this->notifySuperAdmins('New Athlete Registered', "A new athlete verified registration: {$user->username}");

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully. Registration completed.',
            'redirect_url' => route('login'),
        ]);
    }



    // Show OTP page
    public function showOtpPage($pending_id)
    {
        return view('auth.verify-otp', compact('pending_id'));
    }

    // Resend OTP
   public function resendOtp(Request $request)
    {
        $request->validate(['pending_id' => 'required|integer']);

        $pending = DB::table('pending_registrations')->where('id', $request->pending_id)->first();

        if (!$pending) {
            return response()->json(['success' => false, 'message' => 'Pending registration not found']);
        }

        $otp = rand(100000, 999999);

        DB::table('pending_registrations')
            ->where('id', $pending->id)
            ->update([
                'otp' => $otp,
                'expires_at' => now()->addMinutes(10),
                'updated_at' => now(),
            ]);

        Mail::to($pending->username)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => 'A new OTP has been sent to your email.'
        ]);
    }


    // Helper function to notify all Super Admins
    protected function notifySuperAdmins($title, $message, $type = 'info')
    {
        $superAdmins = User::where('role', 'SuperAdmin')->get();

        foreach ($superAdmins as $admin) {
            Notification::create([
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'user_id' => $admin->user_id,
            ]);
        }
    }

    public function ocrExtract(Request $request)
    {
        $request->validate([
            'school_id_image' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        // Store temporarily
        $path = $request->file('school_id_image')->store('ocr_uploads', 'public');
        $fullPath = storage_path('app/public/' . $path);

        // Run OCR
        $text = (new TesseractOCR($fullPath))
            ->lang('eng')
            ->run();

        // Debug: check raw output
        // Log::info($text);

        // Try to detect name, course, and ID number
        // Common pattern: NAME (all caps) followed by course like BSMX-E, BSIT, BSED, etc.
        preg_match('/([A-Z\s]+)\s+(BS[A-Z\-]+)/i', $text, $nameMatch);
        preg_match('/ID\s*No[:\s]*([A-Z0-9\-]+)/i', $text, $idMatch);

        $name = isset($nameMatch[1]) ? trim($nameMatch[1]) : 'Not Detected';
        $idNumber = isset($idMatch[1]) ? trim($idMatch[1]) : 'Not Detected';

        return response()->json([
            'success' => true,
            'extracted_text' => $text,
            'full_name' => $name,
            'school_id' => $idNumber,
        ]);
    }
}
