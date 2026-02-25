@extends('layouts.app')

@section('title', 'Patients')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <h1 class="text-2xl font-semibold text-slate-700 mb-4">Patient Lists</h1>

            <div class="flex justify-end items-center mb-6 gap-3">
                <!-- Search Input -->
                <!-- Search Input - use a form for server-side search -->
                <form method="GET" action="{{ route('patients.index') }}" class="flex gap-2" id="searchForm">
                    <input type="text" name="search" id="searchInput" placeholder="Search by id, name, age, phone..."
                        class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ request('search') }}" autocomplete="off">
                </form>

                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Patient
                </button>
            </div>

            <!-- PATIENT TABLE -->
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="w-full text-sm text-left" id="patientsTable">
                    <thead class="bg-sky-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Name</th>
                            <th class="px-4 py-3">Age</th>
                            <th class="px-4 py-3">Phone</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($patients as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $p->id }}</td>
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $p->full_name }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $p->age }}</td>
                                <td class="px-4 py-3 text-gray-700">{{ $p->phone_number }}</td>

                                <td class="px-4 py-3 text-center space-x-2">
                                    <button onclick="window.location.href='{{ route('patients.edit', $p->id) }}'"
                                        class="text-amber-600 hover:underline">
                                        Edit
                                        </a>
                                        <form action="/patients/{{ $p->id }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this patient?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="text-red-600 hover:underline">
                                                Delete
                                            </button>
                                        </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if ($patients->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Showing info -->
                        <div class="text-sm text-gray-600">
                            Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }}
                            patients
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-1">
                            <!-- Previous Page Link -->
                            @if ($patients->onFirstPage())
                                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                                </span>
                            @else
                                <a href="{{ $patients->previousPageUrl() }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    <i class="fa-solid fa-chevron-left w-3 h-3"></i>
                                </a>
                            @endif

                            <!-- Dynamic Page Numbers -->
                            @php
                                $currentPage = $patients->currentPage();
                                $lastPage = $patients->lastPage();
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
                                <a href="{{ $patients->url(1) }}"
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
                                    <a href="{{ $patients->url($page) }}"
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
                                <a href="{{ $patients->url($lastPage) }}"
                                    class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    {{ $lastPage }}
                                </a>
                            @endif

                            <!-- Next Page Link -->
                            @if ($patients->hasMorePages())
                                <a href="{{ $patients->nextPageUrl() }}"
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

            <!-- VIEW PATIENT MODAL -->
            <div id="viewModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
                <div class="bg-white rounded-xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
                    <h2 class="text-xl font-semibold mb-4 text-slate-700">Patient Details</h2>
                    <div id="patientDetails" class="grid grid-cols-2 gap-4 text-sm">
                        <!-- Details will be loaded via AJAX -->
                    </div>
                    <div class="text-right mt-6">
                        <button onclick="closeViewModal()"
                            class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Close</button>
                    </div>
                </div>
            </div>

            <!-- ADD PATIENT MODAL -->
            <div id="addModal"
                class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 overflow-auto py-8">
                <div class="patient-form-container">
                    <form method="POST" action="{{ route('patients.store') }}" id="patientForm"
                        onsubmit="return validateForm()">
                        @csrf

                        <!-- Patient Information Section -->
                        <h2 class="section-title">Patient Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Full Name</label>
                                <input type="text" name="full_name" id="full_name" placeholder="Enter Full Name"
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="required">Age</label>
                                <input type="number" name="age" id="age" placeholder="Enter Age" min="0"
                                    max="120" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" required
                                    onchange="calculateAge()">
                            </div>
                            <div class="form-group">
                                <label class="required">Sex / Gender</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="sex_gender" value="male" checked> Male</label>
                                    <label><input type="radio" name="sex_gender" value="female"> Female</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Phone Number</label>
                                <input type="tel" name="phone_number" id="phone_number"
                                    placeholder="Enter Phone Number" required oninput="formatPhoneNumber(this)">
                            </div>
                            <div class="form-group">
                                <label class="required">Address</label>
                                <input type="text" name="address" id="address" placeholder="Enter Address"
                                    required>
                            </div>
                        </div>

                        <!-- Medical History Section -->
                        <h2 class="section-title">Medical History</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Known Medical Conditions</label>
                                <input type="text" id="known_medical_conditions" name="known_medical_conditions"
                                    placeholder="Type to search medical conditions.">
                                <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple
                                    conditions</small>
                                <div id="conditions-tags" class="mt-2 flex flex-wrap gap-2"></div>
                            </div>
                            <div class="form-group">
                                <label>Allergies</label>
                                <input id="allergies" name="allergies" placeholder="Type to search allergies.">
                                <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple
                                    allergies</small>
                                <div id="allergies-tags" class="mt-2 flex flex-wrap gap-2"></div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Blood Type</label>
                                <select name="blood_type" id="blood_type">
                                    <option value="" selected>Select</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="unknown">Unknown</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Alcohol Consumption</label>
                                <div class="radio-group">
                                    <label><input type="radio" name="alcohol_consumption" value="none" checked>
                                        None</label>
                                    <label><input type="radio" name="alcohol_consumption" value="occasional">
                                        Occasional</label>
                                    <label><input type="radio" name="alcohol_consumption" value="regular">
                                        Regular</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Registration Date</label>
                                <input type="date" name="registration_date" id="registration_date" required>
                            </div>
                        </div>

                        <div class="button-container">
                            <button type="button" onclick="closeAddModal()" class="cancel-btn">Cancel</button>
                            <button type="submit" class="register-btn">Register Patient</button>
                        </div>
                    </form>
                </div>
            </div>

            <style>
                /* === MODAL & FORM === */
                .patient-form-container {
                    background-color: #f6fcff;
                    width: 700px;
                    max-width: 95%;
                    padding: 20px 25px;
                    border-radius: 12px;
                    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
                }

                .section-title {
                    color: #1f3b57;
                    font-size: 16px;
                    margin: 12px 0 8px 0;
                    padding-bottom: 4px;
                    border-bottom: 1px solid #e0f0ff;
                    font-weight: 600;
                }

                .form-row {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 15px;
                    margin-bottom: 12px;
                }

                .form-group {
                    flex: 1;
                    min-width: 220px;
                }

                .form-group label {
                    display: block;
                    font-weight: 600;
                    color: #1f3b57;
                    margin-bottom: 4px;
                    font-size: 13px;
                }

                .form-group label.required::after {
                    content: " *";
                    color: #ef4444;
                }

                .form-group input,
                .form-group select,
                .form-group textarea {
                    width: 100%;
                    padding: 6px 10px;
                    border-radius: 8px;
                    border: 1px solid #c8e1f3;
                    font-size: 13px;
                    background-color: #fff;
                }

                .form-group input:focus,
                .form-group select:focus,
                .form-group textarea:focus {
                    border-color: #4a90e2;
                    box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
                    outline: none;
                }

                .radio-group {
                    display: flex;
                    gap: 15px;
                    margin-top: 2px;
                }

                .radio-group label {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    font-weight: normal;
                    color: #4b5563;
                    font-size: 13px;
                }

                .radio-group input[type="radio"] {
                    width: 14px;
                    height: 14px;
                }

                .button-container {
                    display: flex;
                    gap: 12px;
                    margin-top: 20px;
                }

                .register-btn {
                    flex: 1;
                    border: none;
                    padding: 8px 12px;
                    font-size: 13px;
                    border-radius: 8px;
                    font-weight: 600;
                    cursor: pointer;
                    background: linear-gradient(to right, #3b82f6, #2563eb);
                    color: #fff;
                    transition: all 0.2s;
                }

                .register-btn:hover {
                    background: linear-gradient(to right, #2563eb, #1d4ed8);
                    transform: translateY(-1px);
                    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
                }

                .cancel-btn {
                    flex: 1;
                    border: none;
                    padding: 8px 12px;
                    font-size: 13px;
                    border-radius: 8px;
                    font-weight: 600;
                    background-color: #f1f5f9;
                    color: #64748b;
                    border: 1px solid #cbd5e1;
                    transition: all 0.2s;
                }

                .cancel-btn:hover {
                    background-color: #e2e8f0;
                    transform: translateY(-1px);
                }

                /* Small text helper */
                .text-gray-500.text-xs {
                    font-size: 11px;
                    margin-top: 2px;
                }

                /* Tag styling for conditions and allergies */
                .tag {
                    background-color: #e0f2fe;
                    color: #0369a1;
                    padding: 2px 8px;
                    border-radius: 12px;
                    font-size: 12px;
                    display: inline-flex;
                    align-items: center;
                    gap: 4px;
                }

                .tag-remove {
                    cursor: pointer;
                    font-size: 14px;
                    line-height: 1;
                }

                .tag-remove:hover {
                    color: #dc2626;
                }

                /* Modal adjustments */
                #addModal {
                    align-items: flex-start;
                    padding-top: 40px;
                }

                /* Animation styles */
                @keyframes slideOut {
                    from {
                        opacity: 1;
                        transform: translateX(0);
                    }

                    to {
                        opacity: 0;
                        transform: translateX(-20px);
                    }
                }

                .slide-out {
                    animation: slideOut 0.3s ease forwards;
                }

                /* Notification styles */
                .transition-all {
                    transition: all 0.3s ease;
                }

                .transform {
                    transform: translateX(100%);
                }

                .translate-x-0 {
                    transform: translateX(0);
                }

                .translate-x-full {
                    transform: translateX(100%);
                }

                @media (max-width: 768px) {
                    .patient-form-container {
                        width: 95%;
                        padding: 15px 20px;
                    }

                    .form-group {
                        min-width: 100%;
                    }
                }
            </style>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        /* ---------------- NOTIFICATION FUNCTION (FROM NEW) ---------------- */

        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

            if (type === 'success') {
                notification.className += ' bg-green-100 border-l-4 border-green-500 text-green-700';
            } else if (type === 'error') {
                notification.className += ' bg-red-100 border-l-4 border-red-500 text-red-700';
            } else if (type === 'info') {
                notification.className += ' bg-blue-100 border-l-4 border-blue-500 text-blue-700';
            }

            notification.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        ${type === 'success' ?
                            '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>' :
                         type === 'error' ?
                            '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>' :
                            '<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.remove()" class="inline-flex rounded-md focus:outline-none">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            // Add to document
            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.remove();
                        }
                    }, 300);
                }
            }, 5000);
        }

        // Function to update pagination count after delete
        function updatePaginationCount() {
            const showingInfo = document.querySelector('.text-sm.text-gray-600');
            if (showingInfo) {
                const currentText = showingInfo.textContent;
                const match = currentText.match(/Showing (\d+) to (\d+) of (\d+) patients/);
                if (match) {
                    const start = parseInt(match[1]);
                    const end = parseInt(match[2]);
                    const total = parseInt(match[3]) - 1;

                    if (total > 0) {
                        const newStart = start > 1 ? start - 1 : start;
                        const newEnd = end > start ? end - 1 : end;
                        showingInfo.textContent = `Showing ${newStart} to ${newEnd} of ${total} patients`;
                    }
                }
            }
        }

        /* ---------------- SEARCH FUNCTIONALITY (FROM NEW) ---------------- */

        document.getElementById('searchInput').addEventListener('keyup', function(e) {
            if (this.value.length > 0) {
                // Submit the form to trigger server-side search
                document.getElementById('searchForm').submit();
            } else if (this.value === '') {
                // If search is empty, go to the index page without search
                window.location.href = '{{ route('patients.index') }}';
            }
        });

        // Add debounce to avoid too many requests
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            const value = this.value;

            searchTimeout = setTimeout(() => {
                if (value.length > 0) {
                    document.getElementById('searchForm').submit();
                } else if (value === '') {
                    window.location.href = '{{ route('patients.index') }}';
                }
            }, 500); // Wait 500ms after user stops typing
        });

        /* ---------------- AGE CALCULATION (FROM OLD) ---------------- */

        function calculateAge() {
            const dobInput = document.getElementById('date_of_birth');
            const ageInput = document.getElementById('age');

            if (dobInput.value) {
                const dob = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }

                ageInput.value = age;
            }
        }

        /* ---------------- PHONE NUMBER FORMATTING (FROM OLD) ---------------- */

        function formatPhoneNumber(input) {
            // Remove all non-digit characters
            let phone = input.value.replace(/\D/g, '');

            // Format as (XXX) XXX-XXXX
            if (phone.length > 0) {
                if (phone.length <= 3) {
                    phone = '(' + phone;
                } else if (phone.length <= 6) {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3);
                } else {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3, 6) + '-' + phone.substring(6, 10);
                }
            }

            input.value = phone;
        }

        /* ---------------- DELETE PATIENT FUNCTION (FROM NEW) ---------------- */

        function deletePatient(patientId, patientName) {
            if (confirm(`Are you sure you want to delete patient "${patientName}"? This action cannot be undone.`)) {
                // Send delete request
                fetch(`/patients/${patientId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`Delete failed with status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Show success message
                            showNotification(data.message || 'Patient deleted successfully!', 'success');

                            // Remove the table row with animation
                            const row = event.target.closest('tr');
                            if (row) {
                                row.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';

                                setTimeout(() => {
                                    row.remove();

                                    // Check if table is empty
                                    const remainingRows = document.querySelectorAll('#patientsTable tbody tr');
                                    const hasEmptyRow = remainingRows.length === 1 &&
                                        remainingRows[0].querySelector('td[colspan]');

                                    if (remainingRows.length === 0 || hasEmptyRow) {
                                        location.reload(); // Reload to show "No patients found" message
                                    } else {
                                        // Update pagination info if needed
                                        updatePaginationCount();
                                    }
                                }, 300);
                            } else {
                                // If row not found, reload the page
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        } else {
                            throw new Error(data.message || 'Delete failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting patient:', error);
                        showNotification('Error deleting patient. Please try again.', 'error');
                    });
            }
        }

        /* ---------------- FORM VALIDATION (FROM NEW) ---------------- */

        function validateForm() {
            const fullName = document.getElementById('full_name').value.trim();
            const age = document.getElementById('age').value;
            const dob = document.getElementById('date_of_birth').value;
            const phone = document.getElementById('phone_number').value.trim();
            const address = document.getElementById('address').value.trim();
            const regDate = document.getElementById('registration_date').value;

            // Basic validation
            if (!fullName) {
                alert('Please enter full name');
                return false;
            }

            if (!age || age < 0 || age > 120) {
                alert('Please enter a valid age (0-120)');
                return false;
            }

            if (!dob) {
                alert('Please select date of birth');
                return false;
            }

            if (!phone || phone.replace(/\D/g, '').length < 10) {
                alert('Please enter a valid phone number');
                return false;
            }

            if (!address) {
                alert('Please enter address');
                return false;
            }

            if (!regDate) {
                alert('Please select registration date');
                return false;
            }

            // Check if registration date is not in the future
            const today = new Date().toISOString().split('T')[0];
            if (regDate > today) {
                alert('Registration date cannot be in the future');
                return false;
            }

            return true;
        }

        /* ---------------- VIEW MODAL FUNCTION (FROM NEW) ---------------- */

        function openViewModal(patientId) {
            // Fetch patient details via AJAX
            fetch(`/patients/${patientId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const detailsDiv = document.getElementById('patientDetails');

                    // Format the date properly
                    const formatDate = (dateString) => {
                        if (!dateString) return 'N/A';
                        const date = new Date(dateString);
                        return date.toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    };

                    // Format phone number for display
                    const formatPhone = (phone) => {
                        if (!phone) return 'N/A';
                        // Keep the format as is if already formatted
                        return phone;
                    };

                    detailsDiv.innerHTML = `
                        <div class="col-span-2"><b>ID:</b> ${data.id || 'N/A'}</div>
                        <div class="col-span-2"><b>Name:</b> ${data.full_name || 'N/A'}</div>
                        <div><b>Age:</b> ${data.age || 'N/A'}</div>
                        <div><b>Gender:</b> ${data.sex_gender ? data.sex_gender.charAt(0).toUpperCase() + data.sex_gender.slice(1) : 'N/A'}</div>
                        <div><b>Date of Birth:</b> ${formatDate(data.date_of_birth)}</div>
                        <div><b>Phone:</b> ${formatPhone(data.phone_number)}</div>
                        <div class="col-span-2"><b>Address:</b> ${data.address || 'N/A'}</div>
                        <div class="col-span-2"><b>Known Medical Conditions:</b> ${data.known_medical_conditions || 'None'}</div>
                        <div class="col-span-2"><b>Allergies:</b> ${data.allergies || 'None'}</div>
                        <div><b>Blood Type:</b> ${data.blood_type || 'Unknown'}</div>
                        <div><b>Alcohol Consumption:</b> ${data.alcohol_consumption ? data.alcohol_consumption.charAt(0).toUpperCase() + data.alcohol_consumption.slice(1) : 'None'}</div>
                        <div class="col-span-2"><b>Registration Date:</b> ${formatDate(data.registration_date)}</div>
                    `;

                    document.getElementById('viewModal').classList.remove('hidden');
                    document.getElementById('viewModal').classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                })
                .catch(error => {
                    console.error('Error fetching patient details:', error);

                    // Show error in modal instead of alert
                    const detailsDiv = document.getElementById('patientDetails');
                    detailsDiv.innerHTML = `
                        <div class="col-span-2 text-center py-8">
                            <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-red-800 mb-2">Error Loading Patient Details</h3>
                            <p class="text-red-600">Unable to load patient information. Please try again.</p>
                            <p class="text-sm text-gray-500 mt-2">Error: ${error.message}</p>
                        </div>
                    `;

                    document.getElementById('viewModal').classList.remove('hidden');
                    document.getElementById('viewModal').classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                });
        }

        /* ---------------- MODAL FUNCTIONS (FROM OLD - KEEP AS IS) ---------------- */

        function openAddModal() {
            const form = document.getElementById('patientForm');
            if (form) form.reset();

            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
            document.body.classList.add('overflow-hidden');

            // Set today's date as default for registration
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="registration_date"]').value = today;

            // Set min date for date of birth (120 years ago)
            const minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 120);
            document.getElementById('date_of_birth').max = today;
            document.getElementById('date_of_birth').min = minDate.toISOString().split('T')[0];

            // Set max date for registration date to today
            document.getElementById('registration_date').max = today;
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openEditModal(patientId) {
            // Redirect to edit page or open edit modal
            window.location.href = `/patients/${patientId}/edit`;
        }

        // Close modals when clicking outside (FROM OLD)
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

        // Close modals with Escape key (FROM OLD)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeViewModal();
            }
        });

        // Initial setup (FROM OLD)
        document.addEventListener('DOMContentLoaded', function() {
            // Focus search input if it has value
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }
        });
    </script>

    <!-- KEEP THE OLD WORKING JQUERY AUTOCOMPLETE SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <script>
        // OLD WORKING SCRIPT FOR AUTOCOMPLETE (KEEP AS IS)
        $(document).ready(function() {
            let medicalConditions = [];
            let allergies = [];

            // Cache for autocomplete data
            let medicalConditionsCache = null;
            let allergiesCache = null;

            // Load data from JSON files
            function loadMedicalConditions() {
                if (medicalConditionsCache) {
                    return Promise.resolve(medicalConditionsCache);
                }

                return $.ajax({
                    url: '{{ asset('data/medical-conditions.json') }}',
                    dataType: 'json',
                    cache: true
                }).then(function(data) {
                    medicalConditionsCache = data;
                    console.log('Medical conditions loaded:', data.length);
                    return data;
                }).fail(function() {
                    console.warn('Failed to load medical conditions, using fallback');
                    medicalConditionsCache = getFallbackConditions();
                    return medicalConditionsCache;
                });
            }

            function loadAllergies() {
                if (allergiesCache) {
                    return Promise.resolve(allergiesCache);
                }

                return $.ajax({
                    url: '{{ asset('data/allergies.json') }}',
                    dataType: 'json',
                    cache: true
                }).then(function(data) {
                    allergiesCache = data;
                    console.log('Allergies loaded:', data.length);
                    return data;
                }).fail(function() {
                    console.warn('Failed to load allergies, using fallback');
                    allergiesCache = getFallbackAllergies();
                    return allergiesCache;
                });
            }

            // Fallback data in case JSON files fail
            function getFallbackConditions() {
                return [
                    "Hypertension", "Diabetes", "Asthma", "Arthritis", "Migraine",
                    "Anxiety", "Depression", "High Cholesterol", "Heart Disease",
                    "Allergic Rhinitis", "GERD", "Osteoporosis", "COPD"
                ];
            }

            function getFallbackAllergies() {
                return [
                    "Penicillin", "Sulfa Drugs", "NSAIDs", "Aspirin", "Ibuprofen",
                    "Codeine", "Latex", "Pollen", "Dust Mites", "Peanuts"
                ];
            }

            // Initialize Medical Conditions Autocomplete
            $('#known_medical_conditions').autocomplete({
                source: function(request, response) {
                    loadMedicalConditions().then(function(data) {
                        const term = request.term.toLowerCase();
                        const filtered = data.filter(function(item) {
                            return item.toLowerCase().includes(term);
                        });
                        response(filtered.slice(0, 20)); // Limit to 20 results
                    });
                },
                minLength: 1,
                delay: 50,
                select: function(event, ui) {
                    const condition = ui.item.value;
                    if (condition && !medicalConditions.includes(condition)) {
                        medicalConditions.push(condition);
                        updateMedicalConditionsDisplay();
                    }
                    $(this).val('');
                    return false;
                },
                open: function() {
                    $(this).autocomplete('widget').css('z-index', 999999);
                }
            }).on('keypress', function(e) {
                // Add condition on Enter key
                if (e.which == 13) {
                    const condition = $(this).val().trim();
                    if (condition && !medicalConditions.includes(condition)) {
                        medicalConditions.push(condition);
                        updateMedicalConditionsDisplay();
                    }
                    $(this).val('');
                    e.preventDefault();
                    return false;
                }
            });

            // Initialize Allergies Autocomplete
            $('#allergies').autocomplete({
                source: function(request, response) {
                    loadAllergies().then(function(data) {
                        const term = request.term.toLowerCase();
                        const filtered = data.filter(function(item) {
                            return item.toLowerCase().includes(term);
                        });
                        response(filtered.slice(0, 20)); // Limit to 20 results
                    });
                },
                minLength: 1,
                delay: 50,
                select: function(event, ui) {
                    const allergy = ui.item.value;
                    if (allergy && !allergies.includes(allergy)) {
                        allergies.push(allergy);
                        updateAllergiesDisplay();
                    }
                    $(this).val('');
                    return false;
                },
                open: function() {
                    $(this).autocomplete('widget').css('z-index', 999999);
                }
            }).on('keypress', function(e) {
                // Add allergy on Enter key
                if (e.which == 13) {
                    const allergy = $(this).val().trim();
                    if (allergy && !allergies.includes(allergy)) {
                        allergies.push(allergy);
                        updateAllergiesDisplay();
                    }
                    $(this).val('');
                    e.preventDefault();
                    return false;
                }
            });

            // Function to update medical conditions display
            function updateMedicalConditionsDisplay() {
                const container = $('#known_medical_conditions').parent();
                // Remove existing tags container
                container.find('.tags-container').remove();

                if (medicalConditions.length > 0) {
                    // Create tags container
                    const tagsHtml = '<div class="tags-container mt-2">' +
                        medicalConditions.map((condition, index) =>
                            `<span class="condition-tag">${condition} <span class="remove" data-index="${index}">×</span></span>`
                        ).join('') +
                        '</div>';

                    container.append(tagsHtml);

                    // Update hidden input for form submission
                    container.find('input[name="known_medical_conditions_hidden"]').remove();
                    container.append(
                        `<input type="hidden" name="known_medical_conditions_hidden" value="${medicalConditions.join('|')}">`
                        );
                }

                // Add click handlers for remove buttons
                container.on('click', '.condition-tag .remove', function() {
                    const index = $(this).data('index');
                    medicalConditions.splice(index, 1);
                    updateMedicalConditionsDisplay();
                });
            }

            // Function to update allergies display
            function updateAllergiesDisplay() {
                const container = $('#allergies').parent();
                // Remove existing tags container
                container.find('.tags-container').remove();

                if (allergies.length > 0) {
                    // Create tags container
                    const tagsHtml = '<div class="tags-container mt-2">' +
                        allergies.map((allergy, index) =>
                            `<span class="allergy-tag">${allergy} <span class="remove" data-index="${index}">×</span></span>`
                        ).join('') +
                        '</div>';

                    container.append(tagsHtml);

                    // Update hidden input for form submission
                    container.find('input[name="allergies_hidden"]').remove();
                    container.append(
                    `<input type="hidden" name="allergies_hidden" value="${allergies.join('|')}">`);
                }

                // Add click handlers for remove buttons
                container.on('click', '.allergy-tag .remove', function() {
                    const index = $(this).data('index');
                    allergies.splice(index, 1);
                    updateAllergiesDisplay();
                });
            }

            // Form submission handler
            $('#patientForm').on('submit', function(e) {
                // Set the textarea values to the joined arrays
                $('#known_medical_conditions').val(medicalConditions.join(', '));
                $('#allergies').val(allergies.join(', '));
                return true;
            });

            // Load data immediately when modal opens
            $(document).on('click', '[onclick*="openAddModal"]', function() {
                // Preload data for faster response
                loadMedicalConditions();
                loadAllergies();
            });

            // Age and Date of Birth synchronization (FROM OLD)
            function setupAgeDateSync() {
                const ageInput = document.querySelector('input[name="age"]');
                const dobInput = document.querySelector('input[name="date_of_birth"]');

                if (!ageInput || !dobInput) return;

                // When date of birth is selected, calculate age
                dobInput.addEventListener('change', function() {
                    const dob = new Date(this.value);
                    if (this.value && !isNaN(dob.getTime())) {
                        const today = new Date();
                        let age = today.getFullYear() - dob.getFullYear();

                        // Adjust if birthday hasn't occurred yet this year
                        const monthDiff = today.getMonth() - dob.getMonth();
                        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                            age--;
                        }

                        if (age >= 0 && age <= 120) {
                            ageInput.value = age;
                        } else {
                            ageInput.value = '';
                        }
                    } else if (this.value === '') {
                        ageInput.value = '';
                    }
                });

                // Also add a button to calculate age from DOB
                addCalculateAgeButton(ageInput, dobInput);
            }

            // Add a calculate button for manual calculation
            function addCalculateAgeButton(ageInput, dobInput) {
                const dobGroup = dobInput.closest('.form-group');
                if (!dobGroup) return;

                // Check if button already exists
                if (dobGroup.querySelector('.calculate-age-btn')) return;

                const button = document.createElement('button');
                button.type = 'button';
                button.className =
                    'calculate-age-btn mt-2 text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200';
                button.textContent = 'Calculate Age from Date';

                button.addEventListener('click', function() {
                    const dob = new Date(dobInput.value);
                    if (!dobInput.value || isNaN(dob.getTime())) {
                        alert('Please select a valid date of birth first.');
                        return;
                    }

                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();

                    // Adjust if birthday hasn't occurred yet this year
                    const monthDiff = today.getMonth() - dob.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }

                    if (age >= 0 && age <= 120) {
                        ageInput.value = age;
                    } else {
                        alert('Invalid date of birth. Age must be between 0 and 120.');
                    }
                });

                dobGroup.appendChild(button);
            }

            // Initialize when DOM is loaded
            setupAgeDateSync();
        });
    </script>
@endpush
