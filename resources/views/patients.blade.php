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
                <div class="flex gap-2">
                    <input type="text" id="searchInput" placeholder="Search by id, name, age, phone, doctor..."
                        class="border rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ request('search') ?? '' }}"
                        autocomplete="off">
                </div>

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
                            <th class="px-4 py-3">Assigned Doctor</th>
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
                                <td class="px-4 py-3">
                                    @if ($p->doctor)
                                        <span class="text-gray-900">{{ $p->doctor->full_name }}</span>
                                        @if ($p->doctor->speciality)
                                            <span class="text-xs text-gray-500">({{ $p->doctor->speciality }})</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400 italic">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button class="text-amber-600 hover:text-amber-700 hover:underline" onclick="openViewModal({{ $p->id }})">View</button>
                                    <button class="text-amber-600 hover:text-amber-700 hover:underline" onclick="openEditModal({{ $p->id }})">Edit</button>
                                    <button class="text-red-600 hover:text-red-700 hover:underline"
                                            onclick="deletePatient({{ $p->id }}, '{{ addslashes($p->full_name) }}')">
                                        Delete
                                    </button>
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
                            Showing {{ $patients->firstItem() }} to {{ $patients->lastItem() }} of {{ $patients->total() }} patients
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
                                    <span class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
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
                        <button onclick="closeViewModal()" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Close</button>
                    </div>
                </div>
            </div>

            <!-- ADD PATIENT MODAL -->
            <div id="addModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 overflow-auto py-8">
                <div class="patient-form-container">
                    <form method="POST" action="{{ route('patients.store') }}" id="patientForm" onsubmit="return validateForm()">
                        @csrf

                        <!-- Patient Information Section -->
                        <h2 class="section-title">Patient Information</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Full Name</label>
                                <input type="text" name="full_name" id="full_name" placeholder="Enter Full Name" required>
                            </div>
                            <div class="form-group">
                                <label class="required">Age</label>
                                <input type="number" name="age" id="age" placeholder="Enter Age" min="0" max="120" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" required onchange="calculateAge()">
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
                                <input type="tel" name="phone_number" id="phone_number" placeholder="Enter Phone Number" required oninput="formatPhoneNumber(this)">
                            </div>
                            <div class="form-group">
                                <label class="required">Address</label>
                                <input type="text" name="address" id="address" placeholder="Enter Address" required>
                            </div>
                        </div>

                        <!-- Medical History Section -->
                        <h2 class="section-title">Medical History</h2>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Known Medical Conditions</label>
                                <input type="text" id="known_medical_conditions" name="known_medical_conditions[]"
                                    placeholder="Type to search medical conditions.">
                                <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple conditions</small>
                                <div id="conditions-tags" class="mt-2 flex flex-wrap gap-2"></div>
                            </div>
                            <div class="form-group">
                                <label>Allergies</label>
                                <input id="allergies" name="allergies[]"
                                    placeholder="Type to search allergies.">
                                <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple allergies</small>
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
                                    <label><input type="radio" name="alcohol_consumption" value="none" checked> None</label>
                                    <label><input type="radio" name="alcohol_consumption" value="occasional"> Occasional</label>
                                    <label><input type="radio" name="alcohol_consumption" value="regular"> Regular</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="required">Assigned Doctor</label>
                                <select name="assigned_doctor" id="assigned_doctor" required>
                                    <option value="" disabled selected>Select Doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->full_name }} ({{ $doctor->speciality }})</option>
                                    @endforeach
                                </select>
                            </div>
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
        /* ---------------- NOTIFICATION FUNCTION ---------------- */

        function showNotification(message, type = 'success') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

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

        /* ---------------- SEARCH FUNCTIONALITY ---------------- */

        document.getElementById('searchInput').addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#patientsTable tbody tr');

            rows.forEach(row => {
                // Get data from each column
                const id = row.children[0].innerText.toLowerCase();
                const name = row.children[1].innerText.toLowerCase();
                const age = row.children[2].innerText.toLowerCase();
                const phone = row.children[3].innerText.toLowerCase();
                const doctor = row.children[4].innerText.toLowerCase();

                // Check if any column contains the search value
                if (
                    id.includes(value) ||
                    name.includes(value) ||
                    age.includes(value) ||
                    phone.includes(value) ||
                    doctor.includes(value)
                ) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        /* ---------------- AGE CALCULATION ---------------- */

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

        /* ---------------- PHONE NUMBER FORMATTING ---------------- */

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

        /* ---------------- TAG MANAGEMENT FOR CONDITIONS AND ALLERGIES ---------------- */

        let conditions = [];
        let allergies = [];

        function initializeAutocompleters() {
            // Initialize medical conditions autocompleter
            if (typeof Def !== 'undefined' && Def.Autocompleter) {
                const conditionsInput = document.getElementById('known_medical_conditions');
                const allergiesInput = document.getElementById('allergies');

                if (conditionsInput) {
                    const autocompleter = new Def.Autocompleter.Search('known_medical_conditions',
                        'https://clinicaltables.nlm.nih.gov/api/conditions/v3/search');

                    conditionsInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const value = this.value.trim();
                            if (value && !conditions.includes(value)) {
                                conditions.push(value);
                                updateConditionTags();
                                this.value = '';
                            }
                        }
                    });
                }

                if (allergiesInput) {
                    const autocompleter = new Def.Autocompleter.Search('allergies',
                        'https://clinicaltables.nlm.nih.gov/api/rxterms/v3/search');

                    allergiesInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const value = this.value.trim();
                            if (value && !allergies.includes(value)) {
                                allergies.push(value);
                                updateAllergyTags();
                                this.value = '';
                            }
                        }
                    });
                }
            }
        }

        function updateConditionTags() {
            const container = document.getElementById('conditions-tags');
            if (!container) return;

            container.innerHTML = '';
            conditions.forEach((condition, index) => {
                const tag = document.createElement('div');
                tag.className = 'tag';
                tag.innerHTML = `
                    ${condition}
                    <span class="tag-remove" onclick="removeCondition(${index})">&times;</span>
                `;
                container.appendChild(tag);
            });

            // Update hidden input value
            const hiddenInput = document.querySelector('input[name="known_medical_conditions[]"]');
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(conditions);
            }
        }

        function updateAllergyTags() {
            const container = document.getElementById('allergies-tags');
            if (!container) return;

            container.innerHTML = '';
            allergies.forEach((allergy, index) => {
                const tag = document.createElement('div');
                tag.className = 'tag';
                tag.innerHTML = `
                    ${allergy}
                    <span class="tag-remove" onclick="removeAllergy(${index})">&times;</span>
                `;
                container.appendChild(tag);
            });

            // Update hidden input value
            const hiddenInput = document.querySelector('input[name="allergies[]"]');
            if (hiddenInput) {
                hiddenInput.value = JSON.stringify(allergies);
            }
        }

        function removeCondition(index) {
            conditions.splice(index, 1);
            updateConditionTags();
        }

        function removeAllergy(index) {
            allergies.splice(index, 1);
            updateAllergyTags();
        }

        /* ---------------- FORM VALIDATION ---------------- */

        function validateForm() {
            const fullName = document.getElementById('full_name').value.trim();
            const age = document.getElementById('age').value;
            const dob = document.getElementById('date_of_birth').value;
            const phone = document.getElementById('phone_number').value.trim();
            const address = document.getElementById('address').value.trim();
            const doctor = document.getElementById('assigned_doctor').value;
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

            if (!doctor) {
                alert('Please select an assigned doctor');
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

        /* ---------------- MODAL FUNCTIONS ---------------- */

        function openAddModal() {
            const form = document.getElementById('patientForm');
            if (form) form.reset();

            // Reset arrays
            conditions = [];
            allergies = [];
            updateConditionTags();
            updateAllergyTags();

            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
            document.body.classList.add('overflow-hidden');

            initializeAutocompleters();

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
                        <div class="col-span-2"><b>Assigned Doctor:</b> ${data.doctor ? data.doctor.full_name + (data.doctor.speciality ? ' (' + data.doctor.speciality + ')' : '') : 'Not Assigned'}</div>
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

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openEditModal(patientId) {
            // Redirect to edit page or open edit modal
            window.location.href = `/patients/${patientId}/edit`;
        }

        function deletePatient(patientId, patientName) {
            if (confirm(`Are you sure you want to delete patient "${patientName}"? This action cannot be undone.`)) {
                // Show loading state on the delete button
                const deleteBtn = event.target;
                const originalText = deleteBtn.textContent;
                deleteBtn.textContent = 'Deleting...';
                deleteBtn.disabled = true;
                deleteBtn.classList.add('opacity-50');

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
                        showNotification('Patient deleted successfully!', 'success');

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
                                if (remainingRows.length === 0) {
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

                    // Reset delete button
                    deleteBtn.textContent = originalText;
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove('opacity-50');
                });
            }
        }

        // Close modals when clicking outside
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

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddModal();
                closeViewModal();
            }
        });

        // Initial setup
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize autocompleters
            initializeAutocompleters();

            // Focus search input if it has value
            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }
        });
    </script>
@endpush