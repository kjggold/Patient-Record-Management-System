@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">MediCore</h2>
        <nav>
            <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            <a href="{{ route('patients.index') }}" ><i class="fa-solid fa-user"></i> Patients</a>
            <a href="{{ route('doctors.index') }}" class="{{ request()->is('doctors') ? 'active' : '' }}">
                <i class="fa-solid fa-user-doctor"></i> Doctors
            </a>
            <a><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a href="{{ route('services.index') }}"><i class="fa-solid fa-stethoscope"></i> Services</a>
            <a><i class="fa-solid fa-credit-card"></i> Payments</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <a href="{{ route('logout') }}" class="logout"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </form>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">Doctors Lists</h1>
            <div class="flex gap-3">
                 <!-- Search Input with Dropdown -->
                 <div class="relative w-64">
                    <input type="text"
                           id="searchDoctors"
                           placeholder="Search by name..."
                           class="border rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                           autocomplete="off">

                    <!-- Search Results Dropdown -->
                    <div id="searchResults" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                        <!-- Results will be populated here -->
                    </div>
                </div>
                <button onclick="openAddModal()" class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700">
                    + Add Doctors
                </button>
            </div>
        </div>

        <!-- DOCTOR TABLE -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-blue-50 text-left">
                    <tr>
                        <th class="px-6 py-3">ID</th>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Specialty</th>
                        <th class="px-6 py-3">Phone</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach($doctors as $doctor)
                    <tr>
                        <td class="px-6 py-4">{{ $doctor->id }}</td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-gradient-to-tr from-blue-500 to-cyan-400">
                                {{ substr($doctor->full_name, 0, 2) }}
                            </div>
                            {{ $doctor->full_name }}
                        </td>
                        <td class="px-6 py-4">{{ $doctor->speciality }}</td>
                        <td class="px-6 py-4">{{ $doctor->phone_number }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $doctor->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} px-3 py-1 rounded-full text-sm">
                                {{ $doctor->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-2">
                            <button class="text-blue-600 hover:underline">View</button>
                            <a href="{{ route('doctors.edit', $doctor->id) }}" class="text-amber-600 hover:underline">Edit</a>
                            <form
                                action="/doctors/{{ $doctor->id }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this doctor?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button class="bg-red-500 text-white px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</div>

<!-- Include the add doctor modal as a separate component -->
@include('doctors.partials.add-modal')

@push('styles')
<style>
    /* Sidebar styles */
    .sidebar {
        width: 250px;
        background: linear-gradient(to bottom, #1e40af, #3b82f6);
        color: white;
        padding: 20px;
        min-height: 100vh;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
    }

    .sidebar nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 8px;
        color: #dbeafe;
        text-decoration: none;
        transition: all 0.3s;
    }

    .sidebar nav a:hover,
    .sidebar nav a.active {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar nav i {
        width: 20px;
        text-align: center;
    }

    .logout-form {
        margin-top: auto;
    }

    .logout {
        color: #fca5a5 !important;
    }

    .logout:hover {
        background-color: rgba(239, 68, 68, 0.1) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('addModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeAddModal() {
        document.getElementById('addModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('addModal');
        if (modal && e.target.id === 'addModal') {
            closeAddModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
        }
    });
</script>
@endpush
@endsection