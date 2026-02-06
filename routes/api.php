<?php

use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/patient-statistics', [PatientController::class, 'getPatientStatistics']);
// Route::get('/patients/age-distribution', [PatientController::class, 'getAgeDistribution']);

Route::get('/dashboard/patient-chart', [DashboardController::class, 'getPatientChartData']);