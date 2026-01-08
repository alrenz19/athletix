<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class SecurityController extends Controller
{
    /**
     * Display Security Protocol Dashboard
     */
    public function index()
    {
        // Fetch all users
        $users = User::all();

        $activeUsers = $users->where('removed', false)->count();
        $inactiveUsers = $users->where('removed', true)->count();

        $weakPasswords = 0;
        foreach ($users as $user) {
            $user->isWeakPassword = $this->isWeakPassword($user->password);
            if ($user->isWeakPassword) {
                $weakPasswords++;
            }

            // Optional: you can track failed logins here if you store them
            $user->failedLogins = $user->failed_logins ?? 0; // placeholder
        }

        // Fetch latest 100 audit logs
        $logs = AuditLog::with('user')->latest()->take(100)->get();

        return view('security-protocols', compact(
            'users', 'activeUsers', 'inactiveUsers', 'weakPasswords', 'logs'
        ));
    }

    /**
     * Force Password Reset for a user
     */
    public function forceReset(User $user)
    {
        $newPassword = $this->generatePassword(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Force Password Reset',
            'module' => 'User Security',
            'description' => "Forced password reset for user: {$user->username}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', "Password for {$user->username} has been reset.");
    }

    /**
     * Deactivate a user account
     */
    public function deactivateUser(User $user)
    {
        $user->removed = true;
        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Deactivate User',
            'module' => 'User Security',
            'description' => "Deactivated user: {$user->username}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', "{$user->username} has been deactivated.");
    }

    /**
     * Activate a user account
     */
    public function activateUser(User $user)
    {
        $user->removed = false;
        $user->save();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Activate User',
            'module' => 'User Security',
            'description' => "Activated user: {$user->username}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', "{$user->username} has been activated.");
    }

    /**
     * Download audit logs as CSV
     */
    public function downloadLogs()
    {
        $logs = AuditLog::with('user')->get();
        $filename = "audit_logs_" . date('Ymd_His') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $columns = ['User', 'Action', 'Module', 'Description', 'IP Address', 'Date'];

        $callback = function() use ($logs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->user->username ?? 'N/A',
                    $log->action,
                    $log->module,
                    $log->description,
                    $log->ip_address,
                    $log->created_at,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Check if a password is weak
     * Currently a placeholder since passwords are hashed
     */
    private function isWeakPassword($hashedPassword)
    {
        // In real implementation, store a flag at password creation
        return false;
    }

    /**
     * Generate a strong password
     */
    private function generatePassword($length = 10)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$!";
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }
}
