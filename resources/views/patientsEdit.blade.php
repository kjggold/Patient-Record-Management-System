@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            

            <!-- Info Message Display -->
            @if(session('info'))
                <div class="mb-6 bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded-md" id="infoMessage">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Success Message Display -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md" id="successMessage" role="alert">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <span class="font-medium">{{ session('success') }}</span>
                                <p class="text-sm text-green-600 mt-1">Patient #{{ $patient->id }} has been updated.</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('patients.index') }}"
                               class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition">
                                View All Patients
                            </a>
                            <button onclick="this.parentElement.parentElement.parentElement.remove()"
                                    class="text-green-600 hover:text-green-800">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message Display -->
            @if($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                    <div class="flex">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div>
                            <p class="font-medium">There were some errors with your submission:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- EDIT PATIENT FORM -->
            <div class="bg-white rounded-xl shadow p-6">
                <form method="POST" action="{{ route('patients.update', $patient->id) }}" id="patientForm">
                    @csrf
                    @method('PUT')

                    <!-- Patient Information Section -->
                    <h2 class="section-title">Patient Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Full Name</label>
                            <input type="text" name="full_name" id="full_name"
                                   value="{{ old('full_name', $patient->full_name) }}"
                                   placeholder="Enter Full Name" required>
                            @error('full_name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Age</label>
                            <input type="number" name="age" id="age"
                                   value="{{ old('age', $patient->age) }}"
                                   placeholder="Enter Age" min="0" max="120" required>
                            @error('age')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Date of Birth</label>
                            <!-- Display current date from DB -->
                            <div class="mb-1 text-sm text-gray-600">
                                Current:
                                @if($patient->date_of_birth_year && $patient->date_of_birth_month && $patient->date_of_birth_day)
                                    {{ $patient->date_of_birth_day }}/{{ $patient->date_of_birth_month }}/{{ $patient->date_of_birth_year }}
                                @else
                                    Not set
                                @endif
                            </div>
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                   value="{{ old('date_of_birth',
                                        $patient->date_of_birth_year && $patient->date_of_birth_month && $patient->date_of_birth_day
                                        ? sprintf('%04d-%02d-%02d', $patient->date_of_birth_year, $patient->date_of_birth_month, $patient->date_of_birth_day)
                                        : '') }}"
                                   required>
                            @error('date_of_birth')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Sex / Gender</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="sex_gender" value="male"
                                           {{ old('sex_gender', $patient->sex_gender) == 'male' ? 'checked' : '' }}>
                                    Male
                                </label>
                                <label>
                                    <input type="radio" name="sex_gender" value="female"
                                           {{ old('sex_gender', $patient->sex_gender) == 'female' ? 'checked' : '' }}>
                                    Female
                                </label>
                            </div>
                            @error('sex_gender')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number"
                                   value="{{ old('phone_number', $patient->phone_number) }}"
                                   placeholder="Enter Phone Number" required>
                            @error('phone_number')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Address</label>
                            <input type="text" name="address" id="address"
                                   value="{{ old('address', $patient->address) }}"
                                   placeholder="Enter Address" required>
                            @error('address')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Medical History Section -->
                    <h2 class="section-title">Medical History</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Known Medical Conditions</label>
                            <input type="text" name="known_medical_conditions" id="known_medical_conditions"
                                   value="{{ old('known_medical_conditions', $patient->known_medical_conditions) }}"
                                   placeholder="Type to search medical conditions.">
                            <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple conditions</small>
                            @error('known_medical_conditions')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Allergies</label>
                            <input type="text" name="allergies" id="allergies"
                                   value="{{ old('allergies', $patient->allergies) }}"
                                   placeholder="Type to search allergies.">
                            <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple allergies</small>
                            @error('allergies')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Blood Type</label>
                            <select name="blood_type" id="blood_type" required>
                                <option value="" {{ old('blood_type', $patient->blood_type) == '' ? 'selected' : '' }}>Select</option>
                                <option value="A+" {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+" {{ old('blood_type', $patient->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('blood_type', $patient->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+" {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="unknown" {{ old('blood_type', $patient->blood_type) == 'unknown' ? 'selected' : '' }}>Unknown</option>
                            </select>
                            @error('blood_type')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Alcohol Consumption</label>
                            <div class="radio-group">
                                <label>
                                    <input type="radio" name="alcohol_consumption" value="none"
                                           {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'none' ? 'checked' : '' }}>
                                    None
                                </label>
                                <label>
                                    <input type="radio" name="alcohol_consumption" value="occasional"
                                           {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'occasional' ? 'checked' : '' }}>
                                    Occasional
                                </label>
                                <label>
                                    <input type="radio" name="alcohol_consumption" value="regular"
                                           {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'regular' ? 'checked' : '' }}>
                                    Regular
                                </label>
                            </div>
                            @error('alcohol_consumption')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Assigned Doctor</label>
                            <select name="assigned_doctor" id="assigned_doctor" required>
                                <option value="" disabled {{ !$patient->assigned_doctor ? 'selected' : '' }}>Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}"
                                            {{ old('assigned_doctor', $patient->assigned_doctor) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->full_name }} ({{ $doctor->speciality }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_doctor')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Registration Date</label>
                            <input type="date" name="registration_date" id="registration_date"
                                   value="{{ old('registration_date', $patient->registration_date) }}" required>
                            @error('registration_date')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="button-container">
                        <a href="{{ route('patients.index') }}" class="cancel-btn">Cancel</a>
                        <button type="submit" class="register-btn" id="submitBtn">Update Patient</button>
                    </div>
                </form>
            </div>

            <style>
                /* Reuse the same styling from the add form */
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

                .register-btn:disabled {
                    opacity: 0.6;
                    cursor: not-allowed;
                    background: linear-gradient(to right, #93c5fd, #60a5fa);
                }

                .cancel-btn {
                    flex: 1;
                    border: none;
                    padding: 8px 12px;
                    font-size: 13px;
                    border-radius: 8px;
                    font-weight: 600;
                    text-align: center;
                    background-color: #f1f5f9;
                    color: #64748b;
                    border: 1px solid #cbd5e1;
                    transition: all 0.2s;
                    text-decoration: none;
                }

                .cancel-btn:hover {
                    background-color: #e2e8f0;
                    transform: translateY(-1px);
                }

                @media (max-width: 768px) {
                    .form-group {
                        min-width: 100%;
                    }
                }

                /* Animation for loading */
                @keyframes pulse {
                    0%, 100% {
                        opacity: 1;
                    }
                    50% {
                        opacity: 0.7;
                    }
                }

                .animate-pulse {
                    animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
                }
            </style>
        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Initialize autocompleters for medical conditions and allergies
        function initializeAutocompleters() {
            if (typeof Def !== 'undefined' && Def.Autocompleter) {
                new Def.Autocompleter.Search('known_medical_conditions',
                    'https://clinicaltables.nlm.nih.gov/api/conditions/v3/search');
                new Def.Autocompleter.Search('allergies', 'https://clinicaltables.nlm.nih.gov/api/rxterms/v3/search');
            }
        }

        // Form validation
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

            if (!phone) {
                alert('Please enter phone number');
                return false;
            }

            // Phone validation (simple check)
            const phoneDigits = phone.replace(/\D/g, '');
            if (phoneDigits.length < 10) {
                alert('Please enter a valid phone number (at least 10 digits)');
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
            const todayStr = new Date().toISOString().split('T')[0];
            if (regDate > todayStr) {
                alert('Registration date cannot be in the future');
                return false;
            }

            return true;
        }

        // Track original form data
        let originalFormData = {};

        function captureOriginalFormData() {
            const form = document.getElementById('patientForm');
            const formElements = form.querySelectorAll('input, select, textarea');

            formElements.forEach(element => {
                if (element.name) {
                    if (element.type === 'radio') {
                        const checkedRadio = form.querySelector(`input[name="${element.name}"]:checked`);
                        originalFormData[element.name] = checkedRadio ? checkedRadio.value : '';
                    } else {
                        originalFormData[element.name] = element.value;
                    }
                }
            });
        }

        // Check if form has changes
        function hasFormChanges() {
            const form = document.getElementById('patientForm');
            const formElements = form.querySelectorAll('input, select, textarea');

            for (let element of formElements) {
                if (element.name) {
                    let currentValue;

                    if (element.type === 'radio') {
                        const checkedRadio = form.querySelector(`input[name="${element.name}"]:checked`);
                        currentValue = checkedRadio ? checkedRadio.value : '';
                    } else {
                        currentValue = element.value;
                    }

                    if (currentValue !== originalFormData[element.name]) {
                        return true;
                    }
                }
            }

            return false;
        }

        // Update submit button state
        function updateSubmitButtonState() {
            const submitBtn = document.getElementById('submitBtn');

            if (hasFormChanges()) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Patient';
                submitBtn.classList.remove('opacity-75');
            } else {
                submitBtn.disabled = true;
                submitBtn.textContent = 'No Changes Made';
                submitBtn.classList.add('opacity-75');
            }
        }

        // Auto-calculate age when date of birth changes
        document.getElementById('date_of_birth').addEventListener('change', function() {
            const dob = this.value;
            if (dob) {
                const birthDate = new Date(dob);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                document.getElementById('age').value = age;
                updateSubmitButtonState();
            }
        });

        // Format phone number as user types
        document.getElementById('phone_number').addEventListener('input', function(e) {
            let phone = this.value.replace(/\D/g, '');

            if (phone.length > 0) {
                if (phone.length <= 3) {
                    phone = '(' + phone;
                } else if (phone.length <= 6) {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3);
                } else {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3, 6) + '-' + phone.substring(6, 10);
                }
            }

            this.value = phone;
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeAutocompleters();
            captureOriginalFormData();

            // Set max date for date of birth and registration date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date_of_birth').max = today;
            document.getElementById('registration_date').max = today;

            // Listen for form changes
            const form = document.getElementById('patientForm');
            form.addEventListener('input', updateSubmitButtonState);
            form.addEventListener('change', updateSubmitButtonState);

            // Initial button state
            updateSubmitButtonState();

            // Form submission handling
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function(e) {
                // Prevent submission if no changes
                if (!hasFormChanges()) {
                    e.preventDefault();
                    alert('No changes detected. Please modify at least one field before updating.');
                    return false;
                }

                // Validate form
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                // Show loading state
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Updating...';
                submitBtn.disabled = true;
                submitBtn.classList.add('animate-pulse');

                // Allow form to submit normally
            });

            // Auto-hide success message after 5 seconds
            const successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    successMessage.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        if (successMessage.parentNode) {
                            successMessage.remove();
                        }
                    }, 500);
                }, 5000);
            }

            // Auto-hide info message after 5 seconds
            const infoMessage = document.getElementById('infoMessage');
            if (infoMessage) {
                setTimeout(() => {
                    infoMessage.style.opacity = '0';
                    infoMessage.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => {
                        if (infoMessage.parentNode) {
                            infoMessage.remove();
                        }
                    }, 500);
                }, 5000);
            }

            // Set date limits
            const minDate = new Date();
            minDate.setFullYear(minDate.getFullYear() - 120);
            document.getElementById('date_of_birth').min = minDate.toISOString().split('T')[0];

            // Calculate age on load if date of birth exists
            const dobInput = document.getElementById('date_of_birth');
            if (dobInput.value) {
                const birthDate = new Date(dobInput.value);
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Only update if age field is empty or different
                const currentAge = document.getElementById('age').value;
                if (!currentAge || currentAge != age) {
                    document.getElementById('age').value = age;
                }
            }
        });
    </script>
@endpush