<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function dashboard()
    {
        return view('staff.dashboard');
    }

    public function registrationApproval()
    {
        return view('staff.registration-approval');
    }

    public function deactivateAthlete()
    {
        return view('staff.deactivate-athlete');
    }

    public function updateAthlete()
    {
        return view('staff.update-athlete');
    }

    public function settings()
    {
        return view('staff.settings');
    }
}
