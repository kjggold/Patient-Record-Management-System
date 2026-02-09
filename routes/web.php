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
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
Route::post(
    '/appointments/complete-discharge',
    [AppointmentController::class, 'completeDischarge']
)->name('appointments.completeDischarge');
    // Discharge AJAX
    Route::get('/appointments/{id}/discharge', [AppointmentController::class, 'getDischargeData']);
    Route::post('/appointments/{id}/discharge', [AppointmentController::class, 'completeDischarge']);

    // Discharge page
    Route::get('/discharge', [DischargeController::class, 'index'])->name('discharge.index');
    Route::post('/discharges', [DischargeController::class, 'store'])->name('discharges.store');

    // Services
    Route::get('/services/search', [ServiceController::class, 'search'])->name('services.search');

    // API
    Route::get('/api/discharged-appointments',[DischargeController::class, 'completedIds']);

    Route::resources([
        'patients' => PatientController::class,
        'doctors' => DoctorController::class,
        'appointments' => AppointmentController::class,
        'services' => ServiceController::class,
        'discharge' => DischargeController::class,
    ]);
});
