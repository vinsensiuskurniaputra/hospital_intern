<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\General\AuthController;
use App\Http\Controllers\General\HomeController;
use App\Http\Controllers\Admin\AdminStudentController;

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

});



