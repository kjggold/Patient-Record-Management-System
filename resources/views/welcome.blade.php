<!DOCTYPE html>
<html lang="en">
 eaindra

<head>
 HEAD
eaindra
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MediCore Clinic</title>
        HEAD
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Remove the inline body style - it's in app.css already -->
    </head>
    <!-- REMOVE bg-white from body class -->

<body class="relative">
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    main

    c691b35 (Welcome Page)

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Clinic</title>
 921fcf8 (My updates on eaindra branch)

<head>
 main
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
 eaindra
eaindra
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <h1 class="mb-1 font-medium">Let's get started</h1>
                    <p class="mb-2 text-[#706f6c] dark:text-[#A1A09A]">Laravel has an incredibly rich ecosystem. <br>We suggest starting with the following.</p>
                    <ul class="flex flex-col mb-4 lg:mb-6">
                        <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:top-1/2 before:bottom-0 before:left-[0.4rem] before:absolute">
                            <span class="relative py-1 bg-white dark:bg-[#161615]">
                                <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                    <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                                </span>
                            </span>
                            <span>
                                Read the
                                <a href="https://laravel.com/docs" target="_blank" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                                    <span>Documentation</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-2.5 h-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                        <li class="flex items-center gap-4 py-2 relative before:border-l before:border-[#e3e3e0] dark:before:border-[#3E3E3A] before:bottom-1/2 before:top-0 before:left-[0.4rem] before:absolute">
                            <span class="relative py-1 bg-white dark:bg-[#161615]">
                                <span class="flex items-center justify-center rounded-full bg-[#FDFDFC] dark:bg-[#161615] shadow-[0px_0px_1px_0px_rgba(0,0,0,0.03),0px_1px_2px_0px_rgba(0,0,0,0.06)] w-3.5 h-3.5 border dark:border-[#3E3E3A] border-[#e3e3e0]">
                                    <span class="rounded-full bg-[#dbdbd7] dark:bg-[#3E3E3A] w-1.5 h-1.5"></span>
                                </span>
                            </span>
                            <span>
                                Watch video tutorials at
                                <a href="https://laracasts.com" target="_blank" class="inline-flex items-center space-x-1 font-medium underline underline-offset-4 text-[#f53003] dark:text-[#FF4433] ml-1">
                                    <span>Laracasts</span>
                                    <svg
                                        width="10"
                                        height="11"
                                        viewBox="0 0 10 11"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-2.5 h-2.5"
                                    >
                                        <path
                                            d="M7.70833 6.95834V2.79167H3.54167M2.5 8L7.5 3.00001"
                                            stroke="currentColor"
                                            stroke-linecap="square"
                                        />
                                    </svg>
                                </a>
                            </span>
                        </li>
                    </ul>
                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a href="https://cloud.laravel.com" target="_blank" class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-5 py-1.5 bg-[#1b1b18] rounded-sm border border-black text-white text-sm leading-normal">
                                Deploy now
                            </a>
                        </li>
                    </ul>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Clinic</title>
main

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- App CSS -->
    <style>
        /* Gradient Animated Background */
        body {
            overflow-x: hidden;
            background: linear-gradient(-45deg, #edf7ff, #f9fbfc, #edf3f8);
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
                margin-left: 24px;
                margin-right: 24px;
            }

            .hero-title {
                font-size: 2.2rem;
            }

            .hero-desc {
                font-size: 1.2rem;
                line-height: 1.4;
            }

            .line-clamp-2 {
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
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
    <div class="relative z-10">

        <!-- Header (UNCHANGED) -->
        <header class="w-full px-6 py-4">
            <div class="w-full flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-blue-500 rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
HEAD

eaindra
                    <div></div>

                <div></div>
            </div>
        </header>

        <!-- Hero Section -->
        <!-- Hero Section -->
        <main class="flex flex-col mt-32"> <!-- reduced from mt-48 to mt-32 -->
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
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                        Login
                    </a>

                    <button
                        class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                        Learn More
                    </button>
c691b35 (Welcome Page)

 main

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
 eaindra
main
main

                    <span class="text-2xl font-bold text-gray-900">MediCore</span>
921fcf8 (My updates on eaindra branch)
                </div>
            </div>
        </header>

        <!-- Hero Section (MOVED LOWER) -->
        <main class="flex flex-col pt-48 md:pt-56">
            <div class="hero-content">

                <h1 class="hero-title font-bold text-gray-900 mb-6 text-left">
                    Organized Patient Management
                    <span class="hero-subtitle block text-blue-600 mt-2">For Clinics</span>
                </h1>

                <p class="hero-desc text-gray-700 mb-10 text-left line-clamp-2">
                    Every patient record, securely organized and instantly accessible —
                    designed to simplify clinic workflows and elevate quality of care.
                </p>


 HEAD
                                        <!-- CTA Buttons -->
                                        <div class="flex flex-col sm:flex-row gap-4 justify-start mb-12">
                                            <a href="{{ route('login') }}"
                                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                                Login
                                            </a>

                                            <button
                                                class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                                                Learn More
                                            </button>
                                            c691b35 (Welcome Page)

                                            <!-- Hero Section -->
                                            <!-- Hero Section -->
                                            <main class="flex flex-col mt-32"> <!-- reduced from mt-48 to mt-32 -->
                                                <div class="hero-content">

                                                    <!-- Main Heading -->
                                                    <h1 class="hero-title font-bold text-gray-900 mb-6 text-left">
                                                        Organized Patient Management
                                                        <span class="hero-subtitle block text-blue-600 mt-2">For
                                                            Clinics</span>
                                                    </h1>

                                                    <!-- Description -->
                                                    <p class="hero-desc text-gray-700 mb-10 text-left">
                                                        Where every patient's journey is carefully documented, securely
                                                        preserved, and effortlessly
                                                        accessible—transforming healthcare records into a seamless
                                                        symphony of precision and care.
                                                    </p>


                                                    <!-- CTA Buttons -->
                                                    <div class="flex flex-col sm:flex-row gap-4 justify-start mb-12">
                                                        <a href="{{ route('login') }}"
                                                            class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                                            Login
                                                        </a>

                                                        <button
                                                            class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                                                            Learn More
                                                        </button>
                                                        main
                                                    </div>

HEAD eaindra HEAD {{-- Light Mode 12 SVG --}} <svg
                                                        class="w-[448px] max-w-none relative -mt-[4.9rem] -ml-8 lg:ml-0 lg:-mt-[6.6rem] dark:hidden"
                                                        viewBox="0 0 440 376" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M188.263 355.73L188.595 355.73C195.441 348.845 205.766 339.761 219.569 328.477C232.93 317.193 242.978 308.205 249.714 301.511C256.34 294.626 260.867 287.358 263.296 279.708C265.725 272.058 264.565 264.121 259.816 255.896C254.516 246.716 247.062 239.352 237.454 233.805C227.957 228.067 217.908 225.198 207.307 225.198C196.927 225.197 190.136 227.97 186.934 233.516C183.621 238.872 184.726 246.331 190.247 255.894L125.647 255.891C116.371 239.825 112.395 225.481 113.72 212.858C115.265 200.235 121.559 190.481 132.602 183.596C143.754 176.52 158.607 172.982 177.159 172.983C196.594 172.984 215.863 176.523 234.968 183.6C253.961 190.486 271.299 200.241 286.98 212.864C302.661 225.488 315.14 239.833 324.416 255.899C333.03 270.817 336.841 283.918 335.847 295.203C335.075 306.487 331.376 316.336 324.75 324.751C318.346 333.167 308.408 343.494 294.936 355.734L377.094 355.737L405.917 405.656L217.087 405.649L188.263 355.73Z"
                                                                fill="black" />
                                                            <path
                                                                d="M9.11884 226.339L-13.7396 226.338L-42.7286 176.132L43.0733 176.135L175.595 405.649L112.651 405.647L9.11884 226.339Z"
                                                                fill="black" />
                                                            <path
                                                                d="M188.263 355.73L188.595 355.73C195.441 348.845 205.766 339.761 219.569 328.477C232.93 317.193 242.978 308.205 249.714 301.511C256.34 294.626 260.867 287.358 263.296 279.708C265.725 272.058 264.565 264.121 259.816 255.896C254.516 246.716 247.062 239.352 237.454 233.805C227.957 228.067 217.908 225.198 207.307 225.198C196.927 225.197 190.136 227.97 186.934 233.516C183.621 238.872 184.726 246.331 190.247 255.894L125.647 255.891C116.371 239.825 112.395 225.481 113.72 212.858C115.265 200.235 121.559 190.481 132.602 183.596C143.754 176.52 158.607 172.982 177.159 172.983C196.594 172.984 215.863 176.523 234.968 183.6C253.961 190.486 271.299 200.241 286.98 212.864C302.661 225.488 315.14 239.833 324.416 255.899C333.03 270.817 336.841 283.918 335.847 295.203C335.075 306.487 331.376 316.336 324.75 324.751C318.346 333.167 308.408 343.494 294.936 355.734L377.094 355.737L405.917 405.656L217.087 405.649L188.263 355.73Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                            <path
                                                                d="M9.11884 226.339L-13.7396 226.338L-42.7286 176.132L43.0733 176.135L175.595 405.649L112.651 405.647L9.11884 226.339Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                            <path
                                                                d="M204.592 327.449L204.923 327.449C211.769 320.564 222.094 311.479 235.897 300.196C249.258 288.912 259.306 279.923 266.042 273.23C272.668 266.345 277.195 259.077 279.624 251.427C282.053 243.777 280.893 235.839 276.145 227.615C270.844 218.435 263.39 211.071 253.782 205.524C244.285 199.786 234.236 196.917 223.635 196.916C213.255 196.916 206.464 199.689 203.262 205.235C199.949 210.59 201.054 218.049 206.575 227.612L141.975 227.61C132.699 211.544 128.723 197.2 130.048 184.577C131.593 171.954 137.887 162.2 148.93 155.315C160.083 148.239 174.935 144.701 193.487 144.702C212.922 144.703 232.192 148.242 251.296 155.319C270.289 162.205 287.627 171.96 303.308 184.583C318.989 197.207 331.468 211.552 340.745 227.618C349.358 242.536 353.169 255.637 352.175 266.921C351.403 278.205 347.704 288.055 341.078 296.47C334.674 304.885 324.736 315.213 311.264 327.453L393.422 327.456L422.246 377.375L233.415 377.368L204.592 327.449Z"
                                                                fill="#F8B803" />
                                                            <path
                                                                d="M25.447 198.058L2.58852 198.057L-26.4005 147.851L59.4015 147.854L191.923 377.368L128.979 377.365L25.447 198.058Z"
                                                                fill="#F8B803" />
                                                            <path
                                                                d="M204.592 327.449L204.923 327.449C211.769 320.564 222.094 311.479 235.897 300.196C249.258 288.912 259.306 279.923 266.042 273.23C272.668 266.345 277.195 259.077 279.624 251.427C282.053 243.777 280.893 235.839 276.145 227.615C270.844 218.435 263.39 211.071 253.782 205.524C244.285 199.786 234.236 196.917 223.635 196.916C213.255 196.916 206.464 199.689 203.262 205.235C199.949 210.59 201.054 218.049 206.575 227.612L141.975 227.61C132.699 211.544 128.723 197.2 130.048 184.577C131.593 171.954 137.887 162.2 148.93 155.315C160.083 148.239 174.935 144.701 193.487 144.702C212.922 144.703 232.192 148.242 251.296 155.319C270.289 162.205 287.627 171.96 303.308 184.583C318.989 197.207 331.468 211.552 340.745 227.618C349.358 242.536 353.169 255.637 352.175 266.921C351.403 278.205 347.704 288.055 341.078 296.47C334.674 304.885 324.736 315.213 311.264 327.453L393.422 327.456L422.246 377.375L233.415 377.368L204.592 327.449Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                            <path
                                                                d="M25.447 198.058L2.58852 198.057L-26.4005 147.851L59.4015 147.854L191.923 377.368L128.979 377.365L25.447 198.058Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                        </g>
                                                        <g style="mix-blend-mode: hard-light"
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M217.342 305.363L217.673 305.363C224.519 298.478 234.844 289.393 248.647 278.11C262.008 266.826 272.056 257.837 278.792 251.144C285.418 244.259 289.945 236.991 292.374 229.341C294.803 221.691 293.643 213.753 288.895 205.529C283.594 196.349 276.14 188.985 266.532 183.438C257.035 177.7 246.986 174.831 236.385 174.83C226.005 174.83 219.214 177.603 216.012 183.149C212.699 188.504 213.804 195.963 219.325 205.527L154.725 205.524C145.449 189.458 141.473 175.114 142.798 162.491C144.343 149.868 150.637 140.114 161.68 133.229C172.833 126.153 187.685 122.615 206.237 122.616C225.672 122.617 244.942 126.156 264.046 133.233C283.039 140.119 300.377 149.874 316.058 162.497C331.739 175.121 344.218 189.466 353.495 205.532C362.108 220.45 365.919 233.551 364.925 244.835C364.153 256.12 360.454 265.969 353.828 274.384C347.424 282.799 337.486 293.127 324.014 305.367L406.172 305.37L434.996 355.289L246.165 355.282L217.342 305.363Z"
                                                                fill="#F0ACB8" />
                                                            <path
                                                                d="M38.197 175.972L15.3385 175.971L-13.6505 125.765L72.1515 125.768L204.673 355.282L141.729 355.279L38.197 175.972Z"
                                                                fill="#F0ACB8" />
                                                            <path
                                                                d="M217.342 305.363L217.673 305.363C224.519 298.478 234.844 289.393 248.647 278.11C262.008 266.826 272.056 257.837 278.792 251.144C285.418 244.259 289.945 236.991 292.374 229.341C294.803 221.691 293.643 213.753 288.895 205.529C283.594 196.349 276.14 188.985 266.532 183.438C257.035 177.7 246.986 174.831 236.385 174.83C226.005 174.83 219.214 177.603 216.012 183.149C212.699 188.504 213.804 195.963 219.325 205.527L154.725 205.524C145.449 189.458 141.473 175.114 142.798 162.491C144.343 149.868 150.637 140.114 161.68 133.229C172.833 126.153 187.685 122.615 206.237 122.616C225.672 122.617 244.942 126.156 264.046 133.233C283.039 140.119 300.377 149.874 316.058 162.497C331.739 175.121 344.218 189.466 353.495 205.532C362.108 220.45 365.919 233.551 364.925 244.835C364.153 256.12 360.454 265.969 353.828 274.384C347.424 282.799 337.486 293.127 324.014 305.367L406.172 305.37L434.996 355.289L246.165 355.282L217.342 305.363Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                            <path
                                                                d="M38.197 175.972L15.3385 175.971L-13.6505 125.765L72.1515 125.768L204.673 355.282L141.729 355.279L38.197 175.972Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                        </g>
                                                        <g style="mix-blend-mode: plus-darker"
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M230.951 281.792L231.282 281.793C238.128 274.907 248.453 265.823 262.256 254.539C275.617 243.256 285.666 234.267 292.402 227.573C299.027 220.688 303.554 213.421 305.983 205.771C308.412 198.12 307.253 190.183 302.504 181.959C297.203 172.778 289.749 165.415 280.142 159.868C270.645 154.13 260.596 151.26 249.995 151.26C239.615 151.26 232.823 154.033 229.621 159.579C226.309 164.934 227.413 172.393 232.935 181.956L168.335 181.954C159.058 165.888 155.082 151.543 156.407 138.92C157.953 126.298 164.247 116.544 175.289 109.659C186.442 102.583 201.294 99.045 219.846 99.0457C239.281 99.0464 258.551 102.585 277.655 109.663C296.649 116.549 313.986 126.303 329.667 138.927C345.349 151.551 357.827 165.895 367.104 181.961C375.718 196.88 379.528 209.981 378.535 221.265C377.762 232.549 374.063 242.399 367.438 250.814C361.033 259.229 351.095 269.557 337.624 281.796L419.782 281.8L448.605 331.719L259.774 331.712L230.951 281.792Z"
                                                                fill="#F3BEC7" />
                                                            <path
                                                                d="M51.8063 152.402L28.9479 152.401L-0.0411453 102.195L85.7608 102.198L218.282 331.711L155.339 331.709L51.8063 152.402Z"
                                                                fill="#F3BEC7" />
                                                            <path
                                                                d="M230.951 281.792L231.282 281.793C238.128 274.907 248.453 265.823 262.256 254.539C275.617 243.256 285.666 234.267 292.402 227.573C299.027 220.688 303.554 213.421 305.983 205.771C308.412 198.12 307.253 190.183 302.504 181.959C297.203 172.778 289.749 165.415 280.142 159.868C270.645 154.13 260.596 151.26 249.995 151.26C239.615 151.26 232.823 154.033 229.621 159.579C226.309 164.934 227.413 172.393 232.935 181.956L168.335 181.954C159.058 165.888 155.082 151.543 156.407 138.92C157.953 126.298 164.247 116.544 175.289 109.659C186.442 102.583 201.294 99.045 219.846 99.0457C239.281 99.0464 258.551 102.585 277.655 109.663C296.649 116.549 313.986 126.303 329.667 138.927C345.349 151.551 357.827 165.895 367.104 181.961C375.718 196.88 379.528 209.981 378.535 221.265C377.762 232.549 374.063 242.399 367.438 250.814C361.033 259.229 351.095 269.557 337.624 281.796L419.782 281.8L448.605 331.719L259.774 331.712L230.951 281.792Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                            <path
                                                                d="M51.8063 152.402L28.9479 152.401L-0.0411453 102.195L85.7608 102.198L218.282 331.711L155.339 331.709L51.8063 152.402Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                        </g>
                                                        <g
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M188.467 355.363L188.798 355.363C195.644 348.478 205.969 339.393 219.772 328.11C233.133 316.826 243.181 307.837 249.917 301.144C253.696 297.217 256.792 293.166 259.205 288.991C261.024 285.845 262.455 282.628 263.499 279.341C265.928 271.691 264.768 263.753 260.02 255.529C254.719 246.349 247.265 238.985 237.657 233.438C228.16 227.7 218.111 224.831 207.51 224.83C197.13 224.83 190.339 227.603 187.137 233.149C183.824 238.504 184.929 245.963 190.45 255.527L125.851 255.524C116.574 239.458 112.598 225.114 113.923 212.491C114.615 206.836 116.261 201.756 118.859 197.253C122.061 191.704 126.709 187.03 132.805 183.229C143.958 176.153 158.81 172.615 177.362 172.616C196.797 172.617 216.067 176.156 235.171 183.233C254.164 190.119 271.502 199.874 287.183 212.497C302.864 225.121 315.343 239.466 324.62 255.532C333.233 270.45 337.044 283.551 336.05 294.835C335.46 303.459 333.16 311.245 329.151 318.194C327.915 320.337 326.515 322.4 324.953 324.384C318.549 332.799 308.611 343.127 295.139 355.367L377.297 355.37L406.121 405.289L217.29 405.282L188.467 355.363Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M9.32197 225.972L-13.5365 225.971L-42.5255 175.765L43.2765 175.768L175.798 405.282L112.854 405.279L9.32197 225.972Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M345.247 111.915C329.566 99.2919 312.229 89.5371 293.235 82.6512L235.167 183.228C254.161 190.114 271.498 199.869 287.179 212.492L345.247 111.915Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M382.686 154.964C373.41 138.898 360.931 124.553 345.25 111.93L287.182 212.506C302.863 225.13 315.342 239.475 324.618 255.541L382.686 154.964Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M293.243 82.6472C274.139 75.57 254.869 72.031 235.434 72.0303L177.366 172.607C196.801 172.608 216.071 176.147 235.175 183.224L293.243 82.6472Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M394.118 194.257C395.112 182.973 391.301 169.872 382.688 154.953L324.619 255.53C333.233 270.448 337.044 283.55 336.05 294.834L394.118 194.257Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M235.432 72.0311C216.88 72.0304 202.027 75.5681 190.875 82.6442L132.806 183.221C143.959 176.145 158.812 172.607 177.363 172.608L235.432 72.0311Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M265.59 124.25C276.191 124.251 286.24 127.12 295.737 132.858L237.669 233.435C228.172 227.697 218.123 224.828 207.522 224.827L265.59 124.25Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M295.719 132.859C305.326 138.406 312.78 145.77 318.081 154.95L260.013 255.527C254.712 246.347 247.258 238.983 237.651 233.436L295.719 132.859Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M387.218 217.608C391.227 210.66 393.527 202.874 394.117 194.25L336.049 294.827C335.459 303.451 333.159 311.237 329.15 318.185L387.218 217.608Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M245.211 132.577C248.413 127.03 255.204 124.257 265.584 124.258L207.516 224.835C197.136 224.834 190.345 227.607 187.143 233.154L245.211 132.577Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M318.094 154.945C322.842 163.17 324.002 171.107 321.573 178.757L263.505 279.334C265.934 271.684 264.774 263.746 260.026 255.522L318.094 154.945Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M176.925 96.6737C180.127 91.1249 184.776 86.4503 190.871 82.6499L132.803 183.227C126.708 187.027 122.059 191.702 118.857 197.25L176.925 96.6737Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M387.226 217.606C385.989 219.749 384.59 221.813 383.028 223.797L324.96 324.373C326.522 322.39 327.921 320.326 329.157 318.183L387.226 217.606Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M317.269 188.408C319.087 185.262 320.519 182.045 321.562 178.758L263.494 279.335C262.451 282.622 261.019 285.839 259.201 288.985L317.269 188.408Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M245.208 132.573C241.895 137.928 243 145.387 248.522 154.95L190.454 255.527C184.932 245.964 183.827 238.505 187.14 233.15L245.208 132.573Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M176.93 96.6719C174.331 101.175 172.686 106.255 171.993 111.91L113.925 212.487C114.618 206.831 116.263 201.752 118.862 197.249L176.93 96.6719Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M317.266 188.413C314.853 192.589 311.757 196.64 307.978 200.566L249.91 301.143C253.689 297.216 256.785 293.166 259.198 288.99L317.266 188.413Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M464.198 304.708L435.375 254.789L377.307 355.366L406.13 405.285L464.198 304.708Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M353.209 254.787C366.68 242.548 376.618 232.22 383.023 223.805L324.955 324.382C318.55 332.797 308.612 343.124 295.141 355.364L353.209 254.787Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M435.37 254.787L353.212 254.784L295.144 355.361L377.302 355.364L435.37 254.787Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M183.921 154.947L248.521 154.95L190.453 255.527L125.853 255.524L183.921 154.947Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M171.992 111.914C170.668 124.537 174.643 138.881 183.92 154.947L125.852 255.524C116.575 239.458 112.599 225.114 113.924 212.491L171.992 111.914Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M307.987 200.562C301.251 207.256 291.203 216.244 277.842 227.528L219.774 328.105C233.135 316.821 243.183 307.832 249.919 301.139L307.987 200.562Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M15.5469 75.1797L44.5359 125.386L-13.5321 225.963L-42.5212 175.756L15.5469 75.1797Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M277.836 227.536C264.033 238.82 253.708 247.904 246.862 254.789L188.794 355.366C195.64 348.481 205.965 339.397 219.768 328.113L277.836 227.536Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M275.358 304.706L464.189 304.713L406.12 405.29L217.29 405.283L275.358 304.706Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M44.5279 125.39L67.3864 125.39L9.31834 225.967L-13.5401 225.966L44.5279 125.39Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M101.341 75.1911L233.863 304.705L175.795 405.282L43.2733 175.768L101.341 75.1911ZM15.5431 75.19L-42.525 175.767L43.277 175.77L101.345 75.1932L15.5431 75.19Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M246.866 254.784L246.534 254.784L188.466 355.361L188.798 355.361L246.866 254.784Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M246.539 254.781L275.362 304.701L217.294 405.277L188.471 355.358L246.539 254.781Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M67.3906 125.391L170.923 304.698L112.855 405.275L9.32257 225.967L67.3906 125.391Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                            <path
                                                                d="M170.921 304.699L233.865 304.701L175.797 405.278L112.853 405.276L170.921 304.699Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="bevel" />
                                                        </g>
                                                        <g style="mix-blend-mode: hard-light"
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M246.544 254.79L246.875 254.79C253.722 247.905 264.046 238.82 277.849 227.537C291.21 216.253 301.259 207.264 307.995 200.57C314.62 193.685 319.147 186.418 321.577 178.768C324.006 171.117 322.846 163.18 318.097 154.956C312.796 145.775 305.342 138.412 295.735 132.865C286.238 127.127 276.189 124.258 265.588 124.257C255.208 124.257 248.416 127.03 245.214 132.576C241.902 137.931 243.006 145.39 248.528 154.953L183.928 154.951C174.652 138.885 170.676 124.541 172 111.918C173.546 99.2946 179.84 89.5408 190.882 82.6559C202.035 75.5798 216.887 72.0421 235.439 72.0428C254.874 72.0435 274.144 75.5825 293.248 82.6598C312.242 89.5457 329.579 99.3005 345.261 111.924C360.942 124.548 373.421 138.892 382.697 154.958C391.311 169.877 395.121 182.978 394.128 194.262C393.355 205.546 389.656 215.396 383.031 223.811C376.627 232.226 366.688 242.554 353.217 254.794L435.375 254.797L464.198 304.716L275.367 304.709L246.544 254.79Z"
                                                                fill="#F0ACB8" />
                                                            <path
                                                                d="M246.544 254.79L246.875 254.79C253.722 247.905 264.046 238.82 277.849 227.537C291.21 216.253 301.259 207.264 307.995 200.57C314.62 193.685 319.147 186.418 321.577 178.768C324.006 171.117 322.846 163.18 318.097 154.956C312.796 145.775 305.342 138.412 295.735 132.865C286.238 127.127 276.189 124.258 265.588 124.257C255.208 124.257 248.416 127.03 245.214 132.576C241.902 137.931 243.006 145.39 248.528 154.953L183.928 154.951C174.652 138.885 170.676 124.541 172 111.918C173.546 99.2946 179.84 89.5408 190.882 82.6559C202.035 75.5798 216.887 72.0421 235.439 72.0428C254.874 72.0435 274.144 75.5825 293.248 82.6598C312.242 89.5457 329.579 99.3005 345.261 111.924C360.942 124.548 373.421 138.892 382.697 154.958C391.311 169.877 395.121 182.978 394.128 194.262C393.355 205.546 389.656 215.396 383.031 223.811C376.627 232.226 366.688 242.554 353.217 254.794L435.375 254.797L464.198 304.716L275.367 304.709L246.544 254.79Z"
                                                                stroke="#1B1B18" stroke-width="1"
                                                                stroke-linejoin="round" />
                                                        </g>
                                                        <g style="mix-blend-mode: hard-light"
                                                            class="transition-all delay-300 translate-y-0 opacity-100 duration-750 starting:opacity-0 starting:translate-y-4">
                                                            <path
                                                                d="M67.41 125.402L44.5515 125.401L15.5625 75.1953L101.364 75.1985L233.886 304.712L170.942 304.71L67.41 125.402Z"
                                                                fill="#F0ACB8" />
                                                            <path
                                                                d="M67.41 125.402L44.5515 125.401L15.5625 75.1953L101.364 75.1985L233.886 304.712L170.942 304.71L67.41 125.402Z"
                                                                stroke="#1B1B18" stroke-width="1" />
                                                        </g>
                                                        </svg>
                                                        <!-- Divider -->
                                                        <hr class="max-w-md mx-auto border-gray-200 mb-8">
                                                        main
 c691b35 (Welcome Page)

                                                </div>
                                            </main>


                                        </div>

                                        <<<<<<< HEAD eaindra @if (Route::has('login'))
                                            <div class="h-14.5 hidden lg:block"></div>
                                            @endif
                        </body>

eaindra

                    </div>
                </main>

winlae

                </div>

 main
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
eaindra
 winlae


eaindra
        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
main


                <div class="flex flex-col sm:flex-row gap-4 justify-start">
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Login
                    </a>
                </div>
921fcf8 (My updates on eaindra branch)

            </div>
        </main>

    </div>

 HEAD
    c691b35 (Welcome Page)

</html>
main

eaindra
<!-- NO INLINE STYLES HERE - everything is in app.css -->

    <!-- NO INLINE STYLES HERE - everything is in app.css -->
 main
main
</body>

</html>
main


 main
</body>
</html>
 eaindra
c691b35 (Welcome Page)

</body>

</html>
 921fcf8 (My updates on eaindra branch)

 main
