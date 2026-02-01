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

    public function backupDatabase()
{
    try {
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sql';
        
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');

        // For Windows (XAMPP)
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
            
            // Check if mysqldump exists
            if (!file_exists($mysqldumpPath)) {
                // Try to find it in PATH
                exec('where mysqldump', $output, $returnVar);
                if ($returnVar === 0) {
                    $mysqldumpPath = 'mysqldump';
                } else {
                    throw new \Exception("mysqldump not found. Please ensure MySQL is installed.");
                }
            }
            
            // Create backup in a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'backup_') . '.sql';
            
            $command = sprintf(
                '"%s" --host=%s --user=%s --password=%s %s > "%s"',
                $mysqldumpPath,
                $dbHost,
                $dbUser,
                $dbPass,
                $dbName,
                $tempFile
            );
        } else {
            // For Linux/Mac
            $tempFile = tempnam(sys_get_temp_dir(), 'backup_') . '.sql';
            
            $command = sprintf(
                'mysqldump --host=%s --user=%s --password=%s %s > "%s"',
                $dbHost,
                $dbUser,
                $dbPass,
                $dbName,
                $tempFile
            );
        }

        // Execute the command
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Database backup failed with error code: $returnVar");
        }

        if (!file_exists($tempFile)) {
            throw new \Exception("Backup file was not created.");
        }

        $fileSize = filesize($tempFile);
        if ($fileSize === 0) {
            unlink($tempFile);
            throw new \Exception("Backup file is empty. Check database connection.");
        }

        $this->logAction('Backup', 'System', "Database backup downloaded: {$filename} ({$fileSize} bytes)");

        // Download the file and delete temp file after
        $response = response()->download($tempFile, $filename)->deleteFileAfterSend(true);
        
        return $response;

    } catch (\Exception $e) {
        \Log::error('Database backup failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Backup failed: ' . $e->getMessage());
    }
}

}
