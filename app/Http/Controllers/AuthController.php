<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\AuthEvent;
use App\Models\User;

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

            AuthEvent::create([
                'event_type' => 'login',
                'user_id'    => Auth::id(),
                'email'      => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'success'    => true,
            ]);

            return redirect()->intended(route('dashboard'));
        }

        // Login failed
        AuthEvent::create([
            'event_type' => 'login',
            'user_id'    => null,
            'email'      => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'success'    => false,
        ]);

        return back()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])
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
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('name', 'email'))
                ->with('open_modal', 'register');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        AuthEvent::create([
            'event_type' => 'register',
            'user_id'    => $user->id,
            'email'      => $user->email,
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
            'success'    => true,
        ]);

        return redirect()->route('dashboard');
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
