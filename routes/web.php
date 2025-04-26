<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\AuthController;
use App\Http\Controllers\General\HomeController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminStaseController;
use App\Http\Controllers\Admin\AdminCampusController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminPresenceController;
use App\Http\Controllers\Admin\AdminScheduleController;
use App\Http\Controllers\Admin\AdminUserAdminController;
use App\Http\Controllers\Admin\AdminCertificateController;
use App\Http\Controllers\Admin\AdminDepartementController;
use App\Http\Controllers\Admin\AdminStudentGradeController;
use App\Http\Controllers\Admin\AdminStudyProgramController;
use App\Http\Controllers\Admin\AdminInternshipClassController;
use App\Http\Controllers\Admin\AdminResponsibleUserController;
use App\Http\Controllers\Admin\AdminUserAuthorizationController;
use App\Http\Controllers\Admin\AdminReportAndMonitoringController;
use App\Http\Controllers\Student\StudentNotificationsController as NotificationController;

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
    Route::resource('/users/students', AdminStudentController::class)->names('admin.students');
    Route::get('/students/filter', [AdminStudentController::class, 'filter'])->name('students.filter');
    Route::post('/students/import', [AdminStudentController::class, 'import'])->name('students.import');

    Route::resource('/users/admins', AdminUserAdminController::class)->names('admin.admins');
    Route::get('/admins/filter', [AdminUserAdminController::class, 'filter'])->name('admins.filter');

    Route::resource('/users/responsibles', AdminResponsibleUserController::class)->names('admin.responsibles');
    Route::get('/responsibles/filter', [AdminResponsibleUserController::class, 'filter'])->name('responsibles.filter');
    
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
    Route::resource('/internships/stases', AdminStaseController::class)->names('admin.stases');
    Route::resource('/internships/internshipClasses', AdminInternshipClassController::class)->names('admin.internshipClasses');

    Route::resource('/presences/schedules', AdminScheduleController::class)->names('admin.schedules');
    Route::resource('/presences/studentPresences', AdminPresenceController::class)->names('admin.studentPresences');
    Route::resource('/presences/studentScores', AdminStudentGradeController::class)->names('admin.studentScores');
    Route::resource('/presences/certificates', AdminCertificateController::class)->names('admin.certificates');
    Route::resource('/presences/reportAndMonitorings', AdminReportAndMonitoringController::class)->names('admin.reportAndMonitorings');
});

// Student Routes 
Route::middleware(['auth', 'menu'])->prefix('student')->name('student.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Student\StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal
    Route::get('/schedule', [App\Http\Controllers\Student\StudentScheduleController::class, 'index'])->name('schedule');
    
    // Presensi & Sertifikasi
    Route::get('/attendance', [App\Http\Controllers\Student\StudentAttendanceController::class, 'index'])->name('attendance');
    
    // Nilai
    Route::get('/grades', [App\Http\Controllers\Student\StudentGradeController::class, 'index'])->name('grades');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'index'])->name('profile');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Student\StudentNotificationsController::class, 'index'])->name('notifications');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// Responsible Routes 
Route::middleware(['auth', 'menu'])->prefix('responsible')->name('responsible.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Responsible\ResponsibleDashboardController::class, 'index'])->name('dashboard');
    
    // Jadwal
    Route::get('/schedule', [App\Http\Controllers\Responsible\ResponsibleScheduleController::class, 'index'])->name('schedule');
    
    // Presensi
    Route::get('/attendance', [App\Http\Controllers\Responsible\ResponsibleAttendanceController::class, 'index'])->name('attendance');
    
    // Profile
    Route::get('/profile', [App\Http\Controllers\Responsible\ResponsibleProfileController::class, 'index'])->name('profile');
    
    // Nilai
    Route::get('/grades', [App\Http\Controllers\Responsible\ResponsibleGradeController::class, 'index'])->name('grades');
    
    // Laporan & Rekapitulasi
    Route::get('/reports', [App\Http\Controllers\Responsible\ResponsibleReportController::class, 'index'])->name('reports');
});



