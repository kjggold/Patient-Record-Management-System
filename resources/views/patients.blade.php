@extends('layouts.app')

@section('title', 'Patients')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Patient Lists</h1>

            <div class="flex flex-col md:flex-row justify-between md:items-center mb-6 gap-4">
                <!-- Search Input -->
                <div class="relative w-full md:w-auto">
                    <input type="text"
                           id="searchInput"
                           placeholder="Search by id, name, age, phone, doctor..."
                           class="w-full md:w-64 border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ request('search') ?? '' }}"
                           autocomplete="off">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1114 0 7 7 0 01-14 0z"></path>
                    </svg>
                </div>

                <!-- Add Patient Button -->
                <button onclick="openAddModal()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    Add Patient
                </button>
            </div>

            <!-- PATIENT TABLE -->
            <div class="bg-white rounded-xl shadow overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="patientsTable">
                        <thead class="bg-sky-50 text-slate-600">
                            <tr>
                                <th class="px-6 py-3">ID</th>
                                <th class="px-6 py-3">Name</th>
                                <th class="px-6 py-3">Age</th>
                                <th class="px-6 py-3">Phone</th>
                                <th class="px-6 py-3">Assigned Doctor</th>
                                <th class="px-6 py-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse ($patients as $p)
                                <tr class="hover:bg-slate-50">
                                    <td class="px-6 py-4">{{ $p->id }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $p->full_name }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $p->age }}</td>
                                    <td class="px-6 py-4 text-gray-700">{{ $p->phone_number }}</td>
                                    <td class="px-6 py-4">
                                        @if ($p->doctor)
                                            <span class="text-gray-900">{{ $p->doctor->full_name }}</span>
                                            @if ($p->doctor->speciality)
                                                <span class="text-xs text-gray-500">({{ $p->doctor->speciality }})</span>
                                            @endif
                                        @else
                                            <span class="text-gray-400 italic">Not Assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center space-x-3">
                                        <button class="text-blue-600 hover:text-blue-800 hover:underline text-sm"
                                                onclick="openViewModal({{ $p->id }})">
                                            View
                                        </button>
                                        <a href="{{ route('patients.edit', $p->id) }}"
                                           class="text-amber-600 hover:text-amber-800 hover:underline text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('patients.destroy', $p->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Are you sure you want to delete this patient?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 hover:underline text-sm">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No patients found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PAGINATION -->
            @if ($patients->hasPages())
                <div class="bg-white rounded-xl shadow px-6 py-4">
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

                                if ($startPage > 1) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }

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
            <div id="viewModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl w-full max-w-lg max-h-[90vh] overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">Patient Details</h2>
                    </div>
                    <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 140px)">
                        <div id="patientDetails" class="grid grid-cols-2 gap-4 text-sm">
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

            <!-- ADD PATIENT MODAL -->
            <div id="addModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
                <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                    <div class="p-6 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">Add New Patient</h2>
                    </div>

                    <form method="POST" action="{{ route('patients.store') }}" id="patientForm"
                          onsubmit="return validateForm()" class="overflow-y-auto" style="max-height: calc(90vh - 140px)">
                        @csrf

                        <div class="p-6">
                            <!-- Patient Information Section -->
                            <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Patient Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="full_name" id="full_name"
                                           placeholder="Enter Full Name"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                                    <input type="number" name="age" id="age"
                                           placeholder="Enter Age"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           min="0" max="120" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth *</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required onchange="calculateAge()">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Sex / Gender *</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="sex_gender" value="male" class="mr-2" checked>
                                            <span>Male</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="sex_gender" value="female" class="mr-2">
                                            <span>Female</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                    <input type="tel" name="phone_number" id="phone_number"
                                           placeholder="Enter Phone Number"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required oninput="formatPhoneNumber(this)">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                                    <input type="text" name="address" id="address"
                                           placeholder="Enter Address"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
                                </div>
                            </div>

                            <!-- Medical History Section -->
                            <h3 class="text-lg font-medium text-gray-700 mb-4 pb-2 border-b">Medical History</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Known Medical Conditions</label>
                                    <input type="text" id="known_medical_conditions"
                                           placeholder="Type condition and press Enter"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <small class="text-gray-500 text-xs mt-1 block">Press Enter to add multiple conditions</small>
                                    <div id="conditions-tags" class="mt-2 flex flex-wrap gap-2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Allergies</label>
                                    <input id="allergies"
                                           placeholder="Type allergy and press Enter"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <small class="text-gray-500 text-xs mt-1 block">Press Enter to add multiple allergies</small>
                                    <div id="allergies-tags" class="mt-2 flex flex-wrap gap-2"></div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Blood Type</label>
                                    <select name="blood_type" id="blood_type"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alcohol Consumption</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="alcohol_consumption" value="none" class="mr-2" checked>
                                            <span>None</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="alcohol_consumption" value="occasional" class="mr-2">
                                            <span>Occasional</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="alcohol_consumption" value="regular" class="mr-2">
                                            <span>Regular</span>
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Doctor *</label>
                                    <select name="assigned_doctor" id="assigned_doctor"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            required>
                                        <option value="" disabled selected>Select Doctor</option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor->full_name }} ({{ $doctor->speciality }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date *</label>
                                    <input type="date" name="registration_date" id="registration_date"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           required>
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
                                Register Patient
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* ---------------- NOTIFICATION FUNCTION ---------------- */
        function showNotification(message, type = 'success') {
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
                            '<i class="fa-solid fa-check-circle"></i>' :
                         type === 'error' ?
                            '<i class="fa-solid fa-exclamation-circle"></i>' :
                            '<i class="fa-solid fa-info-circle"></i>'}
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="this.parentElement.parentElement.remove()" class="inline-flex rounded-md focus:outline-none">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.classList.remove('translate-x-full');
                notification.classList.add('translate-x-0');
            }, 10);

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

        /* ---------------- SEARCH FUNCTIONALITY ---------------- */
        document.getElementById('searchInput')?.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll('#patientsTable tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length < 5) return;

                const id = cells[0].innerText.toLowerCase();
                const name = cells[1].innerText.toLowerCase();
                const age = cells[2].innerText.toLowerCase();
                const phone = cells[3].innerText.toLowerCase();
                const doctor = cells[4].innerText.toLowerCase();

                if (id.includes(value) || name.includes(value) ||
                    age.includes(value) || phone.includes(value) ||
                    doctor.includes(value)) {
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
            let phone = input.value.replace(/\D/g, '');

            if (phone.length > 0) {
                if (phone.length <= 3) {
                    phone = '(' + phone;
                } else if (phone.length <= 6) {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3);
                } else {
                    phone = '(' + phone.substring(0, 3) + ') ' +
                            phone.substring(3, 6) + '-' +
                            phone.substring(6, 10);
                }
            }

            input.value = phone;
        }

        /* ---------------- TAG MANAGEMENT ---------------- */
        let conditions = [];
        let allergies = [];

        function initializeTagInputs() {
            const conditionsInput = document.getElementById('known_medical_conditions');
            const allergiesInput = document.getElementById('allergies');

            if (conditionsInput) {
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

        function updateConditionTags() {
            const container = document.getElementById('conditions-tags');
            if (!container) return;

            container.innerHTML = '';
            conditions.forEach((condition, index) => {
                const tag = document.createElement('div');
                tag.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs flex items-center gap-1';
                tag.innerHTML = `
                    ${condition}
                    <button type="button" onclick="removeCondition(${index})" class="text-blue-600 hover:text-blue-900">
                        <i class="fa-solid fa-times text-xs"></i>
                    </button>
                `;
                container.appendChild(tag);
            });

            // Update hidden input
            document.querySelector('input[name="known_medical_conditions[]"]')?.value = JSON.stringify(conditions);
        }

        function updateAllergyTags() {
            const container = document.getElementById('allergies-tags');
            if (!container) return;

            container.innerHTML = '';
            allergies.forEach((allergy, index) => {
                const tag = document.createElement('div');
                tag.className = 'bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs flex items-center gap-1';
                tag.innerHTML = `
                    ${allergy}
                    <button type="button" onclick="removeAllergy(${index})" class="text-red-600 hover:text-red-900">
                        <i class="fa-solid fa-times text-xs"></i>
                    </button>
                `;
                container.appendChild(tag);
            });

            // Update hidden input
            document.querySelector('input[name="allergies[]"]')?.value = JSON.stringify(allergies);
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

            conditions = [];
            allergies = [];
            updateConditionTags();
            updateAllergyTags();

            document.getElementById('addModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Set today's date for registration
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('registration_date').value = today;

            // Set min/max for date of birth
            document.getElementById('date_of_birth').max = today;
            const minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 120);
            document.getElementById('date_of_birth').min = minDate.toISOString().split('T')[0];

            // Initialize tag inputs
            initializeTagInputs();
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function openViewModal(patientId) {
            fetch(`/patients/${patientId}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    const detailsDiv = document.getElementById('patientDetails');

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
                        <div><b>Age:</b> ${data.age || 'N/A'}</div>
                        <div><b>Gender:</b> ${data.sex_gender ? data.sex_gender.charAt(0).toUpperCase() + data.sex_gender.slice(1) : 'N/A'}</div>
                        <div><b>Date of Birth:</b> ${formatDate(data.date_of_birth)}</div>
                        <div><b>Phone:</b> ${data.phone_number || 'N/A'}</div>
                        <div class="col-span-2"><b>Address:</b> ${data.address || 'N/A'}</div>
                        <div class="col-span-2"><b>Known Medical Conditions:</b> ${data.known_medical_conditions || 'None'}</div>
                        <div class="col-span-2"><b>Allergies:</b> ${data.allergies || 'None'}</div>
                        <div><b>Blood Type:</b> ${data.blood_type || 'Unknown'}</div>
                        <div><b>Alcohol Consumption:</b> ${data.alcohol_consumption ? data.alcohol_consumption.charAt(0).toUpperCase() + data.alcohol_consumption.slice(1) : 'None'}</div>
                        <div class="col-span-2"><b>Assigned Doctor:</b> ${data.doctor ? data.doctor.full_name + (data.doctor.speciality ? ' (' + data.doctor.speciality + ')' : '') : 'Not Assigned'}</div>
                        <div class="col-span-2"><b>Registration Date:</b> ${formatDate(data.registration_date)}</div>
                    `;

                    document.getElementById('viewModal').classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                })
                .catch(error => {
                    console.error('Error fetching patient details:', error);
                    const detailsDiv = document.getElementById('patientDetails');
                    detailsDiv.innerHTML = `
                        <div class="col-span-2 text-center py-8">
                            <i class="fa-solid fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-red-800 mb-2">Error Loading Patient Details</h3>
                            <p class="text-red-600">Unable to load patient information. Please try again.</p>
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

        document.addEventListener('DOMContentLoaded', function() {
            initializeTagInputs();

            const searchInput = document.getElementById('searchInput');
            if (searchInput && searchInput.value) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }
        });
    </script>
@endpush