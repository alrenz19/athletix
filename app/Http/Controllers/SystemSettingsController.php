<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use App\Models\AuditLog;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $users = User::all();
        $teams = Team::all();
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->get(); // Fetch audit logs

        return view('controlPanel', compact('users', 'teams', 'logs'));
    }
}
