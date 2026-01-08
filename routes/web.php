<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\{
    AuthController, DashboardController, AnnouncementController,
    UserController, ReportController, ControlPanelController,
    EventController, SecurityController, PerformanceController,
    AttendanceController, NotificationController, StaffProfileController,
    StaffController, RegistrationApprovalController, AthleteController,
    StaffNotificationController, CoachDashboardController, CoachAttendancePerformanceController, 
    CoachAthleteController, CoachController, CoachProfileController,
    AwardController, CoachReportController, CoachNotificationController,
    AthleteDashboardController, AthleteEventController, AthleteStatusController,
    AthleteNotificationController, AthleteProfileController, StaffDashboardController, OCRController
};

// -----------------------------
// Public Routes
// -----------------------------
Route::get('/', fn() => view('welcome'))->name('login')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::post('/ocr/extract', [OCRController::class, 'extract'])
    ->name('ocr.extract');

// OTP
Route::get('/verify-otp/{pending_id}', [AuthController::class, 'showOtpPage'])->name('verify.otp.page');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->name('resend.otp');
//Route::post('/ocr-extract', [AuthController::class, 'ocrExtract'])->name('ocr.extract');

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');


// -----------------------------
// Protected Routes
// -----------------------------
Route::middleware(['auth'])->group(function () {

    // -----------------------------
    // SuperAdmin, Staff, Coach
    // -----------------------------
    Route::middleware(['role:SuperAdmin|Staff|Coach|Admin'])->group(function() {
        //Announcement
        Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    
        // Events
        Route::prefix('events')->group(function() {
            Route::get('/', [EventController::class, 'index'])->name('events');
            Route::post('/store', [EventController::class, 'storeEvent'])->name('events.storeEvent');
            Route::put('/{id}/update', [EventController::class, 'updateEvent'])->name('events.updateEvent');
            Route::delete('/{id}', [EventController::class, 'deleteEvent'])->name('events.deleteEvent');
        });
    
    });

    // -----------------------------
    // SuperAdmin & Staff
    // -----------------------------
    Route::middleware(['role:SuperAdmin|Staff|Admin'])->group(function() {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/settings', [StaffController::class, 'settings'])->name('settings');

        // Control Panel
        Route::prefix('control-panel')->group(function() {
            Route::get('/', [ControlPanelController::class, 'index'])->name('control.panel');

            // Users
            Route::post('/user/store', [ControlPanelController::class, 'storeUser'])->name('control-panel.storeUser');
            Route::put('/user/{id}/update', [ControlPanelController::class, 'updateUser'])->name('control-panel.updateUser');
            Route::patch('/user/{id}/delete', [ControlPanelController::class, 'deleteUser'])->name('control-panel.deleteUser');

            // Teams
            Route::post('/team/store', [ControlPanelController::class, 'storeTeam'])->name('control-panel.storeTeam');
            Route::put('/team/{id}/update', [ControlPanelController::class, 'updateTeam'])->name('control-panel.updateTeam');
            Route::delete('/team/{id}/delete', [ControlPanelController::class, 'deleteTeam'])->name('control-panel.deleteTeam');

            // Sports
            Route::patch('/sports/deactivate/{id}', [ControlPanelController::class, 'deactivateSport'])
                ->name('sports.deactivate');
            Route::put('/sports/{id}/update', [ControlPanelController::class, 'updateSport'])
            ->name('sports.update');
            Route::post('/sports/store', [ControlPanelController::class, 'storeSport'])->name('sports.store');

            // Backup
            Route::get('/backup', [ControlPanelController::class, 'backupDatabase'])->name('control-panel.backupDatabase');
        });

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
       // Individual form exports
        Route::get('/reports/export/{form}/{format}', [ReportController::class, 'exportForm'])
            ->name('reports.export.form')
            ->where('form', '[A-G]')
            ->where('format', 'pdf|csv');

        // Feedback submission
        Route::post('/reports/save-feedback', [ReportController::class, 'saveFeedback'])
            ->name('reports.save-feedback');

        // Legacy routes (keep for backward compatibility)
        Route::get('/reports/export/{format}', [ReportController::class, 'export'])
            ->name('reports.export')
            ->where('format', 'pdf|csv|xlsx');

        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])
            ->name('reports.export.pdf');

        // // Save institutional data
        Route::post('/reports/save-institutional', [ReportController::class, 'saveInstitutional'])
            ->name('reports.save-institutional');

        // // Save sports programs data  
        Route::post('/reports/save-sports-programs', [ReportController::class, 'saveSportsPrograms'])
            ->name('reports.save-sports-programs');

        Route::post('/reports/save-budget-expenditure', [ReportController::class, 'saveBudgetExpenditure'])
            ->name('reports.save-budget-expenditure');
        
        Route::post('/reports.save-student-athletes', [ReportController::class, 'saveBudgetExpenditure'])
            ->name('reports.save-student-athletes');

        Route::post('/reports.save-benefits', [ReportController::class, 'saveBudgetExpenditure'])
            ->name('reports.save-benefits');

        Route::post('/reports.save-sports-personnel', [ReportController::class, 'saveBudgetExpenditure'])
            ->name('reports.save-sports-personnel');

        Route::get('/reports/export/{form}/{format}', [ReportController::class, 'exportForm'])->name('reports.export-form');
        
    });

    // -----------------------------
    // SuperAdmin Only
    // -----------------------------
    Route::middleware(['role:SuperAdmin|Admin|Staff'])->group(function() {

        // Departments
        Route::prefix('departments')->group(function() {
            Route::post('/store', [ControlPanelController::class, 'storeDepartment'])->name('departments.store');
            Route::put('/{id}/update', [ControlPanelController::class, 'updateDepartment'])->name('departments.update');
            Route::patch('/{id}/deactivate', [ControlPanelController::class, 'deactivateDepartment'])->name('departments.deactivate');
        });

        // Courses
        Route::prefix('courses')->group(function() {
            Route::post('/store', [ControlPanelController::class, 'storeDepartment'])->name('courses.store');
            Route::put('/{id}/update', [ControlPanelController::class, 'updateCourse'])->name('courses.update');
            Route::patch('/{id}/deactivate', [ControlPanelController::class, 'deactivateCourse'])->name('courses.deactivate');
        });

        // Sections
        Route::prefix('sections')->group(function() {
            Route::post('/store', [ControlPanelController::class, 'storeSection'])->name('sections.store');
            Route::put('/{id}/update', [ControlPanelController::class, 'updateSection'])->name('sections.update');
            Route::patch('/{id}/deactivate', [ControlPanelController::class, 'deactivateSection'])->name('sections.deactivate');
        });


        // Security
        Route::prefix('security')->group(function() {
            Route::get('/', [SecurityController::class, 'index'])->name('security.index');
            Route::post('/force-reset/{user}', [SecurityController::class, 'forceReset'])->name('security.forceReset');
            Route::post('/deactivate/{user}', [SecurityController::class, 'deactivateUser'])->name('security.deactivateUser');
            Route::post('/activate/{user}', [SecurityController::class, 'activateUser'])->name('security.activateUser');
            Route::get('/download-logs', [SecurityController::class, 'downloadLogs'])->name('security.downloadLogs');
        });

        // Performance
        Route::prefix('performance')->group(function() {
            Route::get('/', [PerformanceController::class, 'index'])->name('performance.index');
            Route::get('/create', [PerformanceController::class, 'create'])->name('performance.create');
            Route::post('/store', [PerformanceController::class, 'store'])->name('performance.store');
            Route::get('/edit/{performance}', [PerformanceController::class, 'edit'])->name('performance.edit');
            Route::post('/update/{performance}', [PerformanceController::class, 'update'])->name('performance.update');
            Route::post('/delete/{performance}', [PerformanceController::class, 'destroy'])->name('performance.destroy');
        });

        // Attendance
        Route::prefix('attendance')->group(function() {
            Route::get('/', [AttendanceController::class, 'index'])->name('attendance');
            Route::get('/create', [AttendanceController::class, 'create'])->name('attendance.create');
            Route::post('/store', [AttendanceController::class, 'store'])->name('attendance.store');
            Route::get('/edit/{attendance}', [AttendanceController::class, 'edit'])->name('attendance.edit');
            Route::post('/update/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
            Route::post('/delete/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
        });

        // Notifications
        Route::prefix('notifications')->group(function() {
            Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
            Route::get('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
            Route::post('/', [NotificationController::class, 'store'])->name('notifications.store');
        });

        // Users
        Route::resource('users', UserController::class)->only(['store', 'update', 'destroy', 'index', 'edit']);
    });

    // -----------------------------
    // Staff Only
    // -----------------------------
    Route::middleware(['role:Staff|Admin|SuperAdmin'])->prefix('staff')->name('staff.')->group(function() {
        Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard.index');
        Route::get('/registration-approval', [RegistrationApprovalController::class, 'index'])->name('approval.index');
        Route::patch('/registration-approval/{id}/approve', [RegistrationApprovalController::class, 'approve'])->name('approval.approve');
        Route::patch('/registration-approval/{id}/reject', [RegistrationApprovalController::class, 'reject'])->name('approval.reject');

        Route::get('/athlete/deactivate', [AthleteController::class, 'deactivateIndex'])->name('athlete.deactivate');
        Route::patch('/athlete/deactivate/{id}', [AthleteController::class, 'deactivate'])->name('athlete.deactivate.submit');

        Route::get('/athletes', [AthleteController::class, 'index'])->name('athletes.index');
        Route::patch('/athletes/{id}', [AthleteController::class, 'update'])->name('athletes.update');
        Route::put('/staff/athletes/{athlete}/status', [AthleteController::class, 'updateStatus'])->name('athletes.update-status');


        Route::get('/profile', [StaffProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [StaffProfileController::class, 'update'])->name('profile.update');

        Route::get('/notifications', [StaffNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [StaffNotificationController::class, 'markAsRead'])->name('notifications.read');
    });

    // -----------------------------
    // Coach Only
    // -----------------------------
    Route::middleware(['role:Coach|SuperAdmin|Staff|Admin'])->prefix('coach')->name('coach.')->group(function() {
        Route::get('/dashboard', [CoachDashboardController::class, 'index'])->name('dashboard.index');

        Route::get('/athletes', [CoachAthleteController::class, 'index'])->name('athletes.index');
        Route::get('/athletes/{athlete}', [CoachAthleteController::class, 'show'])->name('athletes.show');
        Route::post('/athletes/{athlete}/notes', [CoachAthleteController::class, 'storeNote'])->name('athletes.notes.store');
        Route::post('/athletes/{athlete}/assign-event', [CoachAthleteController::class, 'assignEvent'])->name('athletes.assignEvent');
        Route::put('/athletes/{athlete}/status', [CoachAthleteController::class, 'updateStatus'])->name('athletes.update-status');

        Route::get('/attendance', [CoachAttendancePerformanceController::class, 'index'])->name('attendance.index');
        Route::post('/attendance', [CoachAttendancePerformanceController::class, 'store'])->name('attendance.store');
    
        // Registrations index (all / filter by sport)
        Route::get('/registrations', [CoachController::class, 'registrations'])->name('events.registrations');

        // Approve / Reject keep using event + athlete
        Route::post('/registrations/{event}/{athlete}/approve', [CoachController::class, 'approve'])->name('events.registrations.approve');
        Route::post('/registrations/{event}/{athlete}/reject', [CoachController::class, 'reject'])->name('events.registrations.reject');

        //Awards
        Route::resource('awards', AwardController::class);

        // Reports
        Route::get('/reports/performance', [CoachReportController::class, 'performance'])
            ->name('reports.performance');
        Route::get('/reports/attendance', [CoachReportController::class, 'attendance'])
            ->name('reports.attendance');
        Route::get('/coach/{coachId}/athletes', [CoachReportController::class, 'coachAthletes'])
            ->name('coach.athletes');
        
        // Add these export routes:
        Route::post('/reports/export-performance', [CoachReportController::class, 'exportPerformanceXLSX'])
            ->name('reports.export-performance');
        Route::post('/reports/export-attendance', [CoachReportController::class, 'exportAttendanceXLSX'])
            ->name('reports.export-attendance');

        // For AJAX filtering
        Route::post('/reports/filter', [CoachReportController::class, 'filter'])
            ->name('reports.filter');

        //notifications
        Route::get('/coach/notifications', [CoachNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/coach/notifications/{id}/read', [CoachNotificationController::class, 'markAsRead'])->name('notifications.read');

        //Setting
        Route::get('/profile', [CoachProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [CoachProfileController::class, 'update'])->name('profile.update');

    
    });


    // -----------------------------
    // Athlete Only
    // -----------------------------
    Route::middleware(['role:Athlete|SuperAdmin'])
        ->prefix('athlete')
        ->name('athlete.')
        ->group(function() {

        // Athlete Dashboard
        Route::get('/dashboard', [AthleteDashboardController::class, 'index'])->name('dashboard');

        // Event registration
        Route::post('/athlete/events/{event}/register', [AthleteEventController::class, 'register'])->name('events.register');
        Route::delete('/athlete/events/{event}/unregister', [AthleteEventController::class, 'unregister'])->name('events.unregister');

        // Events
        Route::get('/events', [AthleteEventController::class, 'index'])->name('events.index');
        Route::get('/history', [AthleteEventController::class, 'history'])->name('events.history');
    
    
        // Status
        Route::get('/status', [AthleteStatusController::class, 'index'])->name('status');

        // Notifications
        Route::get('/notifications', [AthleteNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/{id}/read', [AthleteNotificationController::class, 'markAsRead'])->name('notifications.read');

        // Profile
        Route::get('/profile/edit', [AthleteProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/update', [AthleteProfileController::class, 'update'])->name('profile.update');
    });

});
