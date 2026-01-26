<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Clinic</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Remove the inline body style - it's in app.css already -->
</head>
<!-- REMOVE bg-white from body class -->
<body class="relative">
    <!-- Animated Background -->
    <div class="animated-bg"></div>

    <!-- Twinkling Stars - REMOVE the inline style block at the bottom -->
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>
    <div class="twinkling-star"></div>

    <!-- Main Content -->
    <div class="relative z-10">
        <!-- Header -->
        <header class="w-full px-6 py-4">
            <div class="max-w-6xl mx-auto flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-blue-500 rounded flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="text-xl font-semibold text-gray-900">MediCore</span>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">Features</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">About</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600 font-medium transition-colors duration-200">Contact</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="min-h-[80vh] flex items-center justify-center px-4 py-12">
            <div class="max-w-3xl w-full text-center">
                <!-- Main Heading -->
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    Organized Patient Management
                    <span class="block text-blue-600 mt-2">For Clinics</span>
                </h1>

                <!-- Description -->
                <p class="text-lg md:text-xl text-gray-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Where every patient's journey is carefully documented, securely preserved, and effortlessly accessible—transforming healthcare records into a seamless symphony of precision and care.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-12">
                    <a href="{{ route('login') }}"
                       class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                        Login
                    </a>
                    <button class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg border-2 border-blue-600 hover:bg-blue-50 transition-colors duration-200 shadow-sm">
                        Learn More
                    </button>
                </div>

                <!-- Divider -->
                <hr class="max-w-md mx-auto border-gray-200 mb-8">

                <!-- Navigation Section -->
                <div class="text-center">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Navigation:</h3>
                    <div class="flex flex-col sm:flex-row justify-center gap-4 text-gray-700">
                        <a href="#" class="font-medium hover:text-blue-600 transition-colors duration-200">Features</a>
                        <a href="#" class="font-medium hover:text-blue-600 transition-colors duration-200">About</a>
                        <a href="#" class="font-medium hover:text-blue-600 transition-colors duration-200">Contact</a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- NO INLINE STYLES HERE - everything is in app.css -->
</body>
</html>