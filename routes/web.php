<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Welcome page (public)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Guest-only routes (login & register)
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Register
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Auth-only routes (protected)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Doctor
 
    Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
    Route::post('/doctors/store', [DoctorController::class, 'store'])->name('doctors.store');



    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Optional: Redirect any unknown route to welcome page
Route::fallback(function () {
    return redirect()->route('welcome');
});
