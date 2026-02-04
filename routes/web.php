<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DischargeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserApprovalController;


// Public
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])
    ->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/logout', [AuthController::class, 'logout'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('logout');

// Protected dashboard + resources
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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

// Admin approval links (from email, signed URLs)
Route::get('/admin/registrations/{registrationRequest}/approve', [AdminUserApprovalController::class, 'approve'])
    ->name('admin.registrations.approve')
    ->middleware('signed');

Route::get('/admin/registrations/{registrationRequest}/decline', [AdminUserApprovalController::class, 'decline'])
    ->name('admin.registrations.decline')
    ->middleware('signed');

// Add medical services route
Route::get('/medical-services', function () {
    // Get clinic services data (you'll need to replace this with your actual data source)
    $clinicServices = [
        [
            'name' => 'General Consultation',
            'description' => 'Comprehensive health check-ups and general medical advice',
            'illnesses' => ['Fever', 'Cough', 'Headache', 'Fatigue', 'Allergies'],
            'doctors' => [
                ['id' => 1, 'name' => 'Dr. Sarah Johnson', 'speciality' => 'General Physician', 'qualification' => 'MD, MBBS', 'availability' => 'Mon-Fri: 9AM-5PM', 'rating' => '4.8', 'experience' => '10+ years', 'fee' => '$50']
            ]
        ],
        // ... other clinic services data
    ];

    return view('medical-services', compact('clinicServices'));
})->name('medical-services.index');

//

