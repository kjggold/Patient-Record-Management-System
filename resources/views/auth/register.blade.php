@extends('layouts.app')

@section('title', 'Register - MediCore Clinic')

@section('content')

    <div class="relative min-h-screen">

        <!-- Welcome Page Content (Blurred Background) -->
        <div id="welcomeContent" class="absolute inset-0 z-0 pointer-events-none">
            <!-- Hero Section -->
            <main class="flex flex-col pt-56 pl-24">
                <div class="hero-content text-gray-900">
                    <h1 class="hero-title font-bold text-5xl mb-6">
                        Organized Patient Management
                        <span class="hero-subtitle block text-blue-600 mt-2 text-4xl">For Clinics</span>
                    </h1>
                    <p class="hero-desc text-xl line-clamp-2 max-w-xl">
                        Every patient record, securely organized and instantly accessible —
                        designed to simplify clinic workflows and elevate quality of care.
                    </p>
                </div>
            </main>

            <!-- Floating Medical Icons -->
            <img src="https://img.icons8.com/color/96/pill.png"
                class="floating-icon absolute left-10 top-40 w-16 opacity-70 animate-[floatUpDown_12s_infinite]" />
            <img src="https://img.icons8.com/color/96/stethoscope.png"
                class="floating-icon absolute left-80 top-80 w-16 opacity-70 animate-[floatUpDown_15s_infinite]" />
            <img src="https://img.icons8.com/color/96/thermometer.png"
                class="floating-icon absolute left-1/2 top-64 w-16 opacity-70 animate-[floatUpDown_18s_infinite]" />
            <img src="https://img.icons8.com/color/96/syringe.png"
                class="floating-icon absolute left-1/4 top-96 w-16 opacity-70 animate-[floatUpDown_20s_infinite]" />
            <img src="https://img.icons8.com/color/96/heart-with-pulse.png"
                class="floating-icon absolute left-3/4 top-48 w-16 opacity-70 animate-[floatUpDown_22s_infinite]" />
        </div>

        <!-- Blur Overlay -->
        <div class="absolute inset-0 z-10 backdrop-blur-sm bg-black bg-opacity-20"></div>

        <!-- Register Form Card -->
        <div class="relative z-20 flex items-center justify-center min-h-screen px-4">
            <div
                class="glass-card bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-md animate-fadeIn">

                <a href="{{ route('welcome') }}" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl font-bold" aria-label="Cancel and return to welcome">
                    &times;
                </a>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-800 px-4 py-3 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">Create Account</h2>
                <p class="text-gray-700 mb-6 text-center">Register to access your clinic dashboard</p>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <input type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-900 placeholder-gray-400">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-900 placeholder-gray-400">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-900 placeholder-gray-400">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <input type="password" name="password_confirmation" placeholder="Confirm Password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white text-gray-900 placeholder-gray-400">
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit"
                        class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Create Account
                    </button>
                </form>

                @if (!session('status'))
                    <p class="mt-6 text-center text-sm text-gray-700">
                        Already have an account?
                        <a href="{{ route('login') }}" class="text-blue-600 font-semibold ml-1 hover:text-blue-700">
                            Back to login
                        </a>
                    </p>
                @else
                    <p class="mt-6 text-center text-sm text-gray-500">
                        Please wait until the main admin approves your registration. You’ll be able to log in after approval.
                    </p>
                @endif
            </div>
        </div>

    </div>

    <!-- Animations -->
    <style>
        @keyframes floatUpDown {
            0% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(10deg);
            }

            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        .animate-\[floatUpDown_12s_infinite\] {
            animation: floatUpDown 12s ease-in-out infinite;
        }

        .animate-\[floatUpDown_15s_infinite\] {
            animation: floatUpDown 15s ease-in-out infinite;
        }

        .animate-\[floatUpDown_18s_infinite\] {
            animation: floatUpDown 18s ease-in-out infinite;
        }

        .animate-\[floatUpDown_20s_infinite\] {
            animation: floatUpDown 20s ease-in-out infinite;
        }

        .animate-\[floatUpDown_22s_infinite\] {
            animation: floatUpDown 22s ease-in-out infinite;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

@endsection