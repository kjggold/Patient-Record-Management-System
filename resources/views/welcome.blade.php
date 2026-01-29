<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Clinic</title>

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
                    <span class="text-2xl font-bold text-gray-900">MediCore</span>
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


                <div class="flex flex-col sm:flex-row gap-4 justify-start">
                    <a href="{{ route('login') }}"
                        class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Login
                    </a>
                </div>

            </div>
        </main>

    </div>

</body>

</html>
