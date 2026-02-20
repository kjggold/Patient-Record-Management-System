@extends('layouts.app')

@section('title', 'Dashboard - MediCore')

@section('content')
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="flex-1 p-8 ml-60">
            <!-- HEADER -->
            <header class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-center md:text-left text-xl font-bold text-blue-900 w-full md:w-auto">
                    Welcome to MediCore Patient Record System
                </h1>



                <!-- User Profile with Dropdown -->
                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false"
                            class="flex items-center gap-3 focus:outline-none">
                            @php
                                $user = auth()->user();
                                $userName = $user->name ?? $user->email;

                                // Generate initials
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

                            <!-- User Avatar -->
                            <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gradient-to-br from-blue-200 to-blue-400 text-white font-bold shadow-lg hover:scale-105 transition-all duration-200 cursor-pointer"
                                title="{{ $userName }}">
                                {{ $initials }}
                            </div>
                        </button>

                        <!-- Dropdown Menu with See-Through Glass Effect -->
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

                            <!-- Frosted glass overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-transparent to-white/5"></div>

                            <div class="relative z-10">
                                <!-- User Info Section with transparent background -->
                                <div class="p-4 border-b border-white/20">
                                    <div class="space-y-1">
                                        <p class="font-semibold text-black text-sm drop-shadow-lg">{{ $userName }}</p>
                                        <p class="text-xs text-black/90 drop-shadow">{{ $user->email }}</p>
                                    </div>
                                </div>

                                <!-- Logout Button with glass effect -->
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

            <!-- KPI CARDS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Total Patients</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $totalPatients ?? 1234 }}</h2>
                    <span class="text-green-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-arrow-up"></i> 12% from last month
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Active Doctors</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $activeDoctors ?? 45 }}</h2>
                    <span class="text-green-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-arrow-up"></i> 3% from last month
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Appointments Today</h4>
                    <h2 class="text-2xl font-bold mt-2">{{ $appointmentsToday ?? 28 }}</h2>
                    <span class="text-green-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-arrow-up"></i> 8% from last month
                    </span>
                </div>
                <div class="bg-white rounded-xl shadow p-5">
                    <h4 class="text-gray-500">Revenue (This Month)</h4>
                    <h2 class="text-2xl font-bold mt-2">
                        {{ number_format($monthlyRevenue ?? 0) }} MMK
                    </h2>
                </div>

            </section>

            <!-- CHARTS -->
            <section class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 mb-8">
                <div class="flex-1 bg-white rounded-xl shadow p-4 min-h-[420px]">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Patient Overview</h3>
                    </div>
                    <div class="h-[340px]"> <!-- Adjust height as needed -->
                        <canvas id="patientChart"></canvas>
                    </div>
                </div>
                <!-- Quick Actions (replacing Revenue graph) -->
                <div class="flex-1 bg-white rounded-xl shadow p-5 min-h-[420px]">

                    <h3 class="text-xl font-semibold mb-4">Quick Actions</h3>

                    <!-- Same structure as bottom -->
                    <div class="flex flex-col gap-3 h-full">

                        <div class="rounded-lg bg-gray-50 p-2">
                            <button onclick="openAddModal()"
                                class="w-full h-14 flex items-center gap-3 px-4 rounded-lg
                                    bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                                <i class="fa-solid fa-user-plus"></i>
                                Add Doctor
                            </button>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-2">
                            <button onclick="openPatientModal()"
                                class="w-full h-14 flex items-center gap-3 px-4 rounded-lg
                                    bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                                <i class="fa-solid fa-user-plus"></i>
                                Add Patient
                            </button>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-2">
                            <button onclick="openAppointmentModal()"
                                class="w-full h-14 flex items-center gap-3 px-4 rounded-lg
                                    bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                                <i class="fa-solid fa-calendar-plus"></i>
                                Add Appointment
                            </button>
                        </div>

                        <div class="rounded-lg bg-gray-50 p-2">
                            <button onclick="openAddServiceModal()"
                                class="w-full h-14 flex items-center gap-3 px-4 rounded-lg
                                    bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                Add Service
                            </button>
                        </div>

                    </div>
                </div>

            </section>
        </main>
    </div>

    @include('add-modals.doctor-modal')
    @include('add-modals.patient-modal')
    @include('add-modals.service-modal')
    @include('add-modals.appointment-modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let patientChart = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Pass PHP data to JavaScript
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

                if (!response.ok) {
                    throw new Error('Failed to fetch chart data');
                }

                const data = await response.json();

                if (data.success) {
                    renderPatientChart(data.data);
                }
            } catch (error) {
                console.error('Error loading chart:', error);
                // Fallback to static data
                renderStaticChart();
            }
        }

        function renderPatientChart(chartData) {
            const ctx = document.getElementById('patientChart').getContext('2d');

            if (patientChart) {
                patientChart.destroy();
            }

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

            if (patientChart) {
                patientChart.destroy();
            }

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


        // Initialize patient chart on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Always load 7-day chart
            loadPatientChart(8);

            // Optional: auto-refresh every 5 minutes
            setInterval(() => {
                loadPatientChart(8);
            }, 300000);
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