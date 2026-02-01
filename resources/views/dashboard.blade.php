@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="app">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2 class="logo">MediCore</h2>
            <nav>
                <a class="active"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                <a href="{{ route('patients.index') }}"><i class="fa-solid fa-user"></i> Patients</a>
                <a href="{{ route('doctors.index') }}" class="{{ request()->is('doctors') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-doctor"></i> Doctors
                </a>
                <a href="{{ route('appointments.index') }}"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
                <a href="{{ route('services.index') }}"><i class="fa-solid fa-stethoscope"></i> Services</a>
                <a><i class="fa-solid fa-credit-card"></i> Payments</a>
                {{-- <a class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a> --}}
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <a href="{{ route('logout') }}" class="logout"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </a>
                </form>

            </nav>
        </aside>

        <!-- MAIN -->
        <main>
            <header class="topbar">
                <div>
                    <h1 style="font-size:20px;font-weight:700;color:#1e3a8a;text-align:center;">
                        Welcome to MediCore Patient Record System
                    </h1>
                </div>
                <!-- removed search bar -->
                <div class="profile">
                    <span>{{ Auth::user()->name }}</span>
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</div>
                </div>
            </header>

            <section class="cards">
                <div class="card">
                    <h4>Total Patients</h4>
                    <h2>1,234</h2>
                    <span class="positive"><i class="fa-solid fa-arrow-up"></i> 12% from last month</span>
                </div>
                <div class="card">
                    <h4>Active Doctors</h4>
                    <h2>45</h2>
                    <span class="positive"><i class="fa-solid fa-arrow-up"></i> 3% from last month</span>
                </div>
                <div class="card">
                    <h4>Appointments Today</h4>
                    <h2>28</h2>
                    <span class="positive"><i class="fa-solid fa-arrow-up"></i> 8% from last month</span>
                </div>
                <div class="card">
                    <h4>Revenue (Month)</h4>
                    <h2>$52,450</h2>
                    <span class="positive"><i class="fa-solid fa-arrow-up"></i> 18% from last month</span>
                </div>
            </section>

            <section class="charts">
                <div class="chart-card">
                    <h3>Patient Overview</h3>
                    <canvas id="patientChart"></canvas>
                </div>
                <div class="chart-card">
                    <h3>Revenue</h3>
                    <canvas id="revenueChart"></canvas>
                </div>
            </section>

            <section class="grid">
                <div class="box">
                    <h3>Today's Appointments</h3>
                    <div class="list-item completed">
                        <span>John Smith</span>
                        <small>Dr. Sarah Johnson</small>
                        <label>Completed</label>
                    </div>
                    <div class="list-item progress">
                        <span>Emma Wilson</span>
                        <small>Dr. Michael Brown</small>
                        <label>In Progress</label>
                    </div>
                    <div class="list-item scheduled">
                        <span>Robert Davis</span>
                        <small>Dr. Sarah Johnson</small>
                        <label>Scheduled</label>
                    </div>
                </div>
                <div class="box actions">
                    <h3>Quick Actions</h3>
                    <button><i class="fa-solid fa-user-plus"></i> Add Patient</button>
                    <button><i class="fa-solid fa-calendar-plus"></i> Add Appointment</button>
                    <button><i class="fa-solid fa-file-invoice-dollar"></i> Generate Bill</button>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('styles')
    <style>
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #1e293b;
            min-height: 100vh;
            color: #fff;
            padding: 20px;
        }

        .sidebar .logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .sidebar nav .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            transition: background 0.3s;
        }

        .sidebar nav .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }


        .sidebar nav form {
            margin: 0;
        }

        .sidebar nav .sidebar-link {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }

        /* Sidebar links */
        nav a,
        nav .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 12px;
            border-radius: 12px;
            font-size: 1rem;
            text-decoration: none;
            color: black;
            font-weight: 500;
            width: 100%;
            background: none;
            border: none;
            cursor: pointer;
            transition: 0.2s;
            text-align: left;
        }

        /* Hover & active */
        nav a:hover,
        nav .nav-link:hover,
        nav a.active {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Logout color */
        nav .logout {
            color: #ef4444;
        }

        /* Remove form spacing */
        .logout-form {
            margin-top: auto;
        }


        /* Topbar */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f1f5f9;
        }

        .topbar input {
            padding: 5px 10px;
            width: 300px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .profile .avatar {
            width: 35px;
            height: 35px;
            background-color: #3b82f6;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const patientCtx = document.getElementById('patientChart').getContext('2d');
        const patientChart = new Chart(patientCtx, {
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
        const revenueChart = new Chart(revenueCtx, {
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