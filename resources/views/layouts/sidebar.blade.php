<aside class="sidebar">
    <h2 class="logo">MediCore</h2>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>

        {{-- @if (Route::has('patients.index'))
            <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user"></i> Patients re
            </a>
        @else
            <a href="#"><i class="fa-solid fa-user"></i> Patients</a>
        @endif --}}

        @if (Route::has('patientHistory.index'))
        <a href="{{ route('patientHistory.index') }}" class="{{ request()->routeIs('patientHistory.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i> Patients
        </a>
    @else
        <a href="#"><i class="fa-solid fa-user"></i> Patients</a>
    @endif

        @if (Route::has('doctors.index'))
            <a href="{{ route('doctors.index') }}" class="{{ request()->routeIs('doctors.*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-doctor"></i> Doctors
            </a>
        @else
            <a href="#"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
        @endif

        @if (Route::has('appointments.index'))
            <a href="{{ route('appointments.index') }}"
                class="{{ request()->routeIs('appointments.*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> Appointments
            </a>
        @else
            <a href="#"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
        @endif

        @if (Route::has('discharge.index'))
            <a href="{{ route('discharge.index') }}" class="{{ request()->routeIs('discharge.*') ? 'active' : '' }}">
                <i class="fa-solid fa-house-medical-circle-check"></i> Discharge
            </a>
        @else
            <a href="#"><i class="fa-solid fa-house-medical-circle-check"></i> Discharge</a>
        @endif

        @if (Route::has('services.index'))
            <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                <i class="fa-solid fa-stethoscope"></i> Services
            </a>
        @else
            <a href="#"><i class="fa-solid fa-stethoscope"></i> Services</a>
        @endif



        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="logout-form">
            @csrf
            <a href="{{ route('logout') }}" class="logout"
                onclick="event.preventDefault(); this.closest('form').submit();">
                <i class="fa-solid fa-right-from-bracket">
                </i>
                Logout
            </a>
        </form>
    </nav>
</aside>

<style>
    /* SIDEBAR STYLES - Fixed position */
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 220px;
        height: 100vh;
        background-color: #1e293b;
        color: #f8fafc;
        padding: 15px;
        z-index: 1000;
        overflow-y: auto;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar .logo {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 30px;
        color: #000000;
        text-align: center;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Sidebar links */
    .sidebar nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .sidebar nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        border-radius: 10px;
        font-size: 15px;
        text-decoration: none;
        color: #000000;
        font-weight: 500;
        width: 100%;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: left;
    }

    .sidebar nav a:hover {
        background: rgba(255, 255, 255, 0.1);
        color: #000000;
        transform: translateX(5px);
    }

    .sidebar nav a.active {
        background: rgba(59, 130, 246, 0.2);
        color: #000000;
        border-left: 4px solid #3b82f6;
    }

    .sidebar nav a i {
        width: 20px;
        text-align: center;
        font-size: 16px;
    }

    /* Logout styling */
    nav .logout {
        color: #f87171;
        margin-top: auto;
    }

    nav .logout:hover {
        background: rgba(239, 68, 68, 0.1);
        color: #fca5a5;
    }

    .logout-form {
        margin-top: auto;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    /* Scrollbar styling for sidebar */
    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
</style>