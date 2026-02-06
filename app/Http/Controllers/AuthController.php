<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users|unique:registration_requests,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('name', 'email'))
                ->with('open_modal', 'register');
        }

        // Create a registration request (no user row yet)
        $regRequest = RegistrationRequest::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'encrypted_password' => Crypt::encryptString($request->password),
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        // Find main admin (by role) to notify
        $mainAdmin = User::where('role', 'main_admin')->first();

        if ($mainAdmin) {
            Mail::to($mainAdmin->email)->send(new NewUserRegistrationMail($regRequest, $mainAdmin));
        }

        // Optionally still log registration attempt
        AuthEvent::create([
            'event_type' => 'register',
            'user_id'    => null,
            'email'      => $regRequest->email,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'success'    => true,
        ]);

        // Do not log the user in yet – wait for approval (show message on register page)
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