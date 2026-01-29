@extends('layouts.app')

@section('title', 'Login - MediCore Clinic')

@section('content')

<div class="relative min-h-screen">

    <!-- Welcome Page Content (Blurred Background) -->
    <div id="welcomeContent" class="absolute inset-0 z-0 pointer-events-none">
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

        <!-- Floating Icons -->
        <img src="https://img.icons8.com/color/96/pill.png"
            class="absolute left-10 top-40 w-16 opacity-70 animate-[floatUpDown_12s_infinite]" />
        <img src="https://img.icons8.com/color/96/stethoscope.png"
            class="absolute left-80 top-80 w-16 opacity-70 animate-[floatUpDown_15s_infinite]" />
        <img src="https://img.icons8.com/color/96/thermometer.png"
            class="absolute left-1/2 top-64 w-16 opacity-70 animate-[floatUpDown_18s_infinite]" />
        <img src="https://img.icons8.com/color/96/syringe.png"
            class="absolute left-1/4 top-96 w-16 opacity-70 animate-[floatUpDown_20s_infinite]" />
        <img src="https://img.icons8.com/color/96/heart-with-pulse.png"
            class="absolute left-3/4 top-48 w-16 opacity-70 animate-[floatUpDown_22s_infinite]" />
    </div>

    <!-- Blur Overlay -->
    <div class="absolute inset-0 z-10 backdrop-blur-sm bg-black bg-opacity-20"></div>

    <!-- Login Card -->
    <div class="relative z-20 flex items-center justify-center min-h-screen px-4">
        <div
            class="relative bg-white bg-opacity-90 backdrop-blur-md p-8 rounded-3xl shadow-2xl w-full max-w-md animate-fadeIn">

            <!-- ❌ Cancel Button -->
            <a href="{{ route('welcome') }}"
               class="absolute top-4 right-4 text-gray-500 hover:text-red-500 text-2xl font-bold transition">
                &times;
            </a>

            <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">Login</h2>
            <p class="text-gray-700 mb-6 text-center">
                Sign in to access your clinic dashboard
            </p>

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
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold">
                    Register
                </a>
            </p>
        </div>
    </div>
</div>

<!-- Animations -->
<style>
@keyframes floatUpDown {
    0% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0); }
}
.animate-\[floatUpDown_12s_infinite\] { animation: floatUpDown 12s ease-in-out infinite; }
.animate-\[floatUpDown_15s_infinite\] { animation: floatUpDown 15s ease-in-out infinite; }
.animate-\[floatUpDown_18s_infinite\] { animation: floatUpDown 18s ease-in-out infinite; }
.animate-\[floatUpDown_20s_infinite\] { animation: floatUpDown 20s ease-in-out infinite; }
.animate-\[floatUpDown_22s_infinite\] { animation: floatUpDown 22s ease-in-out infinite; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fadeIn {
    animation: fadeIn 0.5s ease-out forwards;
}
</style>

@endsection
