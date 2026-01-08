<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Coach;
use App\Models\Staff;
use App\Models\Athlete;
use App\Models\Team;
use App\Models\Connection;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Course;
use App\Models\Section;
use App\Models\Sport;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserMail;

class ControlPanelController extends Controller
{

    public function index()
    {
        $users = User::where('removed', 0)->get();
        $teams = Team::where('removed', 0)->get();
        $logs = AuditLog::with('user')->orderBy('created_at', 'desc')->get();
        $departments = Department::where('removed', 0)->get();
        $courses = Course::where('removed', 0)->get();
        $sections = Section::where('removed', 0)->get();

        // ✅ Sports and Coaches
        $sports = Sport::with('coaches')->where('removed', 0)->get();
        $coaches = User::where('role', 'Coach')->where('removed', 0)->get();

        return view('controlPanel', compact(
            'users', 'teams', 'logs',
            'departments', 'courses', 'sections',
            'sports', 'coaches'
        ));
    }


    private function logAction($action, $module, $description)
    {
        AuditLog::create([
            'user_id'    => Auth::id(), // currently logged-in user
            'action'     => $action,   // Add / Update / Delete
            'module'     => $module,   // Users / Teams
            'description'=> $description,
            'ip_address' => request()->ip(),
        ]);
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:SuperAdmin,Admin,Coach,Staff,Athlete',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // 4. Send OTP via email
        try {
            Mail::to($user->username)->send(new UserMail($request->username, $request->password));
        } catch (\Exception $e) {
            \Log::error("Mail failed: " . $e->getMessage());
        }
        $this->logAction('Add', 'User', "Created user {$user->username}");

        return redirect()->back()->with('success', 'User added successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => 'required|unique:users,username,' . $id . ',user_id',
            'role' => 'required|in:SuperAdmin,Admin,Coach,Staff,Athlete',
        ]);

        $user->update([
            'username' => $request->username,
            'role' => $request->role,
            'password' => $request->password ? bcrypt($request->password) : $user->password,
        ]);

        $this->logAction('Update', 'User', "Updated user {$user->username}");

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        $user->update(['removed' => 1]);

        if ($user->coach) {
            $user->coach->update(['removed' => 1]);
        }
        if ($user->staff) {
            $user->staff->update(['removed' => 1]);
        }
        if ($user->admin) {
            $user->admin->update(['removed' => 1]);
        }
        if ($user->athlete) {
            $user->athlete->update(['removed' => 1]);
        }

        $this->logAction('Delete', 'User', "Removed user {$user->username}");

