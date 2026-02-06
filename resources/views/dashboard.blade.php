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
                            if(!empty($userName)) {
                                $parts = explode(' ', trim($userName));
                                if(count($parts) >= 2) {
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
                {{-- <div class="flex-1 bg-white rounded-xl shadow p-4">
                    <h3 class="font-semibold mb-2">Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div> --}}
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
