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
            <section class="flex flex-col lg:flex-row gap-6 mb-8">
                <div class="flex-1 bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold mb-2">Patient Overview</h3>
                    <canvas id="patientChart"></canvas>
                </div>
                <div class="flex-1 bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold mb-2">Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </section>

            <!-- GRID: Appointments & Quick Actions -->
            <section class="grid grid-cols-1 lg:grid-cols-[1.3fr_1fr] gap-6">
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

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow p-5 flex flex-col gap-3">
                    <h3 class="font-semibold mb-2">Quick Actions</h3>
                    <button
                        class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                        <i class="fa-solid fa-user-plus"></i> Add Patient
                    </button>
                    <button
                        class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                        <i class="fa-solid fa-calendar-plus"></i> Add Appointment
                    </button>
                    <button
                        class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 hover:bg-blue-400 text-gray-900 font-medium transition">
                        <i class="fa-solid fa-file-invoice-dollar"></i> Generate Bill
                    </button>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const patientCtx = document.getElementById('patientChart').getContext('2d');
        new Chart(patientCtx, {
            type: 'bar',
            data: {
                labels: ['4 Jul', '5 Jul', '6 Jul', '7 Jul', '8 Jul', '9 Jul', '10 Jul', '11 Jul'],
                datasets: [{
                        label: 'Child',
                        data: [100, 120, 80, 105, 90, 115, 100, 105],
                        backgroundColor: '#22d3ee'
                    },
                    {
                        label: 'Adult',
                        data: [120, 110, 100, 132, 115, 140, 130, 132],
                        backgroundColor: '#3b82f6'
                    },
                    {
                        label: 'Elderly',
                        data: [30, 40, 35, 38, 40, 45, 40, 38],
                        backgroundColor: '#38bdf8'
                    },
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

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                        label: 'Income',
                        data: [800, 1000, 900, 1495, 1100, 1200, 1150],
                        borderColor: '#0f172a',
                        fill: false
                    },
                    {
                        label: 'Expense',
                        data: [400, 600, 500, 700, 550, 650, 600],
                        borderColor: '#22d3ee',
                        fill: false
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
    </script>
@endpush