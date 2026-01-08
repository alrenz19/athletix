<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Notification;

class CTUSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // Departments
        // =========================
        $departments = [
            'College of Engineering',
            'College of Information Technology',
            'College of Arts and Sciences',
            'College of Education',
            'College of Agriculture',
            'College of Technology',
        ];

        foreach ($departments as $dept) {
            DB::table('departments')->insert([
                'department_name' => $dept,
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Courses
        // =========================
        $courses = [
            'College of Engineering' => ['BS Civil Engineering','BS Electrical Engineering','BS Electronics Engineering'],
            'College of Information Technology' => ['BS Computer Science','BS Information Technology','BS Information Systems'],
            'College of Arts and Sciences' => ['BS Mathematics','BS Chemistry','BS Biology'],
            'College of Education' => ['Bachelor of Elementary Education','Bachelor of Secondary Education','Bachelor of Technical-Vocational Teacher Education'],
            'College of Agriculture' => ['BS Agriculture','BS Agricultural Technology'],
            'College of Technology' => ['BS Industrial Technology','BS Drafting Technology','BS Electrical Technology'],
        ];

        foreach ($courses as $deptName => $deptCourses) {
            $departmentId = DB::table('departments')->where('department_name', $deptName)->value('department_id');
            foreach ($deptCourses as $course) {
                DB::table('courses')->insert([
                    'course_name' => $course,
                    'department_id' => $departmentId,
                    'removed' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // =========================
        // Sections
        // =========================
        $allCourses = DB::table('courses')->get();
        foreach ($allCourses as $course) {
            for ($i = 1; $i <= 3; $i++) {
                DB::table('sections')->insert([
                    'section_name' => 'Section ' . chr(64 + $i),
                    'course_id' => $course->course_id,
                    'removed' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // =========================
        // Sports
        // =========================
        $sports = ['Basketball', 'Volleyball', 'Table Tennis'];
        foreach ($sports as $sport) {
            DB::table('sports')->insert([
                'sport_name' => $sport,
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Staff
        // =========================
        $staffUsers = [
            ['username' => 'staff1@ctu.com', 'password' => 'password', 'full_name' => 'Juan Dela Cruz', 'position' => 'Admin Staff'],
            ['username' => 'staff2@ctu.com', 'password' => 'password', 'full_name' => 'Maria Clara', 'position' => 'Coordinator'],
            ['username' => 'staff3@ctu.com', 'password' => 'password', 'full_name' => 'Pedro Santos', 'position' => 'Secretary'],
        ];

        foreach ($staffUsers as $s) {
            $userId = DB::table('users')->insertGetId([
                'username' => $s['username'],
                'password' => Hash::make($s['password']),
                'role' => 'Staff',
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('staff')->insert([
                'user_id' => $userId,
                'full_name' => $s['full_name'],
                'position' => $s['position'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Coaches
        // =========================
        $coachUsers = [
            ['username' => 'coach1@ctu.com', 'password' => 'password', 'full_name' => 'Coach A', 'specialization' => 'Basketball', 'sport_id' => 1],
            ['username' => 'coach2@ctu.com', 'password' => 'password', 'full_name' => 'Coach B', 'specialization' => 'Volleyball', 'sport_id' => 2],
            ['username' => 'coach3@ctu.com', 'password' => 'password', 'full_name' => 'Coach C', 'specialization' => 'Table Tennis', 'sport_id' => 3],
        ];

        foreach ($coachUsers as $c) {
            $userId = DB::table('users')->insertGetId([
                'username' => $c['username'],
                'password' => Hash::make($c['password']),
                'role' => 'Coach',
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('coaches')->insert([
                'user_id' => $userId,
                'full_name' => $c['full_name'],
                'specialization' => $c['specialization'],
                'sport_id' => $c['sport_id'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Athletes
        // =========================
        $athleteUsers = [
            ['username' => 'athlete1@ctu.com', 'password' => 'password', 'full_name' => 'Athlete 1', 'birthdate' => '2005-01-10', 'gender' => 'Male', 'year_level' => '1st Year', 'section_id' => 1, 'sport_id' => 1],
            ['username' => 'athlete2@ctu.com', 'password' => 'password', 'full_name' => 'Athlete 2', 'birthdate' => '2004-05-20', 'gender' => 'Female', 'year_level' => '2nd Year', 'section_id' => 2, 'sport_id' => 2],
            ['username' => 'athlete3@ctu.com', 'password' => 'password', 'full_name' => 'Athlete 3', 'birthdate' => '2003-09-15', 'gender' => 'Male', 'year_level' => '3rd Year', 'section_id' => 3, 'sport_id' => 3],
        ];

        foreach ($athleteUsers as $a) {
            $userId = DB::table('users')->insertGetId([
                'username' => $a['username'],
                'password' => Hash::make($a['password']),
                'role' => 'Athlete',
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('athletes')->insert([
                'user_id' => $userId,
                'full_name' => $a['full_name'],
                'birthdate' => $a['birthdate'],
                'gender' => $a['gender'],
                'year_level' => $a['year_level'],
                'section_id' => $a['section_id'],
                'sport_id' => $a['sport_id'],
                'status' => 'pending',
                'school_id'  => '2025' . rand(10000, 99999),
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Events
        // =========================
        $events = [
            ['sport_id' => 1, 'event_name' => 'Basketball Training', 'event_date' => '2025-09-10', 'event_type' => 'Training'],
            ['sport_id' => 2, 'event_name' => 'Volleyball Match', 'event_date' => '2025-09-12', 'event_type' => 'Competition'],
            ['sport_id' => 3, 'event_name' => 'Ping Pong Session', 'event_date' => '2025-09-15', 'event_type' => 'Training'],
        ];

        foreach ($events as $e) {
            DB::table('events')->insert([
                'sport_id' => $e['sport_id'],
                'event_name' => $e['event_name'],
                'event_date' => $e['event_date'],
                'event_type' => $e['event_type'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        DB::table('events')->insert([
            [
                'event_name' => 'City Marathon 2025',
                'sport_id' => 1,
                'event_date' => '2025-10-15',
                'event_type' => 'Competition',
                'location' => 'Central Park',
                'created_at' => now(),
            ],
            [
                'event_name' => 'Regional Swimming Championship',
                'sport_id' => 2,
                'event_date' => '2025-09-20',
                'event_type' => 'Training',
                'location' => 'Aquatic Center',
                'created_at' => now(),
            ],
            [
                'event_name' => 'State Track & Field Meet',
                'sport_id' => 1,
                'event_date' => '2025-11-05',
                'event_type' => 'TryOut',
                'location' => 'National Stadium',
                'created_at' => now(),
            ],
        ]);


        // =========================
        // Attendance
        // =========================
        $attendance = [
            ['athlete_id' => 1, 'event_id' => 1, 'status' => 'Present'],
            ['athlete_id' => 2, 'event_id' => 2, 'status' => 'Absent'],
            ['athlete_id' => 3, 'event_id' => 3, 'status' => 'Late'],
        ];

        foreach ($attendance as $a) {
            DB::table('attendance')->insert([
                'athlete_id' => $a['athlete_id'],
                'event_id' => $a['event_id'],
                'status' => $a['status'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Performance
        // =========================
        $performance = [
            ['athlete_id' => 1, 'event_id' => 1, 'score' => 95, 'remarks' => 'Excellent'],
            ['athlete_id' => 2, 'event_id' => 2, 'score' => 88, 'remarks' => 'Good'],
            ['athlete_id' => 3, 'event_id' => 3, 'score' => 75, 'remarks' => 'Satisfactory'],
        ];

        foreach ($performance as $p) {
            DB::table('performance')->insert([
                'athlete_id' => $p['athlete_id'],
                'event_id' => $p['event_id'],
                'score' => $p['score'],
                'remarks' => $p['remarks'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Announcements
        // =========================
        $announcements = [
            ['title' => 'Welcome to CTU', 'message' => 'Orientation starts on Sept 1', 'posted_by' => 1, 'target' => 'All', 'sport_id' => null, 'section_id' => null],
            ['title' => 'Basketball Tryouts', 'message' => 'Tryouts on Sept 5', 'posted_by' => 2, 'target' => 'Athletes', 'sport_id' => 1, 'section_id' => null],
            ['title' => 'Staff Meeting', 'message' => 'Meeting on Sept 8', 'posted_by' => 3, 'target' => 'Staff', 'sport_id' => null, 'section_id' => null],
        ];

        foreach ($announcements as $a) {
            DB::table('announcements')->insert([
                'title' => $a['title'],
                'message' => $a['message'],
                'posted_by' => $a['posted_by'],
                'target' => $a['target'],
                'sport_id' => $a['sport_id'],
                'section_id' => $a['section_id'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Teams + athlete_team
        // =========================
        $teams = [
            ['team_name' => 'CTU Basketball Team', 'sport_id' => 1],
            ['team_name' => 'CTU Volleyball Team', 'sport_id' => 2],
            ['team_name' => 'CTU Table Tennis Team', 'sport_id' => 3],
        ];

        foreach ($teams as $t) {
            $teamId = DB::table('teams')->insertGetId([
                'team_name' => $t['team_name'],
                'sport_id' => $t['sport_id'],
                'removed' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add one athlete per team
            DB::table('athlete_team')->insert([
                'athlete_id' => $t['sport_id'], // athlete1, athlete2, athlete3
                'team_id' => $teamId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // OTPs (sample)
        // =========================
        $otps = [
            ['user_id' => 1, 'otp' => '123456', 'expires_at' => now()->addMinutes(5)],
            ['user_id' => 2, 'otp' => '654321', 'expires_at' => now()->addMinutes(5)],
            ['user_id' => 3, 'otp' => '112233', 'expires_at' => now()->addMinutes(5)],
        ];

        foreach ($otps as $o) {
            DB::table('otps')->insert([
                'user_id' => $o['user_id'],
                'otp' => $o['otp'],
                'expires_at' => $o['expires_at'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // =========================
        // Notifications (sample)
        // =========================

        $notifications = [
            [
                'title' => 'Failed Login Attempt',
                'message' => 'A failed login attempt was detected for username: user1@example.com',
                'type' => 'warning',
                'user_id' => 1, // null = system-wide notification
                'read' => false,
            ],
            [
                'title' => 'New Athlete Added',
                'message' => 'Athlete John Doe was added to the Basketball team.',
                'type' => 'info',
                'user_id' => 1,
                'read' => false,
            ],
            [
                'title' => 'Upcoming CHED Report Deadline',
                'message' => 'The deadline to submit the September 2025 report is in 3 days.',
                'type' => 'warning',
                'user_id' => 1,
                'read' => false,
            ],
            [
                'title' => 'Feedback Received',
                'message' => 'A new feedback has been submitted by the sports department.',
                'type' => 'info',
                'user_id' => 1,
                'read' => false,
            ],
        ];

        
        foreach ($notifications as $data) {
            Notification::create($data); // ✅ singular
        }

        DB::table('training_notes')->insert([
            [
                'coach_id' => 1,
                'athlete_id' => 1,
                'note' => 'Focus on endurance training for the next two weeks.',
                'created_at' => now(),
            ],
            [
                'coach_id' => 2,
                'athlete_id' => 2,
                'note' => 'Improve sprint start technique.',
                'created_at' => now(),
            ],
            [
                'coach_id' => 1,
                'athlete_id' => 3,
                'note' => 'Maintain current diet plan and hydration.',
                'created_at' => now(),
            ],
        ]);

        DB::table('athlete_event')->insert([
            [
                'athlete_id' => 1,
                'event_id' => 1,
                'created_at' => now(),
            ],
            [
                'athlete_id' => 2,
                'event_id' => 2,
                'created_at' => now(),
            ],
            [
                'athlete_id' => 3,
                'event_id' => 3,
                'created_at' => now(),
            ],
        ]);
    }
}
