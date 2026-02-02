<aside class="sidebar">
    <h2 class="logo">MediCore</h2>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>

        @if (Route::has('patients.index'))
            <a href="{{ route('patients.index') }}" class="{{ request()->routeIs('patients.*') ? 'active' : '' }}">
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
    /* SIDEBAR STYLES */
    .sidebar {
        width: 220px;
        background-color: #1e293b;
        min-height: 100vh;
        color: black;
        padding: 20px;
    }

    .sidebar .logo {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 20px;
    }

    /* Sidebar links */
    .sidebar nav a {
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

    .sidebar nav a:hover,
    .sidebar nav a.active {
        background: rgba(255, 255, 255, 0.1);
    }

    /* Logout color */
    nav .logout {
        color: #ef4444;
    }
</style>
