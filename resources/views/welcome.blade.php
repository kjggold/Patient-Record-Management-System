<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Clinic</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom Styles for Welcome Page -->
    <style>
        /* Gradient Animated Background */
        body {
            overflow-x: hidden;
            background: linear-gradient(-45deg, #ddeffd, #c3e0ff, #ddeffd);
            background-size: 400% 400%;
            animation: gradientBG 25s ease infinite;
        }

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

        /* Floating Medical Icons */
        .floating-icon {
            position: absolute;
            width: 60px;
            height: 60px;
            opacity: 0.7;
            animation: floatUpDown linear infinite;
        }

        @keyframes floatUpDown {
            0% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-40px) rotate(15deg);
            }
            100% {
                transform: translateY(0) rotate(0deg);
            }
        }

        /* Hero Section */
        .hero-content {
            max-width: 1200px;
            margin-left: 120px;
            /* space from left logo */
        }

        .hero-title {
            font-size: 2.5rem;
            /* huge heading */
        }

        .hero-title .hero-subtitle {
            font-size: 2.5rem;
        }

        .hero-desc {
            font-size: 1.5rem;
            line-height: 1.3;
            max-width: 900px;
        }

        @media (max-width: 768px) {
            .hero-content {
                margin-left: 0;
                padding: 0 1rem;
            }

            .hero-title {
                font-size: 2.75rem;
            }

            .hero-title .hero-subtitle {
                font-size: 1.8rem;
            }

            .hero-desc {
                font-size: clamp(1.2rem, 2vw, 1.5rem);
                line-height: 1.2;
                max-width: 100%;
            }
        }

        /* Hide twinkling stars as we're using floating icons instead */
        .twinkling-star {
            display: none;
        }

        /* Animated background overlay */
        .animated-bg {
            display: none;
        }
    </style>
