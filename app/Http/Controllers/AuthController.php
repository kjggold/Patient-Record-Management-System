<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Models\AuthEvent;
use App\Models\RegistrationRequest;
use App\Models\User;
use App\Mail\NewUserRegistrationMail;

class AuthController extends Controller
{
    // Show login page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('open_modal', 'login');
        }

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Block login if user not yet approved
            if (Auth::user()->status !== 'active') {
                Auth::logout();

                return back()
                    ->withErrors(['email' => 'Your account is pending approval by the main admin.'])
                    ->onlyInput('email');
            }

            // Log successful login
            AuthEvent::create([
                'event_type' => 'login',
                'user_id'    => Auth::id(),
                'email'      => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'success'    => true,
            ]);

            return redirect()->route('dashboard')->with('status', 'Login successful.');
        }

        // Log failed login
        AuthEvent::create([
            'event_type' => 'login',
            'user_id'    => null,
            'email'      => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'success'    => false,
        ]);

        return back()
            ->withErrors(['email' => 'The provided credentials do not match our records.'])
            ->onlyInput('email')
            ->with('open_modal', 'login');
    }

    // Show register page
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle registration
    // Handle registration
// Handle registration
public function register(Request $request)
{
    // Debug: Check if we're getting the request
    \Log::info('Registration attempt', ['email' => $request->email]);
    
    // Custom email validation
    $email = $request->email;

    // Basic email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return back()
            ->withErrors(['email' => 'Please enter a valid email address.'])
            ->withInput($request->only('name', 'email'))
            ->with('open_modal', 'register');
    }

    // Check if email already exists
    $emailExists = User::where('email', $email)->exists() ||
                   RegistrationRequest::where('email', $email)->exists();

    if ($emailExists) {
        return back()
            ->withErrors(['email' => 'This email is already registered or has a pending registration request.'])
            ->withInput($request->only('name', 'email'))
            ->with('open_modal', 'register');
    }

    $validator = Validator::make($request->all(), [
        'name'     => 'required|string|max:255',
        'password' => 'required|string|min:6|confirmed',
    ], [
        'password.min' => 'Password must be at least 6 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ]);

    if ($validator->fails()) {
        return back()
            ->withErrors($validator)
            ->withInput($request->only('name', 'email'))
            ->with('open_modal', 'register');
    }

    // Generate a unique token for approval
    $approvalToken = Str::random(64);

    // Create a registration request
    $regRequest = RegistrationRequest::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'encrypted_password' => Crypt::encryptString($request->password),
        'ip_address' => $request->ip(),
        'user_agent' => (string) $request->userAgent(),
        'approval_token' => $approvalToken,
        'status' => 'pending',
    ]);

    // Find main admin (by role) to notify
    $mainAdmin = User::where('role', 'main_admin')->first();

    if ($mainAdmin) {
        Mail::to($mainAdmin->email)->send(new NewUserRegistrationMail($regRequest, $mainAdmin));
    }

    // Log registration attempt
    AuthEvent::create([
        'event_type' => 'register',
        'user_id'    => null,
        'email'      => $regRequest->email,
        'ip_address' => $request->ip(),
        'user_agent' => (string) $request->userAgent(),
        'success'    => true,
        'meta'       => ['registration_id' => $regRequest->id]
    ]);

    return redirect()->route('register')
        ->with('status', 'Registration submitted. Waiting for main admin approval.');
}


    // Dashboard
    public function dashboard()
    {
        return view('dashboard'); // create resources/views/dashboard.blade.php
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
