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

                <a href="{{ route('welcome') }}" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl font-bold" aria-label="Cancel and return to welcome">
                    &times;
                </a>

                @if (session('status'))
                    <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm">
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

                <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">Login</h2>
                <p class="text-gray-700 mb-6 text-center">Sign in to access your clinic dashboard</p>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <input type="email" name="email" placeholder="Email" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    {{-- <input type="password" name="password" placeholder="Password" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"> --}}
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="Password" required
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none pr-10">
                            <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                <!-- Eye slash icon (visible when password is hidden - DEFAULT) -->
                                <svg id="eyeSlashIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                </svg>
                                <!-- Eye icon (visible when password is shown) -->
                                <svg id="eyeIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>

                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const togglePassword = document.getElementById('togglePassword');
                            const passwordInput = document.getElementById('password');
                            const eyeIcon = document.getElementById('eyeIcon');
                            const eyeSlashIcon = document.getElementById('eyeSlashIcon');

                            if (togglePassword && passwordInput) {
                                togglePassword.addEventListener('click', function() {
                                    // Toggle the type attribute
                                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                                    passwordInput.setAttribute('type', type);

                                    // Toggle the eye icons
                                    if (type === 'text') {
                                        // Password is visible - show OPEN eye icon
                                        eyeSlashIcon.classList.add('hidden');
                                        eyeIcon.classList.remove('hidden');
                                    } else {
                                        // Password is hidden - show CROSSED eye icon
                                        eyeSlashIcon.classList.remove('hidden');
                                        eyeIcon.classList.add('hidden');
                                    }
                                });
                            }
                        });
                        </script>

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                        Log In
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
