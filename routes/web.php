<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Welcome page
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

<<<<<<< HEAD
// Basic Dashboard (requires login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Authentication Routes
=======

// Auth Routes
>>>>>>> 921fcf8 (My updates on eaindra branch)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard (protected)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
