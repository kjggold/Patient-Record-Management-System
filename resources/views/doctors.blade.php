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
                                    <button class="text-amber-600 hover:text-amber-700 hover:underline" onclick="openViewModal({{ $doctor->id }})">View</button>
                                    <button class="text-amber-600 hover:text-amber-700 hover:underline" onclick="window.location.href='{{ route('doctors.edit', $doctor->id) }}'">Edit</button>
                                    <button class="text-red-600 hover:text-red-700 hover:underline" onclick="deleteDoctor({{ $doctor->id }})">Delete</button>
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
                            Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of {{ $doctors->total() }} doctors
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

                                // Always show first page if not in range
                                if ($startPage > 1) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }

                                // Always show last page if not in range
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
                                    <span class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
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

            // Doctor functions
            function openViewModal(doctorId) {
                // Fetch doctor details via AJAX
                fetch(`/doctors/${doctorId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Create and show a view modal for doctor details
                        // You'll need to implement this similar to patient view modal
                        alert('View doctor details for ID: ' + doctorId);
                    })
                    .catch(error => {
                        console.error('Error fetching doctor details:', error);
                        alert('Error loading doctor details');
                    });
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