</head>
<body class="relative min-h-screen">
    <!-- Floating Medical Icons -->
    <img src="https://img.icons8.com/color/96/pill.png"
        class="floating-icon left-10 top-40 animate-[floatUpDown_12s_infinite]" />
    <img src="https://img.icons8.com/color/96/stethoscope.png"
        class="floating-icon left-80 top-80 animate-[floatUpDown_15s_infinite]" />
    <img src="https://img.icons8.com/color/96/thermometer.png"
        class="floating-icon left-1/2 top-64 animate-[floatUpDown_18s_infinite]" />
    <img src="https://img.icons8.com/color/96/syringe.png"
        class="floating-icon left-1/4 top-96 animate-[floatUpDown_20s_infinite]" />
    <img src="https://img.icons8.com/color/96/heart-with-pulse.png"
        class="floating-icon left-3/4 top-48 animate-[floatUpDown_22s_infinite]" />

    <!-- Main Content -->
    <div id="welcomeContent" class="relative z-10 transition duration-200">
        <!-- Header (Simplified version from your first code) -->
        <header class="w-full px-6 py-4">
            <div class="w-full flex items-center justify-between">
                <!-- Logo (Fully Left) -->
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-blue-500 rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">MediCore</span>
                </div>

                <!-- Navigation buttons (from second code) -->
                <nav class="hidden md:flex items-center space-x-6">

                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">Dashboard</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition-colors duration-200">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- <button type="button" data-open-modal="login" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                            Login
                        </button>
                        <button type="button" data-open-modal="register" class="px-4 py-2 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                            Register
                        </button> --}}
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Hero Section (Your new design) -->
        <main class="flex flex-col mt-32">
            <div class="hero-content">
                <!-- Main Heading -->
                <h1 class="hero-title font-bold text-gray-900 mb-6 text-left">
                    Organized Patient Management
                    <span class="hero-subtitle block text-blue-600 mt-2">For Clinics</span>
                </h1>

                <!-- Description -->
                <p class="hero-desc text-gray-700 mb-10 text-left">
                    Where every patient's journey is carefully documented, securely preserved, and effortlessly
                    accessible—transforming healthcare records into a seamless symphony of precision and care.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-start mb-12">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                            Go to Dashboard
                        </a>
                    @else
                        <button type="button" data-open-modal="login"
                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                            Login
                        </button>
                        {{-- <button type="button" data-open-modal="register"
                            class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                            Register
                        </button> --}}
                    @endauth
                </div>
            </div>
        </main>
    </div>

    <!-- Auth Modal Overlay (Kept from second code) -->
    <div id="authOverlay" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div id="authBackdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

        <!-- Modal Container -->
        <div class="relative min-h-full flex items-center justify-center p-4">
            <!-- Login Modal -->
            <div id="loginModal" class="hidden w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Login</h2>
                        <p class="text-gray-600 mt-1">Sign in to access your clinic dashboard</p>
                    </div>
                    <button type="button" data-close-modal class="p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="login_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            id="login_email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="admin@clinic.com"
                            required
                            autofocus
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="login_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input
                            type="password"
                            id="login_password"
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="••••••••"
                            required
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                            Remember me
                        </label>
                        <button type="submit" class="px-5 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            Sign In
                        </button>
                    </div>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Don't have an account?
                    <button type="button" data-switch-modal="register" class="font-semibold text-blue-600 hover:text-blue-700 ml-1">
                        Register
                    </button>
                </p>
            </div>

            <!-- Register Modal -->
            <div id="registerModal" class="hidden w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
                <div class="flex items-start justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                        <p class="text-gray-600 mt-1">Register to access your clinic dashboard</p>
                    </div>
                    <button type="button" data-close-modal class="p-2 rounded-lg hover:bg-gray-100 text-gray-600">
                        <span class="sr-only">Close</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="register_name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <input
                            type="text"
                            id="register_name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="Jane Doe"
                            required
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="register_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input
                            type="email"
                            id="register_email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="you@example.com"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="register_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input
                            type="password"
                            id="register_password"
                            name="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="••••••••"
                            required
                        />
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="register_password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input
                            type="password"
                            id="register_password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-900 placeholder-gray-400"
                            placeholder="••••••••"
                            required
                        />
                    </div>

                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                        Create Account
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-gray-600">
                    Already have an account?
                    <button type="button" data-switch-modal="login" class="font-semibold text-blue-600 hover:text-blue-700 ml-1">
                        Back to login
                    </button>
                </p>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const openModalFromServer = {!! json_encode(session('open_modal')) !!};

            const overlay = document.getElementById('authOverlay');
            const backdrop = document.getElementById('authBackdrop');
            const content = document.getElementById('welcomeContent');
            const loginModal = document.getElementById('loginModal');
            const registerModal = document.getElementById('registerModal');

            function showOverlay() {
                overlay.classList.remove('hidden');
                content.classList.add('blur-sm');
                content.classList.add('pointer-events-none');
                document.body.classList.add('overflow-hidden');
            }

            function hideOverlay() {
                overlay.classList.add('hidden');
                loginModal.classList.add('hidden');
                registerModal.classList.add('hidden');
                content.classList.remove('blur-sm');
                content.classList.remove('pointer-events-none');
                document.body.classList.remove('overflow-hidden');
            }

            function openModal(which) {
                showOverlay();
                loginModal.classList.toggle('hidden', which !== 'login');
                registerModal.classList.toggle('hidden', which !== 'register');
            }

            document.querySelectorAll('[data-open-modal]').forEach((btn) => {
                btn.addEventListener('click', () => openModal(btn.getAttribute('data-open-modal')));
            });

            document.querySelectorAll('[data-close-modal]').forEach((btn) => {
                btn.addEventListener('click', hideOverlay);
            });

            document.querySelectorAll('[data-switch-modal]').forEach((btn) => {
                btn.addEventListener('click', () => openModal(btn.getAttribute('data-switch-modal')));
            });

            backdrop.addEventListener('click', hideOverlay);
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !overlay.classList.contains('hidden')) hideOverlay();
            });

            if (openModalFromServer === 'login' || openModalFromServer === 'register') {
                openModal(openModalFromServer);
            }
        })();
    </script>
</body>
</html>
