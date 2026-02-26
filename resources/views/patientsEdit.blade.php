@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 ml-60">

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
                <form method="POST" action="{{ route('patient-history.update', $patient->id) }}" id="patientForm">
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
                            <input type="date" name="date_of_birth" id="date_of_birth"
                                   value="{{ old('date_of_birth', $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('Y-m-d') : '') }}"
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
                                           {{ old('sex_gender', strtolower(trim($patient->sex_gender))) == 'male' ? 'checked' : '' }}>
                                    Male
                                </label>
                                <label>
                                    <input type="radio" name="sex_gender" value="female"
                                           {{ old('sex_gender', strtolower(trim($patient->sex_gender))) == 'female' ? 'checked' : '' }}>
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
                            <div class="tags-input-container">
                                <input type="text" id="known_medical_conditions_input" class="tags-input"
                                    placeholder="Type to search medical conditions." autocomplete="off">
                                <div id="medical_conditions_tags" class="tags-container"></div>
                                <input type="hidden" name="known_medical_conditions" id="known_medical_conditions"
                                       value="{{ old('known_medical_conditions', $patient->known_medical_conditions) }}">
                            </div>
                            <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple conditions</small>
                            @error('known_medical_conditions')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Allergies</label>
                            <div class="tags-input-container">
                                <input type="text" id="allergies_input" class="tags-input"
                                    placeholder="Type to search allergies." autocomplete="off">
                                <div id="allergies_tags" class="tags-container"></div>
                                <input type="hidden" name="allergies" id="allergies"
                                       value="{{ old('allergies', $patient->allergies) }}">
                            </div>
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
                                           {{ old('alcohol_consumption', strtolower(trim($patient->alcohol_consumption))) == 'none' ? 'checked' : '' }}>
                                    None
                                </label>
                                <label>
                                    <input type="radio" name="alcohol_consumption" value="occasional"
                                           {{ old('alcohol_consumption', strtolower(trim($patient->alcohol_consumption))) == 'occasional' ? 'checked' : '' }}>
                                    Occasional
                                </label>
                                <label>
                                    <input type="radio" name="alcohol_consumption" value="regular"
                                           {{ old('alcohol_consumption', strtolower(trim($patient->alcohol_consumption))) == 'regular' ? 'checked' : '' }}>
                                    Regular
                                </label>
                            </div>
                            @error('alcohol_consumption')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="button-container">
                        <a href="{{ route('patient-history.index') }}" class="cancel-btn">Cancel</a>
                        <button type="submit" class="register-btn" id="submitBtn">Update Patient</button>
                    </div>
                </form>
            </div>

            <style>
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

                /* Tags input styling */
                .tags-input-container {
                    border: 1px solid #c8e1f3;
                    border-radius: 8px;
                    background-color: #fff;
                    padding: 4px;
                }

                .tags-input {
                    width: 100%;
                    border: none !important;
                    padding: 6px 8px !important;
                    font-size: 13px;
                    outline: none;
                }

                .tags-input:focus {
                    border: none;
                    box-shadow: none;
                }

                .tags-container {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 6px;
                    padding: 4px 8px 8px 8px;
                }

                .condition-tag {
                    background-color: #91defa;
                    color: #104a58;
                    padding: 4px 10px;
                    border-radius: 16px;
                    font-size: 12px;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .allergy-tag {
                    background-color: #fef3c7;
                    color: #92400e;
                    padding: 4px 10px;
                    border-radius: 16px;
                    font-size: 12px;
                    font-weight: 500;
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                }

                .tag-remove {
                    cursor: pointer;
                    font-size: 16px;
                    line-height: 1;
                    color: currentColor;
                    opacity: 0.7;
                    transition: opacity 0.2s;
                }

                .tag-remove:hover {
                    opacity: 1;
                }

                .tags-input-container:focus-within {
                    border-color: #4a90e2;
                    box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
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
            </style>
        </main>
    </div>
@endsection

@push('scripts')
<!-- jQuery and jQuery UI for Autocomplete -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Format phone number on load
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput.value) {
            let phone = phoneInput.value.replace(/\D/g, '');
            if (phone.length > 0) {
                if (phone.length <= 3) {
                    phone = '(' + phone;
                } else if (phone.length <= 6) {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3);
                } else {
                    phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3, 6) + '-' + phone.substring(6, 10);
                }
            }
            phoneInput.value = phone;
        }

        // Format phone number as user types
        phoneInput.addEventListener('input', function(e) {
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
            }
        });
    });

    $(document).ready(function() {
        let medicalConditions = [];
        let allergies = [];

        // Cache for autocomplete data
        let medicalConditionsCache = null;
        let allergiesCache = null;

        // Load existing data from hidden inputs
        function loadExistingData() {
            // Load medical conditions
            const conditionsHidden = $('#known_medical_conditions').val();
            if (conditionsHidden) {
                medicalConditions = conditionsHidden.split(',').map(item => item.trim()).filter(item => item);
                updateMedicalConditionsDisplay();
            }

            // Load allergies
            const allergiesHidden = $('#allergies').val();
            if (allergiesHidden) {
                allergies = allergiesHidden.split(',').map(item => item.trim()).filter(item => item);
                updateAllergiesDisplay();
            }
        }

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
                "Allergic Rhinitis", "GERD", "Osteoporosis", "COPD", "Chronic kidney disease"
            ];
        }

        function getFallbackAllergies() {
            return [
                "Penicillin", "Sulfa Drugs", "NSAIDs", "Aspirin", "Ibuprofen",
                "Codeine", "Latex", "Pollen", "Dust Mites", "Peanuts"
            ];
        }

        // Function to update medical conditions display
        function updateMedicalConditionsDisplay() {
            const container = $('#medical_conditions_tags');
            container.empty();

            if (medicalConditions.length > 0) {
                medicalConditions.forEach((condition, index) => {
                    const tag = $(`
                        <span class="condition-tag">
                            ${condition}
                            <span class="tag-remove" data-index="${index}">×</span>
                        </span>
                    `);
                    container.append(tag);
                });
            }

            // Update hidden input
            $('#known_medical_conditions').val(medicalConditions.join(', '));
        }

        // Function to update allergies display
        function updateAllergiesDisplay() {
            const container = $('#allergies_tags');
            container.empty();

            if (allergies.length > 0) {
                allergies.forEach((allergy, index) => {
                    const tag = $(`
                        <span class="allergy-tag">
                            ${allergy}
                            <span class="tag-remove" data-index="${index}">×</span>
                        </span>
                    `);
                    container.append(tag);
                });
            }

            // Update hidden input
            $('#allergies').val(allergies.join(', '));
        }

        // Initialize Medical Conditions Autocomplete
        $('#known_medical_conditions_input').autocomplete({
            source: function(request, response) {
                loadMedicalConditions().then(function(data) {
                    const term = request.term.toLowerCase();
                    const filtered = data.filter(function(item) {
                        return item.toLowerCase().includes(term);
                    });
                    response(filtered.slice(0, 20));
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
            if (e.which == 13) {
                e.preventDefault();
                const condition = $(this).val().trim();
                if (condition && !medicalConditions.includes(condition)) {
                    medicalConditions.push(condition);
                    updateMedicalConditionsDisplay();
                }
                $(this).val('');
                return false;
            }
        });

        // Initialize Allergies Autocomplete
        $('#allergies_input').autocomplete({
            source: function(request, response) {
                loadAllergies().then(function(data) {
                    const term = request.term.toLowerCase();
                    const filtered = data.filter(function(item) {
                        return item.toLowerCase().includes(term);
                    });
                    response(filtered.slice(0, 20));
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
            if (e.which == 13) {
                e.preventDefault();
                const allergy = $(this).val().trim();
                if (allergy && !allergies.includes(allergy)) {
                    allergies.push(allergy);
                    updateAllergiesDisplay();
                }
                $(this).val('');
                return false;
            }
        });

        // Remove tag handlers (using event delegation)
        $(document).on('click', '.condition-tag .tag-remove', function() {
            const index = $(this).data('index');
            medicalConditions.splice(index, 1);
            updateMedicalConditionsDisplay();
        });

        $(document).on('click', '.allergy-tag .tag-remove', function() {
            const index = $(this).data('index');
            allergies.splice(index, 1);
            updateAllergiesDisplay();
        });

        // Form submission handler
        $('#patientForm').on('submit', function(e) {
            // Ensure hidden inputs are updated
            updateMedicalConditionsDisplay();
            updateAllergiesDisplay();
            return true;
        });

        // Load existing data and initialize
        loadExistingData();
        loadMedicalConditions();
        loadAllergies();
    });
</script>
@endpush