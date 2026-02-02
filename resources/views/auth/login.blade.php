@extends('layouts.app')

@section('title', 'Login - MediCore Clinic')

@section('content')

    <div class="relative min-h-screen">

        <!-- Welcome Page Content (Blurred Background) -->
        <div id="welcomeContent" class="absolute inset-0 z-0 pointer-events-none">
            <!-- Include the full welcome page layout, but without the top logo -->
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

        <!-- Login Form Card -->
        <div class="relative z-20 flex items-center justify-center min-h-screen px-4">
            <div
                class="glass-card bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-md animate-fadeIn">

                <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">Login</h2>
                <p class="text-gray-700 mb-6 text-center">Sign in to access your clinic dashboard</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Sign In
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-700">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-blue-600 font-semibold">Register</a>
                </p>
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

        /* Gradient Background Animation (optional if using Tailwind) */
        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        .bg-gradient-animate {
            animation: gradientBG 25s ease infinite;
            background-size: 400% 400%;
        }
    </style>

@endsection
