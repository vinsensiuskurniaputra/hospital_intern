<?php

use App\Http\Controllers\Student\StudentProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\AuthController;
use App\Http\Controllers\General\HomeController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminStaseController;
use App\Http\Controllers\Admin\AdminCampusController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminPresenceController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminUserAdminController;
use App\Http\Controllers\General\NotificationController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminDepartementController;
use App\Http\Controllers\Admin\AdminStudentGradeController;
use App\Http\Controllers\Admin\AdminStudyProgramController;
use App\Http\Controllers\Admin\AdminInternshipClassController;
use App\Http\Controllers\Admin\AdminResponsibleUserController;
use App\Http\Controllers\Admin\AdminUserAuthorizationController;
use App\Http\Controllers\Admin\AdminReportAndMonitoringController;
use App\Http\Controllers\Responsible\ResponsibleScheduleController;
use App\Http\Controllers\Responsible\ResponsibleStudentController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'menu'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('/profile', AdminProfileController::class)->names('admin.profile');
    Route::resource('/users/students', AdminStudentController::class)->names('admin.students');
    Route::get('/students/filter', [AdminStudentController::class, 'filter'])->name('students.filter');
    Route::post('/students/import', [AdminStudentController::class, 'import'])->name('students.import');
    Route::put('/students/{student}/change-status', [AdminStudentController::class, 'changeStatus'])->name('students.change_status');
    Route::get('/download-template', [AdminStudentController::class, 'downloadTemplate'])->name('students.downloadTemplate');

    Route::resource('/users/admins', AdminUserAdminController::class)->names('admin.admins');
    Route::get('/admins/filter', [AdminUserAdminController::class, 'filter'])->name('admins.filter');

    Route::resource('/users/responsibles', AdminResponsibleUserController::class)->names('admin.responsibles');
    Route::get('/responsibles/filter', [AdminResponsibleUserController::class, 'filter'])->name('responsibles.filter');
    Route::get('/responsibles/download-template', [AdminResponsibleUserController::class, 'downloadTemplate'])->name('responsibles.downloadTemplate');
    Route::post('/responsibles/import', [AdminResponsibleUserController::class, 'import'])->name('responsibles.import');

    Route::resource('/permissions/users', AdminUserAuthorizationController::class)->names('admin.user_authorizations');
    Route::get('/users/filter', [AdminUserAuthorizationController::class, 'filter'])->name('users.filter');
    
    Route::resource('/permissions/roles', AdminRoleController::class)->names('admin.roles');
    Route::get('/roles/filter', [AdminRoleController::class, 'filter'])->name('roles.filter');
    
    Route::resource('/permissions/menus', AdminMenuController::class)->names('admin.menus');
    Route::get('/menus/filter', [AdminMenuController::class, 'filter'])->name('menus.filter');
    
    Route::resource('/academics/campuses', AdminCampusController::class)->names('admin.campuses');
    Route::get('/campuses/filter', [AdminCampusController::class, 'filter'])->name('campuses.filter');
    
    Route::resource('/academics/studyPrograms', AdminStudyProgramController::class)->names('admin.studyPrograms');
    Route::get('/studyPrograms/filter', [AdminStudyProgramController::class, 'filter'])->name('studyPrograms.filter');
    Route::resource('/internships/departements', AdminDepartementController::class)->names('admin.departements');
    Route::get('/departements/filter', [AdminDepartementController::class, 'filter'])->name('departements.filter');

    Route::resource('/internships/stases', AdminStaseController::class)->names('admin.stases');
    Route::get('/stases/filter', [AdminStaseController::class, 'filter'])->name('stases.filter');
    
    Route::resource('/internships/internshipClasses', AdminInternshipClassController::class)->names('admin.internshipClasses');
    Route::get('/internshipClasses/filter', [AdminInternshipClassController::class, 'filter'])->name('internshipClasses.filter');
    Route::get('/internshipClasses/insertStudent', [AdminInternshipClassController::class, 'insertStudent'])->name('admin.internshipClasses.insertStudent');
    Route::post('/internshipClasses/insertStudent', [AdminInternshipClassController::class, 'insertStudentStore'])->name('admin.internshipClasses.insertStudent.store');

    Route::middleware(['auth', 'menu'])->group(function () {
        // Pastikan route filter-by-date didefinisikan sebelum resource route
        Route::get('/presences/schedules/filter-by-date', [AdminScheduleController::class, 'filterByDate'])
            ->name('presences.schedules.filter-by-date');
        
        Route::get('/presences/schedules/filter', [AdminScheduleController::class, 'filter'])
            ->name('presences.schedules.filter');

        Route::resource('/presences/schedules', AdminScheduleController::class)
            ->names('presences.schedules');
    });
        
    Route::get('stases/{stase}/responsible', [AdminScheduleController::class, 'getResponsible']);
    Route::post('/presences/schedules', [AdminScheduleController::class, 'store'])
        ->name('presences.schedules.store');
    Route::get('/presences/schedules/filter', [AdminScheduleController::class, 'filter'])
        ->name('presences.schedules.filter');
  
    Route::resource('/presences/studentPresences', AdminPresenceController::class)->names('admin.studentPresences');

    Route::resource('/presences/studentScores', AdminStudentGradeController::class)->names('admin.studentScores');
    Route::get('/studentScores/filter', [AdminStudentGradeController::class, 'filter'])->name('studentScores.filter');
    

    Route::resource('/presences/certificates', AdminCertificateController::class)->names('admin.certificates');
    Route::get('/presences/generate-certificates/{id}', [AdminCertificateController::class, 'generateCertificate'])->name('admin.certificate.generate');
    Route::get('/presences/certificate/download/{id}', [AdminCertificateController::class, 'downloadCertificate'])
    ->name('certificate.download');

    Route::resource('/presences/reportAndMonitorings', AdminReportAndMonitoringController::class)->names('admin.reportAndMonitorings');

    Route::resource('/notification', NotificationController::class)->names('notification');

    Route::get('/responsible/schedule/get-schedules', [ResponsibleScheduleController::class, 'getSchedules'])
        ->name('responsible.schedule.get-schedules');

    Route::get('/home/profile', [StudentProfileController::class, 'index'])->name('student-profile');
});

