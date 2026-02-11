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
    AdminUserApprovalController
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

Route::post('/logout', [AuthController::class, 'logout'])->withoutMiddleware([VerifyCsrfToken::class])->name('logout');

// --------------------
// Protected routes (auth)
// --------------------
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Appointments CRUD + AJAX discharge
    Route::resource('appointments', AppointmentController::class)
        ->only(['index','store','create','update','destroy','show','edit']);

    // Discharge AJAX
    Route::post('/appointments/{appointment}/discharge', [AppointmentController::class, 'discharge'])
        ->name('appointments.discharge');

    Route::post('/appointments/complete-discharge', [AppointmentController::class,'completeDischarge'])
        ->name('appointments.completeDischarge');

    // Discharge pages
    Route::get('/discharge', [DischargeController::class, 'index'])->name('discharge.index');
    Route::post('/discharge', [DischargeController::class, 'store'])->name('discharge.store');
    Route::post('/discharges/store', [DischargeController::class, 'store'])->name('discharges.store');

    // Patients / Doctors / Services CRUD
    Route::resources([
        'patients' => PatientController::class,
        'doctors' => DoctorController::class,
        'services' => ServiceController::class,
        'discharge' => DischargeController::class,
        'patientHistory'=> PatientHistoryController::class,
    ]);

    // Patient History Routes
    Route::prefix('patient-history')->name('patient-history.')->group(function () {
        Route::get('/', [PatientHistoryController::class, 'index'])->name('index');
        Route::get('/{patient}', [PatientHistoryController::class, 'show'])->name('show');

        // Appointment Actions
        Route::post('/{patient}/appointments', [PatientHistoryController::class, 'createAppointment'])->name('create-appointment');
        Route::post('/appointments/{appointment}/status', [PatientHistoryController::class, 'updateAppointmentStatus'])->name('update-appointment-status');

        // Payment Actions
        Route::post('/appointments/{appointment}/payments', [PatientHistoryController::class, 'addPayment'])->name('add-payment');

        // Patient Actions
        Route::put('/{patient}/update', [PatientHistoryController::class, 'updatePatientInfo'])->name('update-patient-info');
        Route::get('/{patient}/download-report', [PatientHistoryController::class, 'downloadPatientReport'])->name('download-report');

        // Statistics
        Route::get('/statistics', [PatientHistoryController::class, 'getPatientStatistics'])->name('statistics');
    });

    // Specific patient routes
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('patients.show');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('patients.destroy');

    // Admin approval links (signed URLs)
    Route::get('/admin/registrations/{registrationRequest}/approve', [AdminUserApprovalController::class, 'approve'])
        ->name('admin.registrations.approve')
        ->middleware('signed');

    Route::get('/admin/registrations/{registrationRequest}/decline', [AdminUserApprovalController::class, 'decline'])
        ->name('admin.registrations.decline')
        ->middleware('signed');

    // Medical services page (example view)
    Route::get('/medical-services', function () {
        $clinicServices = [
            [
                'name' => 'General Consultation',
                'description' => 'Comprehensive health check-ups and general medical advice',
                'illnesses' => ['Fever', 'Cough', 'Headache', 'Fatigue', 'Allergies'],
                'doctors' => [
                    ['id' => 1, 'name' => 'Dr. Sarah Johnson', 'speciality' => 'General Physician', 'qualification' => 'MD, MBBS', 'availability' => 'Mon-Fri: 9AM-5PM', 'rating' => '4.8', 'experience' => '10+ years', 'fee' => '$50']
                ]
            ],
        ];
        return view('medical-services', compact('clinicServices'));
    })->name('medical-services.index');
});
