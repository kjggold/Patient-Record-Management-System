@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="app flex">
        @include('layouts.sidebar')

        <!-- MAIN -->
        <main>
            <header class="topbar">
                <div>
                    <h1 style="font-size:20px;font-weight:700;color:#1e3a8a;text-align:center;"> Welcome to MediCore Patient
                        Record System </h1>
                </div>
                <input type="text" placeholder="Search patient, doctor...">
                <div class="profile"> <span>{{ Auth::user()->name }}</span>
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>
            </header>

            <!-- CARDS -->
            <section class="cards grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="card p-4 bg-white rounded shadow">
                    <h4>Total Patients</h4>
                    <h2>1,234</h2>
                    <span class="positive text-green-600"><i class="fa-solid fa-arrow-up"></i> 12% from last month</span>
                </div>
                <div class="card p-4 bg-white rounded shadow">
                    <h4>Active Doctors</h4>
                    <h2>45</h2>
                    <span class="positive text-green-600"><i class="fa-solid fa-arrow-up"></i> 3% from last month</span>
                </div>
                <div class="card p-4 bg-white rounded shadow">
                    <h4>Appointments Today</h4>
                    <h2>28</h2>
                    <span class="positive text-green-600"><i class="fa-solid fa-arrow-up"></i> 8% from last month</span>
                </div>
                <div class="card p-4 bg-white rounded shadow">
                    <h4>Revenue (Month)</h4>
                    <h2>$52,450</h2>
                    <span class="positive text-green-600"><i class="fa-solid fa-arrow-up"></i> 18% from last month</span>
                </div>
            </section>

            <!-- CHARTS -->
            <section class="charts grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                <div class="chart-card p-4 bg-white rounded shadow">
                    <h3>Patient Overview</h3>
                    <canvas id="patientChart"></canvas>
                </div>
                <div class="chart-card p-4 bg-white rounded shadow">
                    <h3>Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </section>

            <!-- QUICK ACTIONS -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="box p-4 bg-white rounded shadow">
                    <h3>Today's Appointments</h3>
                    <div class="list-item completed">John Smith - Dr. Sarah Johnson <span>Completed</span></div>
                    <div class="list-item progress">Emma Wilson - Dr. Michael Brown <span>In Progress</span></div>
                    <div class="list-item scheduled">Robert Davis - Dr. Sarah Johnson <span>Scheduled</span></div>
                </div>
                <div class="box actions p-4 bg-white rounded shadow">
                    <h3>Quick Actions</h3>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded mb-2">+ Add Patient</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded mb-2">+ Add Appointment</button>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded">Generate Bill</button>
                </div>
            </section>
        </main>
    </div>

    @push('scripts')
        <script>
            // Charts initialization (same as your previous code)
            const patientCtx = document.getElementById('patientChart').getContext('2d');
            const patientChart = new Chart(patientCtx, {
                type: 'bar',
                data: {
                    labels: ['4 Jul', '5 Jul', '6 Jul', '7 Jul', '8 Jul', '9 Jul', '10 Jul', '11 Jul'],
                    datasets: [{
                        label: 'Child',
                        data: [100, 120, 80, 105, 90, 115, 100, 105],
                        backgroundColor: '#22d3ee'
                    }, {
                        label: 'Adult',
                        data: [120, 110, 100, 132, 115, 140, 130, 132],
                        backgroundColor: '#3b82f6'
                    }, {
                        label: 'Elderly',
                        data: [30, 40, 35, 38, 40, 45, 40, 38],
                        backgroundColor: '#38bdf8'
                    }]
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
            const revenueChart = new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                    datasets: [{
                        label: 'Income',
                        data: [800, 1000, 900, 1495, 1100, 1200, 1150],
                        borderColor: '#0f172a',
                        fill: false
                    }, {
                        label: 'Expense',
                        data: [400, 600, 500, 700, 550, 650, 600],
                        borderColor: '#22d3ee',
                        fill: false
                    }]
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
@endsection
