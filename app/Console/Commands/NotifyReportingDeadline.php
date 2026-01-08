<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotifyReportingDeadline extends Command
{
    protected $signature = 'notify:reporting-deadline';
    protected $description = 'Notify Super Admins of upcoming reporting deadlines';

    public function handle()
    {
        $deadline = Carbon::parse('2025-09-15'); // example date
        $today = Carbon::today();

        if ($deadline->diffInDays($today) <= 3) { // 3 days before deadline
            $superAdmins = User::where('role', 'SuperAdmin')->get();
            $Staffs = User::where('role', 'Staff')->get();
            foreach ($superAdmins as $admin) {
                Notification::create([
                    'title' => 'Upcoming Reporting Deadline',
                    'message' => "CHED report submission deadline is on {$deadline->format('Y-m-d')}.",
                    'type' => 'warning',
                    'user_id' => $admin->user_id
                ]);
            }

            foreach ($Staffs as $admin) {
                Notification::create([
                    'title' => 'Upcoming Reporting Deadline',
                    'message' => "CHED report submission deadline is on {$deadline->format('Y-m-d')}.",
                    'type' => 'warning',
                    'user_id' => $admin->user_id
                ]);
            }
        }

        $this->info('Reporting deadline notifications sent.');
    }
}
