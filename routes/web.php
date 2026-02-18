<?php

use App\Http\Controllers\{
    AuthController,
    DashboardController,
    PatientController,
    DoctorController,
    AppointmentController,
    ServiceController,
    DischargeController,
    PatientHistoryController,
    AdminUserApprovalController,
    MedicalServiceController
};

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;

// --------------------
// Public routes
// --------------------
Route::get('/', fn() => view('welcome'))->name('welcome');

// Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware([VerifyCsrfToken::class]);

Route::post('/logout', [AuthController::class, 'logout'])
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->name('logout');

// --------------------
// Protected routes
// --------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::resource('appointments', AppointmentController::class)
        ->only(['index', 'store', 'create', 'update', 'destroy', 'show', 'edit']);

    Route::post('/appointments/{appointment}/discharge', [AppointmentController::class, 'discharge'])
        ->name('appointments.discharge');

    Route::post('/appointments/complete-discharge', [AppointmentController::class, 'completeDischarge'])
        ->name('appointments.completeDischarge');

    // Discharge
    Route::resources([
        'discharge' => DischargeController::class,
    ]);

    Route::get('/discharge', [DischargeController::class, 'index'])->name('discharge.index');
    Route::post('/discharge', [DischargeController::class, 'store'])->name('discharge.store');
    Route::post('/discharges/store', [DischargeController::class, 'store'])->name('discharges.store');

    // Patients, Doctors, Services
    Route::resources([
        'patients' => PatientController::class,
        'doctors' => DoctorController::class,
        'services' => ServiceController::class,
    ]);

    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // ------------------------
    // Patient History Routes
    // ------------------------
   Route::prefix('patient-history')->name('patient-history.')->group(function () {

    // Index page
    Route::get('/', [PatientHistoryController::class, 'index'])->name('index');

    // Patient-specific routes (numeric ID only)
    Route::get('/{patient}', [PatientHistoryController::class, 'show'])
        ->whereNumber('patient')
        ->name('show');

    // Download report
    Route::get('/{patient}/download-report', [PatientHistoryController::class, 'downloadReport'])
        ->whereNumber('patient')
        ->name('download-report');

    // Other patient-history routes
    Route::get('/{patient}/report', [PatientHistoryController::class, 'viewPatientReport'])
        ->whereNumber('patient')
        ->name('report');

    Route::get('/{patient}/export-csv', [PatientHistoryController::class, 'exportPatientCsv'])
        ->whereNumber('patient')
        ->name('export.csv');

    Route::get('/{patient}/view-report-pdf', [PatientHistoryController::class, 'viewPatientReportPdf'])
        ->whereNumber('patient')
        ->name('view-report-pdf');

    Route::post('/{patient}/appointments', [PatientHistoryController::class, 'createAppointment'])
        ->whereNumber('patient')
        ->name('create-appointment');

    Route::post('/appointments/{appointment}/status', [PatientHistoryController::class, 'updateAppointmentStatus'])
        ->name('update-appointment-status');

    Route::put('/{patient}/update', [PatientHistoryController::class, 'updatePatientInfo'])
        ->whereNumber('patient')
        ->name('update-patient-info');
});


    // ------------------------
    // Medical Services
    // ------------------------
    Route::prefix('medical-services')->name('medical-services.')->group(function () {
        Route::get('/', [MedicalServiceController::class, 'index'])->name('index');
        Route::get('/create', [MedicalServiceController::class, 'create'])->name('create');
        Route::post('/', [MedicalServiceController::class, 'store'])->name('store');
    });

    // ------------------------
    // Admin User Approval
    // ------------------------
    Route::get('/admin/registrations/{registrationRequest}/approve',
        [AdminUserApprovalController::class, 'approve'])
        ->name('admin.registrations.approve')->middleware('signed');

    Route::get('/admin/registrations/{registrationRequest}/decline',
        [AdminUserApprovalController::class, 'decline'])
        ->name('admin.registrations.decline')->middleware('signed');
});
