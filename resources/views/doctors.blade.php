@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700 mb-4">Doctor Lists</h1>
            </div>
            <div class="flex justify-end items-center mb-6 gap-3">
                <div class="flex gap-2">
                    <!-- only added id, no UI change -->
                    <input type="text" id="searchInput" placeholder="Search by id, name, speciality..."
                        class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Doctor
                </button>
            </div>

            <!-- DOCTOR TABLE -->
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="w-full text-sm text-left" id="doctorsTable">
                    <thead class="bg-sky-50 text-slate-600">
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
                        @forelse ($doctors as $doctor)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">{{ $doctor->id }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $doctor->full_name }}
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $doctor->speciality }}</td>
                                <td class="px-6 py-4 text-gray-700">{{ $doctor->phone_number }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="{{ $doctor->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }} px-3 py-1 rounded-full text-sm">
                                        {{ $doctor->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center space-x-3">
                                    <button class="text-amber-600 hover:text-amber-700 hover:underline"
                                        onclick="window.location.href='{{ route('doctors.edit', $doctor->id) }}'">Edit</button>
                                    <button class="text-red-600 hover:text-red-700 hover:underline"
                                        onclick="deleteDoctor({{ $doctor->id }})">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">No doctors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if ($doctors->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Showing info -->
                        <div class="text-sm text-gray-600">
                            Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }}
                            doctors
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-1">
                            <!-- Previous Page Link -->
                            @if ($doctors->onFirstPage())
                                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                                </span>
                            @else
                                <a href="{{ $doctors->previousPageUrl() }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                                </a>
                            @endif

                            <!-- Dynamic Page Numbers -->
                            @php
                                $currentPage = $doctors->currentPage();
                                $lastPage = $doctors->lastPage();
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);

                                if ($startPage > 1) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }

                                if ($endPage < $lastPage) {
                                    $startPage = max(1, $endPage - 4);
                                }
                            @endphp

                            <!-- First page -->
                            @if ($startPage > 1)
                                <a href="{{ $doctors->url(1) }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    1
                                </a>
                                @if ($startPage > 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                            @endif

                            <!-- Page Numbers -->
                            @for ($page = $startPage; $page <= $endPage; $page++)
                                @if ($page == $currentPage)
                                    <span
                                        class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $doctors->url($page) }}"
                                        class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            <!-- Last page -->
                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                                <a href="{{ $doctors->url($lastPage) }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    {{ $lastPage }}
                                </a>
                            @endif

                            <!-- Next Page Link -->
                            @if ($doctors->hasMorePages())
                                <a href="{{ $doctors->nextPageUrl() }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    <i class="fa-solid fa-chevron-right w-3 h-3"></i>
                                </a>
                            @else
                                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                    <i class="fa-solid fa-chevron-right w-3 h-3"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- Include the add doctor modal as a separate component -->
    @include('doctors.partials.add-modal')

    @push('styles')
        <style>
            /* Sidebar styles */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 220px;
                height: 100vh;
                background-color: #1e293b;
                color: #f8fafc;
                padding: 20px 15px;
                z-index: 1000;
                overflow-y: auto;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar .logo {
                font-size: 25px;
                font-weight: 700;
                margin-bottom: 30px;
                color: #080808;
                text-align: left;
                padding-left: 5px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .sidebar nav {
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .sidebar nav a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 16px;
                border-radius: 10px;
                font-size: 17px;
                text-decoration: none;
                font-weight: 500;
                width: 100%;
                background: none;
                border: none;
                cursor: pointer;
            }

            .sidebar nav a:hover {
                background: rgba(59, 130, 246, 0.2);
                transform: translateX(5px);
            }

            .sidebar nav a.active {
                background: rgba(133, 173, 236, 0.3);
                font-weight: 500;
            }

            .sidebar nav a i {
                width: 20px;
                text-align: center;
                font-size: 16px;
            }

            nav .logout {
                color: #e93e3e;
                margin-top: 8px;
                padding: 12px 16px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                gap: 12px;
                font-weight: 500;
                transition: all 0.2s ease;
            }

            nav .logout:hover {
                background: rgba(242, 14, 14, 0.2);
                color: #ff1010;
            }

            .logout-form {
                margin-top: 0;
            }

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

            main {
                margin-left: 220px;
                padding: 20px;
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

                    const id = row.children[0].innerText.toLowerCase();
                    const name = row.children[1].innerText.toLowerCase();
                    const speciality = row.children[2].innerText.toLowerCase();
                    const phone = row.children[3].innerText.toLowerCase();
                    const status = row.children[4].innerText.toLowerCase();

                    if (
                        id.includes(value) ||
                        name.includes(value) ||
                        speciality.includes(value) ||
                        phone.includes(value) ||
                        status.includes(value)
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

            function deleteDoctor(doctorId) {
                if (confirm('Are you sure you want to delete this doctor?')) {
                    fetch(`/doctors/${doctorId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => {
                            if (response.ok) {
                                alert('Doctor deleted successfully');
                                location.reload();
                            } else {
                                throw new Error('Delete failed');
                            }
                        })
                        .catch(error => {
                            console.error('Error deleting doctor:', error);
                            alert('Error deleting doctor. Please try again.');
                        });
                }
            }

            document.addEventListener('click', function(e) {
                const modal = document.getElementById('addModal');
                if (modal && e.target.id === 'addModal') {
                    closeAddModal();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeAddModal();
                }
            });
        </script>
    @endpush
@endsection