        return redirect()->back()->with('success', 'User and all related connections have been removed.');
    }

    public function storeTeam(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string',
        ]);

        $team = Team::create([
            'team_name' => $request->team_name,
            'sport_id' => $request->sport_id,
        ]);

        $this->logAction('Add', 'Team', "Created team {$team->team_name}");

        return redirect()->back()->with('success', 'Team added successfully.');
    }

    public function updateTeam(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $request->validate([
            'team_name' => 'required|string',
        ]);

        $team->update([
            'team_name' => $request->team_name,
            'sport_id' => $request->sport_id,
        ]);

        $this->logAction('Update', 'Team', "Updated team {$team->team_name}");

        return redirect()->back()->with('success', 'Team updated successfully.');
    }

    public function deleteTeam($id)
    {
        $team = Team::findOrFail($id);
        $team->update(['removed' => 1]);

        $this->logAction('Delete', 'Team', "Removed team {$team->team_name}");

        return redirect()->back()->with('success', 'Team has been removed.');
    }

    public function backupDatabase()
    {
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $dbHost = env('DB_HOST');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        $command = "mysqldump -h {$dbHost} -u {$dbUser} -p{$dbPass} {$dbName} > {$path}";
        exec($command);

        $this->logAction('Backup', 'System', "Database backup created: {$filename}");

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function storeDepartment(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $department = Department::create([
            'department_name' => $request->name,
        ]);

        $this->logAction('Add', 'Department', "Created department {$department->name}");

        return redirect()->back()->with('success', 'Department added successfully.');
    }

    public function updateDepartment(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
        ]);

        $department->update(['name' => $request->name]);

        $this->logAction('Update', 'Department', "Updated department {$department->name}");

        return redirect()->back()->with('success', 'Department updated successfully.');
    }

    public function deactivateDepartment($id)
    {
        $department = Department::findOrFail($id);
        $department->update(['removed' => 1]);

        $this->logAction('Delete', 'Department', "Deactivated department {$department->name}");

        return redirect()->back()->with('success', 'Department deactivated successfully.');
    }


    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'nullable|exists:departments,department_id', // validate department if provided
        ]);

        $course = Course::create([
            'course_name' => $request->name,
            'department_id' => $request->department_id ?? null,
        ]);

        $this->logAction('Add', 'Course', "Created course {$course->course_name}");

        return redirect()->back()->with('success', 'Course added successfully.');
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'department_id' => 'nullable|exists:departments,department_id',
        ]);

        $course->update([
            'course_name' => $request->name,
            'department_id' => $request->department_id ?? $course->department_id,
        ]);

        $this->logAction('Update', 'Course', "Updated course {$course->course_name}");

        return redirect()->back()->with('success', 'Course updated successfully.');
    }

    public function deactivateCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['removed' => 1]);

        $this->logAction('Delete', 'Course', "Deactivated course {$course->course_name}");

        return redirect()->back()->with('success', 'Course deactivated successfully.');
    }

    public function deactivateSection($id)
    {
        $section = Section::findOrFail($id);
        $section->update(['removed' => 1]);

        $this->logAction('Delete', 'Section', "Deactivated section {$section->section_name}");

        return redirect()->back()->with('success', 'Section deactivated successfully.');
    }

    public function storeSport(Request $request)
    {
        $request->validate([
            'sport_name' => 'required|string|max:255',
            'coach_id'   => 'nullable|exists:users,user_id',
        ]);

        // Create sport first
        $sport = Sport::create([
            'sport_name' => $request->sport_name,
        ]);

        // If coach assigned, update coach.sport_id
        if ($request->coach_id) {
            $coach = Coach::where('user_id', $request->coach_id)->first();
            if ($coach) {
                $coach->sport_id = $sport->sport_id;
                $coach->save();
            }
        }

        $this->logAction('Add', 'Sport', "Created sport {$sport->sport_name}");

        return redirect()->back()->with('success', 'Sport added successfully and coach assigned.');
    }

    public function updateSport(Request $request, $id)
    {
        $sport = Sport::findOrFail($id);

        $request->validate([
            'sport_name' => 'required|string|max:255',
            'coach_id'   => 'nullable|exists:users,user_id',
        ]);

        // Update sport
        $sport->update([
            'sport_name' => $request->sport_name,
        ]);

        // Handle coach reassignment
        if ($request->coach_id) {
            // Remove previous coach assignment if any
            Coach::where('sport_id', $sport->sport_id)->update(['sport_id' => null]);

            // Assign new coach
            $coach = Coach::where('user_id', $request->coach_id)->first();
            if ($coach) {
                $coach->sport_id = $sport->sport_id;
                $coach->save();
            }
        } else {
            // If no coach selected, unassign current coach
            Coach::where('sport_id', $sport->sport_id)->update(['sport_id' => null]);
        }

        $this->logAction('Update', 'Sport', "Updated sport {$sport->sport_name}");

        return redirect()->back()->with('success', 'Sport updated successfully.');
    }

    public function deactivateSport($id)
    {
        $sport = Sport::findOrFail($id);
        $sport->update(['removed' => 1]);

        // Also unassign coaches linked to this sport
        Coach::where('sport_id', $sport->sport_id)->update(['sport_id' => null]);

        $this->logAction('Delete', 'Sport', "Deactivated sport {$sport->sport_name}");

        return redirect()->back()->with('success', 'Sport deactivated successfully.');
    }


}
