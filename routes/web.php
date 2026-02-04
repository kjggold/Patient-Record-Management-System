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
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected dashboard + resources
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/appointments/discharge', [AppointmentController::class, 'discharge'])->name('appointments.discharge');
    Route::post('/appointments/complete-discharge',
        [AppointmentController::class,'completeDischarge']
    )->name('appointments.completeDischarge');

    Route::get('/discharge', [DischargeController::class, 'index'])->name('discharge.index');
    Route::post('/discharge', [DischargeController::class, 'store'])->name('discharge.store');

    Route::resources([
        'patients' => PatientController::class,
        'doctors' => DoctorController::class,
        'appointments' => AppointmentController::class,
        'services' => ServiceController::class,
        'discharge' => DischargeController::class,
    ]);
});