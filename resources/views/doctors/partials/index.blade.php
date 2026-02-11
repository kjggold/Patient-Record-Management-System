@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            <!-- HEADER -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h1 class="text-2xl font-bold text-gray-800">Doctors List</h1>
                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <input type="text" id="searchDoctors" placeholder="Search by name..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            autocomplete="off">
                        <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 1114 0 7 7 0 01-14 0z"></path>
                        </svg>

                        <!-- Search Results Dropdown -->
                        <div id="searchResults"
                            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                            <!-- Results will be populated here -->
                        </div>
                    </div>

                    <button onclick="openAddModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg transition-colors font-medium whitespace-nowrap flex items-center gap-2">
                        <i class="fa-solid fa-plus"></i>
                        Add Doctor
                    </button>
                </div>
            </div>

            <!-- DOCTOR TABLE -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Specialty</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($doctors as $doctor)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $doctor->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold bg-gradient-to-tr from-blue-500 to-cyan-400 flex-shrink-0">
                                                {{ substr($doctor->full_name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">{{ $doctor->full_name }}</div>
                                                @if ($doctor->email)
                                                    <div class="text-sm text-gray-500">{{ $doctor->email }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $doctor->speciality }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $doctor->phone_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $doctor->status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                            {{ $doctor->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap space-x-3">
                                        <button onclick="openViewModal({{ $doctor->id }})"
                                            class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                                            View
                                        </button>
                                        <a href="{{ route('doctors.edit', $doctor->id) }}"
                                            class="text-amber-600 hover:text-amber-800 hover:underline text-sm font-medium">
                                            Edit
                                        </a>
                                        <form action="{{ route('doctors.destroy', $doctor->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this doctor?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 hover:underline text-sm font-medium">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No doctors found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($doctors->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-white">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <!-- Showing info -->
                            <div class="text-sm text-gray-600">
                                Showing {{ $doctors->firstItem() }} to {{ $doctors->lastItem() }} of
                                {{ $doctors->total() }} doctors
                            </div>

                            <!-- Pagination Links -->
                            <div class="flex items-center gap-1">
                                <!-- Previous Page Link -->
                                @if (!$doctors->onFirstPage())
                                    <a href="{{ $doctors->previousPageUrl() }}"
                                        class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                        <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                                    </a>
                                @endif

                                <!-- Page Numbers -->
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
                                        class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-lg bg-white text-gray-600 hover:bg-gray-100">
                                        1
                                    </a>
                                    @if ($startPage > 2)
                                        <span class="px-2 text-gray-400">...</span>
                                    @endif
                                @endif

                                <!-- Page Numbers -->
                                @for ($i = $startPage; $i <= $endPage; $i++)
                                    <a href="{{ $doctors->url($i) }}"
                                        class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-lg {{ $currentPage == $i ? 'bg-blue-50 text-blue-600 border-blue-300' : 'bg-white text-gray-500 hover:bg-gray-100' }}">
                                        {{ $i }}
                                    </a>
                                @endfor

                                <!-- Last page -->
                                @if ($endPage < $lastPage)
                                    @if ($endPage < $lastPage - 1)
                                        <span class="px-2 text-gray-400">...</span>
                                    @endif
                                    <a href="{{ $doctors->url($lastPage) }}"
                                        class="px-3 py-1.5 text-sm font-medium border border-gray-300 rounded-lg bg-white text-gray-600 hover:bg-gray-100">
                                        {{ $lastPage }}
                                    </a>
                                @endif

                                <!-- Next Page Link -->
                                @if ($doctors->hasMorePages())
                                    <a href="{{ $doctors->nextPageUrl() }}"
                                        class="px-3 py-1.5 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700">
                                        <i class="fa-solid fa-chevron-right w-3 h-3"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ADD DOCTOR MODAL -->
    <div id="addModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Add New Doctor</h2>
            </div>

            <form method="POST" action="{{ route('doctors.store') }}" id="doctorForm" class="overflow-y-auto"
                style="max-height: calc(90vh - 140px)">
                @csrf

                <div class="p-6">
                    <!-- Personal Information -->
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                            <input type="text" name="full_name" placeholder="Enter Full Name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                            <input type="email" name="email" placeholder="Enter Email"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                            <input type="tel" name="phone_number" placeholder="Enter Phone Number"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Professional Information -->
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Professional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Specialty *</label>
                            <input type="text" name="speciality" placeholder="Enter Specialty"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">License Number</label>
                            <input type="text" name="license_number" placeholder="Enter License Number"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience</label>
                            <input type="number" name="years_of_experience" placeholder="Enter Years" min="0"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                                <option value="Active">Active</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <textarea name="address" rows="2" placeholder="Enter Address"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" placeholder="Enter City"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                            <input type="text" name="state" placeholder="Enter State"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t bg-gray-50 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()"
                        class="px-6 py-2.5 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Add Doctor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- VIEW DOCTOR MODAL -->
    <div id="viewModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Doctor Details</h2>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                <div id="doctorDetails" class="grid grid-cols-2 gap-4 text-sm">
                    <!-- Details will be loaded via AJAX -->
                </div>
            </div>
            <div class="p-6 border-t bg-gray-50 flex justify-end">
                <button onclick="closeViewModal()"
                    class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ---------------- MODAL FUNCTIONS ---------------- */
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openViewModal(doctorId) {
            fetch(`/doctors/${doctorId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    const detailsDiv = document.getElementById('doctorDetails');

                    const formatDate = (dateString) => {
                        if (!dateString) return 'N/A';
                        return new Date(dateString).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    };

                    detailsDiv.innerHTML = `
                    <div class="col-span-2"><b>ID:</b> ${data.id || 'N/A'}</div>
                    <div class="col-span-2"><b>Name:</b> ${data.full_name || 'N/A'}</div>
                    <div><b>Email:</b> ${data.email || 'N/A'}</div>
                    <div><b>Phone:</b> ${data.phone_number || 'N/A'}</div>
                    <div><b>Date of Birth:</b> ${formatDate(data.date_of_birth)}</div>
                    <div><b>Specialty:</b> ${data.speciality || 'N/A'}</div>
                    <div><b>License Number:</b> ${data.license_number || 'N/A'}</div>
                    <div><b>Years of Experience:</b> ${data.years_of_experience || '0'}</div>
                    <div><b>Status:</b> <span class="px-2 py-1 rounded-full text-xs ${data.status == 'Active' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'}">${data.status || 'N/A'}</span></div>
                    <div class="col-span-2"><b>Address:</b> ${data.address || 'N/A'}</div>
                    <div><b>City:</b> ${data.city || 'N/A'}</div>
                    <div><b>State:</b> ${data.state || 'N/A'}</div>
                `;

                    document.getElementById('viewModal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                })
                .catch(error => {
                    console.error('Error fetching doctor details:', error);
                    const detailsDiv = document.getElementById('doctorDetails');
                    detailsDiv.innerHTML = `
                    <div class="col-span-2 text-center py-8">
                        <i class="fa-solid fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-red-800 mb-2">Error Loading Doctor Details</h3>
                        <p class="text-red-600">Unable to load doctor information. Please try again.</p>
                    </div>
                `;

                    document.getElementById('viewModal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                });
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        /* ---------------- SEARCH FUNCTIONALITY ---------------- */
        document.getElementById('searchDoctors')?.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const resultsDiv = document.getElementById('searchResults');

            if (searchTerm.length > 1) {
                // Simulate search results
                resultsDiv.innerHTML = `
                <div class="px-4 py-2 text-gray-500 text-sm">Searching for "${searchTerm}"...</div>
            `;
                resultsDiv.classList.remove('hidden');
            } else {
                resultsDiv.classList.add('hidden');
            }
        });

        /* ---------------- EVENT LISTENERS ---------------- */
        document.addEventListener('click', function(e) {
            const addModal = document.getElementById('addModal');
            const viewModal = document.getElementById('viewModal');

            if (addModal && e.target.id === 'addModal') {
                closeAddModal();
            }
            if (viewModal && e.target.id === 'viewModal') {
                closeViewModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeViewModal();
            }
        });

        /* ---------------- FORM VALIDATION ---------------- */
        document.getElementById('doctorForm')?.addEventListener('submit', function(e) {
            const fullName = this.querySelector('input[name="full_name"]').value.trim();
            const phone = this.querySelector('input[name="phone_number"]').value.trim();
            const specialty = this.querySelector('input[name="speciality"]').value.trim();
            const status = this.querySelector('select[name="status"]').value;

            if (!fullName) {
                alert('Please enter full name');
                e.preventDefault();
                return false;
            }

            if (!phone) {
                alert('Please enter phone number');
                e.preventDefault();
                return false;
            }

            if (!specialty) {
                alert('Please enter specialty');
                e.preventDefault();
                return false;
            }

            if (!status) {
                alert('Please select status');
                e.preventDefault();
                return false;
            }

            return true;
        });
    </script>
@endpush
