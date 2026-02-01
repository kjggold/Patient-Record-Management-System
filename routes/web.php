<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\WelcomeController;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\DashboardController;
// use App\Http\Controllers\PatientController;
// use App\Http\Controllers\DoctorController;

// // Welcome Page
// Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// // Authentication Routes
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
// Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Route::get('/add_patient', [PatientController::class, 'index'])->name('patients.index');
// Route::post('/patient', [PatientController::class, 'store'])->name('patient');

// // Doctors - FIXED ROUTES
// Route::get('/add_doctor', [DoctorController::class, 'index'])->name('doctors.index');
// Route::post('/submit_doctor', [DoctorController::class, 'store'])->name('submit_doctor');


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AuthController;

// Public
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Patients
    Route::resource('patients', PatientController::class);

    // Doctors
    Route::resource('doctors', DoctorController::class);

    // Appointments
    Route::resource('appointments', AppointmentController::class);

    // Services
    Route::resource('services', ServiceController::class);

    // Payments
    Route::resource('payments', PaymentController::class);

});