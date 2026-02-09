<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DischargeController;
use App\Http\Controllers\AuthController;

// Public
Route::get('/', fn() => view('welcome'))->name('welcome');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('appointments', AppointmentController::class)
        ->only(['index','store','create','update','destroy','show','edit']);

    // ✅ Discharge route for AJAX
    Route::post('/appointments/{appointment}/discharge', [AppointmentController::class, 'discharge'])
        ->name('appointments.discharge');

    Route::get('/discharge', [DischargeController::class, 'index'])->name('discharge.index');

    Route::post('/discharges/store', [DischargeController::class, 'store'])->name('discharges.store');

    Route::resource('patients', PatientController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('services', ServiceController::class);
});
