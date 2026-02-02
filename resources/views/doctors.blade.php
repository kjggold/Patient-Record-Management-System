@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700">Doctor Lists</h1>
            </div>
            <div class="flex justify-end items-center mb-6 gap-3">
                <div class="flex gap-2">
                    <!-- only added id, no UI change -->
                    <input type="text" id="searchInput" placeholder="Search by id,name,speciality..."
                        class="border rounded px-3 py-2 w-64">
                </div>
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Doctor
                </button>
            </div>

            <!-- DOCTOR TABLE -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full" id="doctorsTable">
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
                        @foreach ($doctors as $doctor)
                            <tr>
                                <td class="px-6 py-4">{{ $doctor->id }}</td>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-gradient-to-tr from-blue-500 to-cyan-400">
                                        {{ substr($doctor->full_name, 0, 2) }}
                                    </div>
                                    {{ $doctor->full_name }}
                                </td>
                                <td class="px-6 py-4">{{ $doctor->speciality }}</td>
                                <td class="px-6 py-4">{{ $doctor->phone_number }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="{{ $doctor->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} px-3 py-1 rounded-full text-sm">
                                        {{ $doctor->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button class="text-blue-600 hover:underline">View</button>
                                    <button class="text-amber-600 hover:underline">Edit</button>
                                    <button class="text-red-600 hover:underline">Delete</button>
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
            /* ---------------- SEARCH (ID, NAME, SPECIALTY) ---------------- */

            document.getElementById('searchInput').addEventListener('keyup', function() {

                const value = this.value.toLowerCase();
                const rows = document.querySelectorAll('#doctorsTable tbody tr');

                rows.forEach(row => {

                    // keep your HTML exactly as-is, just read columns by index
                    const id = row.children[0].innerText.toLowerCase();
                    const name = row.children[1].innerText.toLowerCase();
                    const speciality = row.children[2].innerText.toLowerCase();

                    if (
                        id.includes(value) ||
                        name.includes(value) ||
                        speciality.includes(value)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }

                });

            });

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
