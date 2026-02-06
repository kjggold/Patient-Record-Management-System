@extends('layouts.app')

@section('title', 'Dashboard - MediCore')

@section('content')

    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN DASHBOARD CONTENT -->
        <main class="flex-1 p-8">
            <!-- HEADER -->
            <header class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-center md:text-left text-xl font-bold text-blue-900 w-full md:w-auto">
                    Welcome to MediCore Patient Record System
                </h1>
                <input type="text" placeholder="Search patient, doctor..."
                    class="border border-blue-100 rounded-lg p-2 w-full md:w-1/3">
                <div class="flex items-center gap-3">
                    <span class="font-medium text-gray-900">Admin</span>
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full bg-cyan-400 text-white font-bold shadow-md">
                        A
                    </div>
                </div>
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
                    <h4 class="text-gray-500">Revenue (Month)</h4>
                    <h2 class="text-2xl font-bold mt-2">${{ $monthlyRevenue ?? 52450 }}</h2>
                    <span class="text-green-500 flex items-center gap-1 mt-1">
                        <i class="fa-solid fa-arrow-up"></i> 18% from last month
                    </span>
                </div>
            </section>

            <!-- CHARTS -->
            <section class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6 mb-8">
                <div class="flex-1 bg-white rounded-xl shadow p-4 min-h-[420px]">

                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold">Patient Overview</h3>
                    </div>
                    <div class="h-[340px]">  <!-- Adjust height as needed -->
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
                            <button
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

            <!-- GRID: Appointments & Quick Actions -->
            <section class="grid grid-cols-1 lg:grid-cols-[1.5fr_1fr] gap-6">
                <!-- Appointments -->
                <div class="bg-white rounded-xl shadow p-5">
                    <h3 class="font-semibold mb-4">Today's Appointments</h3>
                    @foreach ($appointments ?? [['patient' => 'John Smith', 'doctor' => 'Dr. Sarah Johnson', 'status' => 'completed'], ['patient' => 'Emma Wilson', 'doctor' => 'Dr. Michael Brown', 'status' => 'progress'], ['patient' => 'Robert Davis', 'doctor' => 'Dr. Sarah Johnson', 'status' => 'scheduled']] as $appt)
                        <div
                            class="flex justify-between p-3 rounded-lg mt-2 {{ $appt['status'] == 'completed' ? 'bg-green-50' : ($appt['status'] == 'progress' ? 'bg-blue-50' : 'bg-gray-50') }}">
                            <span>{{ $appt['patient'] }}</span>
                            <small>{{ $appt['doctor'] }}</small>
                            <label>{{ ucfirst($appt['status']) }}</label>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>
    </div>

    @include('add-modals.doctor-modal')
    @include('add-modals.patient-modal')
    @include('add-modals.service-modal')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let patientChart = null;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Pass PHP data to JavaScript
        const patientStats = {
            labels: {!! json_encode($patientStats['labels'] ?? ['Day 1', 'Day 2', 'Day 3', 'Day 4', 'Day 5', 'Day 6', 'Day 7', 'Day 8']) !!},
            childData: {!! json_encode($patientStats['childData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!},
            adultData: {!! json_encode($patientStats['adultData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!},
            elderlyData: {!! json_encode($patientStats['elderlyData'] ?? [0, 0, 0, 0, 0, 0, 0, 0]) !!}
        };

        async function loadPatientChart(days = 7) {
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
                    datasets: [
                        {
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
        document.addEventListener('DOMContentLoaded', function () {
            // Always load 7-day chart
            loadPatientChart(7);

            // Optional: auto-refresh every 5 minutes
            setInterval(() => {
                loadPatientChart(7);
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

        function openAddServiceModal()
        {
            document.getElementById('addServiceModal').classList.remove('hidden');
            document.getElementById('addServiceModal').classList.add('flex');
        }

        function closeAddServiceModal()
        {
            document.getElementById('addServiceModal').classList.add('hidden');
            document.getElementById('addServiceModal').classList.remove('flex');
        }
    </script>

@endpush

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        #patientChart, #revenueChart {
            width: 100% !important;
            height: 300px !important;
        }
    </style>
@endpush