// Student Routes 
Route::middleware(['auth', 'menu'])->prefix('student')->name('student.')->group(function () {

    // Dashboard
    Route::get('/home', [App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal
    Route::get('/schedule', [App\Http\Controllers\Student\StudentScheduleController::class, 'index'])->name('schedule');
    
    // Presensi & Sertifikasi
    Route::get('/attendance', [App\Http\Controllers\Student\StudentAttendanceController::class, 'index'])->name('attendance');
    Route::post('/attendance/checkout', [App\Http\Controllers\Student\StudentAttendanceController::class, 'checkOut'])->name('attendance.checkout');
    
    // Nilai
    Route::get('/grades', [App\Http\Controllers\Student\StudentGradeController::class, 'index'])->name('grades');
    Route::get('/grades/export', [App\Http\Controllers\Student\StudentGradeController::class, 'exportPdf'])->name('grades.export');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'index'])->name('profile');
    Route::get('/profile/password', [StudentProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::post('/profile/password', [StudentProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/edit', [StudentProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [StudentProfileController::class, 'update'])->name('profile.update');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Student\StudentNotificationController::class, 'index'])->name('notifications');
    Route::get('/notifications/{id}', [App\Http\Controllers\Student\StudentNotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notifications/{id}/read', [App\Http\Controllers\Student\StudentNotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\Student\StudentNotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
});

// Responsible Routes 
Route::middleware(['auth', 'menu'])->prefix('responsible')->name('responsible.')->group(function () {
    // Dashboard
    Route::get('/home', [App\Http\Controllers\Responsible\ResponsibleDashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal
    Route::get('/schedule', [ResponsibleScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/schedule/get-schedules', [ResponsibleScheduleController::class, 'getSchedules'])->name('schedule.get-schedules');
    Route::get('/schedule/filter', [ResponsibleScheduleController::class, 'filter'])->name('schedule.filter');
    Route::get('/schedule/class-details', [ResponsibleScheduleController::class, 'getClassDetails'])->name('schedule.class-details');
    
    // Presensi
    Route::get('/attendance', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'index'])
        ->name('attendance');
    Route::get('/attendance/students', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'getStudentAttendance'])
        ->name('attendance.students');
    Route::get('/attendance/classes', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'getInternshipClasses'])
        ->name('attendance.classes');
    Route::post('/attendance/update', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'updateAttendance'])
        ->name('attendance.update');
    
    // API endpoints untuk presensi
    Route::get('/attendance/students', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'getStudentAttendance'])->name('attendance.students');
    Route::post('/attendance/manual-add', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'addManualAttendance'])->name('attendance.manual-add');
    Route::get('/attendance/class-years', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'getClassYears'])
    ->name('attendance.class-years');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Responsible\ResponsibleProfileController::class, 'index'])->name('profile');
    
    // Nilai - PERBAIKAN: Pastikan route ini terdefinisi dengan benar
    Route::get('/grades', [App\Http\Controllers\Responsible\ResponsibleGradeController::class, 'index'])->name('grades.index');
    Route::post('/grades/store', [App\Http\Controllers\Responsible\ResponsibleGradeController::class, 'store'])->name('grades.store');
    
    // Laporan & Rekapitulasi
    Route::get('/reports', [App\Http\Controllers\Responsible\ResponsibleReportController::class, 'index'])->name('reports');
    Route::resource('schedules', AdminScheduleController::class);
    Route::get('stases/{stase}/responsible', [AdminScheduleController::class, 'getResponsible']);
    Route::get('/reports/download-csv', [App\Http\Controllers\Responsible\ResponsibleReportController::class, 'downloadCsv'])->name('reports.download-csv');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Responsible\ResponsibleNotificationController::class, 'index'])->name('notifications');
    Route::get('/schedule/get-classes', [ResponsibleScheduleController::class, 'getClassesForStase'])->name('schedule.get-classes');
    Route::get('/students', [App\Http\Controllers\Responsible\ResponsibleStudentController::class, 'index'])->name('students.index');
});

// API Routes for presence (PIC/Responsible)
Route::middleware(['auth'])->group(function () {
    // PIC/Responsible routes for presence
    Route::get('/presence/active-tokens', [App\Http\Controllers\General\HomeController::class, 'getActiveTokens']);
    Route::post('/presence/generate-token', [App\Http\Controllers\General\HomeController::class, 'generatePresenceToken']);
    
    // Student routes for attendance
    Route::post('/attendance/submit-token', [HomeController::class, 'submitAttendanceToken'])->name('attendance.submit-token');
    Route::get('/attendance/check-today', [HomeController::class, 'checkTodayAttendance'])->name('attendance.check-today');
    Route::post('/attendance/checkout', [HomeController::class, 'checkoutAttendance'])->name('attendance.checkout');
});



