@extends('layouts.app')

@section('title', 'Dashboard - MediCore')

@section('content')
    {{-- Wrap everything in Alpine component with sidebarOpen state --}}
    <div x-data="{ sidebarOpen: false }" @keydown.window.escape="sidebarOpen = false" class="flex min-h-screen ml-0 md:ml-60">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Overlay for mobile (click to close sidebar) --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black bg-opacity-30 z-40 md:hidden"
            x-transition:enter="transition-opacity ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;"></div>

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="flex-1 p-8" @click="sidebarOpen = false">
            <!-- HEADER (updated with hamburger at left corner on mobile) -->
            <header class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4 relative">
                <div class="flex items-center gap-3 w-full md:w-auto justify-center md:justify-start">
                    <!-- Hamburger button – visible only on mobile, now absolutely positioned to left corner -->
                    <button @click.stop="sidebarOpen = !sidebarOpen"
                        class="md:hidden text-2xl text-blue-900 focus:outline-none absolute left-0 top-1/2 -translate-y-1/2 z-10">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <h1
                        class="text-center md:text-left text-xl font-bold text-blue-900 w-full md:w-auto md:ml-0 pl-10 md:pl-0">
                        Welcome to MediCore Patient Record System
                    </h1>
                </div>

                <!-- User Profile with Dropdown (unchanged) -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 focus:outline-none">
                            @php
                                $user = auth()->user();
                                $userName = $user->name ?? $user->email;
                                $initials = 'U';
                                if (!empty($userName)) {
                                    $parts = explode(' ', trim($userName));
                                    if (count($parts) >= 2) {
                                        $initials = strtoupper($parts[0][0] . end($parts)[0]);
                                    } else {
                                        $initials = strtoupper(substr($userName, 0, 1));
                                    }
                                }
                            @endphp
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-200 to-blue-400 text-white font-bold shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                                title="{{ $userName }}">
                                {{ $initials }}
                            </div>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 rounded-xl z-50 overflow-hidden"
                            style="display: none;
                               background: rgba(255, 255, 255, 0.15);
                               backdrop-filter: blur(25px) saturate(180%);
                               -webkit-backdrop-filter: blur(25px) saturate(180%);
                               border: 1px solid rgba(255, 255, 255, 0.25);
                               box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);">
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-white/5"></div>
                            <div class="relative z-10">
                                <div class="p-4 border-b border-white/20">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-black text-sm drop-shadow-lg">{{ $userName }}</p>
                                        <p class="text-xs text-black/90 drop-shadow">{{ $user->email }}</p>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center justify-center gap-2 w-full px-4 py-2.5 text-sm font-medium text-red bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-lg border border-red/20 transition-all duration-200 hover:shadow-lg hover:border-white/30">
                                            <i class="fa-solid fa-right-from-bracket"></i>
                                            <span>Log Out</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-3">
                        <span class="font-medium text-gray-900">Guest</span>
                        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-300 text-gray-600 font-bold shadow-md"
                            title="Guest">
                            G
                        </div>
                    </div>
                @endauth
            </header>

            <!-- KPI CARDS (unchanged) -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Total Patients</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $totalPatients ?? 1234 }}</h2>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Total Doctors</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $activeDoctors ?? 45 }}</h2>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Total Appointments</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $appointmentsToday ?? 28 }}</h2>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Total Revenue</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ number_format($monthlyRevenue ?? 170000) }} MMK</h2>
                </div>
            </section>

            <!-- CHARTS & QUICK ACTIONS (unchanged) -->
            <section class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 mb-8">
                <div class="flex-1 bg-white rounded-xl shadow p-4 min-h-[420px]">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Patient Overview</h3>
                    </div>
                    <div class="h-[340px]">
                        <canvas id="patientChart"></canvas>
                    </div>
                </div>

                <!-- Quick Actions (unchanged) -->
                <div class="flex-1 bg-white rounded-xl shadow p-5 min-h-[420px]">
                    <h3 class="text-xl font-semibold mb-4">Quick Actions</h3>
                    <!-- Animation Styles (unchanged) -->
                    <style>
                        .action-button {
                            transition: all 0.3s ease;
                            position: relative;
                            overflow: hidden;
                            background: linear-gradient(145deg, #eff6ff, #dbeafe);
                            background-size: 200% 200%;
                            width: 100%;
                            height: 56px;
                            display: flex;
                            align-items: center;
                            gap: 12px;
                            padding: 0 16px;
                            border-radius: 8px;
                            color: #111827;
                            font-weight: 500;
                            border: none;
                            cursor: pointer;
                        }

                        .action-button:hover {
                            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
                            background: linear-gradient(#7ba6e7);
                            color: blackwhite !important;
                            background-size: 200% 200%;
                        }

                        .action-button::after {
                            content: '';
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            width: 5px;
                            height: 5px;
                            background: rgba(255, 255, 255, 0.5);
                            opacity: 0;
                            border-radius: 100%;
                            transform: scale(1, 1) translate(-50%);
                            transform-origin: 50% 50%;
                        }

                        .action-button:hover::after {
                            animation: ripple 0.6s ease-out;
                        }

                        .action-button::before {
                            content: '';
                            position: absolute;
                            top: -2px;
                            left: -2px;
                            right: -2px;
                            bottom: -2px;
                            background: linear-gradient(45deg, #3b82f6, #22d3ee, #3b82f6, #22d3ee);
                            border-radius: 12px;
                            opacity: 0;
                            transition: opacity 0.3s ease;
                            z-index: -1;
                        }

                        .action-button:hover::before {
                            opacity: 1;
                            animation: glowing 1.5s ease infinite;
                        }

                        .action-button span {
                            transition: all 0.3s ease;
                            position: relative;
                            display: inline-block;
                            font-size: 1rem;
                        }

                        .action-button:hover span {
                            transform: translateX(5px);
                            letter-spacing: 1px;
                        }

                        @keyframes ripple {
                            0% {
                                transform: scale(0, 0);
                                opacity: 0.5;
                            }

                            100% {
                                transform: scale(40, 40);
                                opacity: 0;
                            }
                        }

                        @keyframes glowing {
                            0% {
                                filter: blur(5px);
                                opacity: 0.5;
                            }

                            50% {
                                filter: blur(10px);
                                opacity: 0.8;
                            }

                            100% {
                                filter: blur(5px);
                                opacity: 0.5;
                            }
                        }

                        .action-container {
                            background-color: #f9fafb;
                            border-radius: 8px;
                            padding: 8px;
                            transition: all 0.3s ease;
                        }

                        .action-container:hover {
                            background-color: #f3f4f6;
                        }
                    </style>

                    <div class="flex flex-col gap-3 h-full">
                        <div class="action-container">
                            <button onclick="openAddModal()" class="action-button">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Add Doctor</span>
                            </button>
                        </div>
                        <div class="action-container">
                            <button onclick="openPatientModal()" class="action-button">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Add Patient</span>
                            </button>
                        </div>
                        <div class="action-container">
                            <button onclick="openAppointmentModal()" class="action-button">
                                <i class="fa-solid fa-calendar-plus"></i>
                                <span>Add Appointment</span>
                            </button>
                        </div>
                        <div class="action-container">
                            <button onclick="openAddServiceModal()" class="action-button">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>Add Service</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    @include('doctors.partials.add-modal')
    @include('add-modals.patient-modal')
    @include('add-modals.service-modal')
    @include('add-modals.appointment-modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart initialization and modal functions remain exactly as before
        let patientChart = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const patientStats = {
            labels: {!! json_encode(
                $patientStats['labels'] ?? ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8'],
            ) !!},
            childData: {!! json_encode($patientStats['childData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!},
            adultData: {!! json_encode($patientStats['adultData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!},
            elderlyData: {!! json_encode($patientStats['elderlyData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!}
        };

        async function loadPatientChart(days = 9) {
            try {
                const response = await fetch(`/dashboard/patient-chart?days=${days}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                if (!response.ok) throw new Error('Failed to fetch chart data');
                const data = await response.json();
                if (data.success) renderPatientChart(data.data);
            } catch (error) {
                console.error('Error loading chart:', error);
                renderStaticChart();
            }
        }

        function renderPatientChart(chartData) {
            const ctx = document.getElementById('patientChart').getContext('2d');
            if (patientChart) patientChart.destroy();
            patientChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw} patients`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            title: {
                                display: true,
                                text: 'Number of Patients'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
        }

        function renderStaticChart() {
            const ctx = document.getElementById('patientChart').getContext('2d');
            if (patientChart) patientChart.destroy();
            patientChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: patientStats.labels,
                    datasets: [{
                            label: 'Child (0-17)',
                            data: patientStats.childData,
                            backgroundColor: '#22d3ee'
                        },
                        {
                            label: 'Adult (18-64)',
                            data: patientStats.adultData,
                            backgroundColor: '#3b82f6'
                        },
                        {
                            label: 'Elderly (65+)',
                            data: patientStats.elderlyData,
                            backgroundColor: '#38bdf8'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadPatientChart(8);
            setInterval(() => loadPatientChart(8), 300000);
        });

        function openPatientModal() {
            document.getElementById('patientModal').classList.remove('hidden');
            document.getElementById('patientModal').classList.add('flex');
        }

        function closePatientModal() {
            document.getElementById('patientModal').classList.add('hidden');
            document.getElementById('patientModal').classList.remove('flex');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openAddServiceModal() {
            document.getElementById('addServiceModal').classList.remove('hidden');
            document.getElementById('addServiceModal').classList.add('flex');
        }

        function closeAddServiceModal() {
            document.getElementById('addServiceModal').classList.add('hidden');
            document.getElementById('addServiceModal').classList.remove('flex');
        }

        function openAppointmentModal() {
            document.getElementById('appointmentModal').classList.remove('hidden');
            document.getElementById('appointmentModal').classList.add('flex');
        }

        function closeAppointmentModal() {
            document.getElementById('appointmentModal').classList.add('hidden');
            document.getElementById('appointmentModal').classList.remove('flex');
        }
    </script>
@endpush

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }

        #patientChart,
        #revenueChart {
            width: 100% !important;
            height: 300px !important;
        }
    </style>
@endpush
