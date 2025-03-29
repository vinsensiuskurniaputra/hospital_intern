<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\AuthController;
use App\Http\Controllers\General\HomeController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminCampusController;
use App\Http\Controllers\Admin\AdminStudentController;
use App\Http\Controllers\Admin\AdminUserAdminController;
use App\Http\Controllers\Admin\AdminStudyProgramController;
use App\Http\Controllers\Admin\AdminResponsibleUserController;
use App\Http\Controllers\Admin\AdminUserAuthorizationController;

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

});



