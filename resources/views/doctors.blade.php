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
                    <form method="GET" action="{{ route('doctors.index') }}" id="searchForm" class="flex">
                        <input type="text" name="search" id="searchInput"
                            placeholder="Search by id, name, speciality..."
                            class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ request('search') }}" autocomplete="off">
                    </form>
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
                                <td class="px-6 py-4 text-center space-x-2">
                                    <button class="text-amber-600 hover:text-amber-900 bg-amber-50 hover:bg-amber-100 px-3 py-1 rounded-md text-xs font-medium transition"
                                        onclick="window.location.href='{{ route('doctors.edit', $doctor->id) }}'">Edit</a>
                                        <form action="/doctors/{{ $doctor->id }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="text-red-600 hover:text-amredber-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md text-xs font-medium transition">
                                                Delete
                                            </button>
                                        </form>
                                </td>
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
    @endpush

    @push('scripts')
        <script>
            /* ---------------- SEARCH (ID, NAME, SPECIALTY) ---------------- */

            let searchTimeout;

            document.getElementById('searchInput').addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                const value = this.value;

                searchTimeout = setTimeout(() => {
                    if (value.length > 0) {
                        // Submit the form to trigger server-side search with pagination
                        document.getElementById('searchForm').submit();
                    } else if (value === '') {
                        // If search is empty, go to the index page without search
                        window.location.href = '{{ route('doctors.index') }}';
                    }
                }, 500); // Wait 500ms after user stops typing
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
