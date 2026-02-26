<?php

use App\Http\Controllers\PatientHistoryController;
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
use Illuminate\Support\Facades\DB;

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

    Route::get('/discharges', [DischargeController::class, 'index'])->name('discharge.index');
    Route::post('/discharges', [DischargeController::class, 'store'])->name('discharges.store');

    Route::get('/patientHistory', [PatientHistoryController::class, 'index'])->name('patientHistory.index');

    Route::resources([
        'patients' => PatientHistoryController::class,
        'doctors' => DoctorController::class,
        'appointments' => AppointmentController::class,
        'services' => ServiceController::class,
        'discharge' => DischargeController::class,
    ]);
});

Route::get('/patient-history/{patient}/download-report', [App\Http\Controllers\PatientHistoryController::class, 'downloadReport'])->name('patient-history.download-report');

Route::post('/appointments', [AppointmentController::class, 'store'])
    ->name('appointments.store');

Route::post('/appointments/store_dashboard', [AppointmentController::class, 'store_dashboard'])
    ->name('appointments.store_dashboard');

Route::middleware(['auth'])->group(function () {
    // Patient History Routes
    Route::prefix('patient-history')->name('patient-history.')->group(function () {
        Route::get('/', [PatientHistoryController::class, 'index'])->name('index');
        Route::get('/{patient}', [PatientHistoryController::class, 'show'])->name('show');

        // Edit and Update routes for patient history
        Route::get('/{patient}/edit', [PatientHistoryController::class, 'edit'])->name('edit');
        Route::put('/{patient}', [PatientHistoryController::class, 'update'])->name('update');

        Route::get('/{services}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::put('/{services}', [ServiceController::class, 'update'])->name('update');

        //add new patient datas
        Route::post('/patients', [PatientHistoryController::class, 'store'])->name('patients.store');

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


use App\Models\Discharge;

Route::get('/fix-discharges-final', function () {
    $discharges = Discharge::all();

    foreach ($discharges as $d) {
        $services = $d->services;

        // Decode JSON if string
        if (is_string($services)) {
            $services = json_decode($services, true);
        }

        if (!is_array($services)) $services = [];

        $newServices = [];

        foreach ($services as $s) {
            // If $s['name'] is array/object, flatten it
            if (isset($s['name']) && is_array($s['name'])) {
                $flat = $s['name'];
                $name = $flat['name'] ?? '-';
                $price = $flat['price'] ?? 0;
                $id = $flat['id'] ?? null;
            } else {
                $name = $s['name'] ?? '-';
                $price = $s['price'] ?? 0;
                $id = $s['id'] ?? null;
            }

            $newServices[] = [
                'id'    => $id,
                'name'  => $name,
                'price' => $price,
            ];
        }

        $first = $newServices[0] ?? ['id'=>null,'name'=>null,'price'=>0];

        $d->update([
            'service_id'    => $first['id'],
            'service_name'  => $first['name'],
            'service_price' => $first['price'],
            'services'      => $newServices,
            'total'         => array_sum(array_column($newServices,'price')),
        ]);
    }

    return 'All discharges flattened successfully!';
});
