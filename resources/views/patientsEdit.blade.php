@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
    <div class="app">
        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <!-- HEADER -->
            <div class="flex w-full sm:w-auto gap-2 mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Edit Patient</h1>
            </div>

            <!-- EDIT PATIENT FORM -->
            <div class="patient-form-container">
                <h1 class="form-title">Edit Patient Information</h1>

                <form method="POST" action="{{ route('patients.update', $patient->id) }}" id="patientForm">
                    @csrf
                    @method('PUT')

                    <!-- Patient Information Section -->
                    <h2 class="section-title">Patient Information</h2>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Patient ID</label>
                            <div class="patient-id">{{ $patient->id }}</div>
                        </div>
                        <div class="form-group">
                            <label class="required">Full Name</label>
                            <input type="text" name="full_name" placeholder="Enter Full Name" required
                                value="{{ old('full_name', $patient->full_name) }}">
                            @error('full_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Age</label>
                            <input type="number" name="age" id="age" placeholder="Enter Age" min="0"
                                max="120" required value="{{ old('age', $patient->age) }}">
                            @error('age')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" required
                                value="{{ old('date_of_birth', $patient->date_of_birth) }}">
                            @error('date_of_birth')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Sex / Gender</label>
                            <select name="sex_gender" required>
                                <option value="" disabled
                                    {{ !old('sex_gender', $patient->sex_gender) ? 'selected' : '' }}>Select</option>
                                <option value="male"
                                    {{ old('sex_gender', $patient->sex_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female"
                                    {{ old('sex_gender', $patient->sex_gender) == 'female' ? 'selected' : '' }}>Female
                                </option>
                            </select>
                            @error('sex_gender')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Phone Number</label>
                            <input type="tel" name="phone_number" id="phone_number" placeholder="Enter Phone Number"
                                required value="{{ old('phone_number', $patient->phone_number) }}"
                                oninput="formatPhoneNumber(this)">
                            @error('phone_number')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Address</label>
                            <input type="text" name="address" placeholder="Enter Address" required
                                value="{{ old('address', $patient->address) }}">
                            @error('address')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Known Medical Conditions</label>
                            <input type="text" id="known_medical_conditions_input"
                                placeholder="Type condition and press Enter" class="mb-1">
                            <small class="text-gray-500 text-xs block">Type and press Enter to add multiple
                                conditions</small>
                            <div id="conditions-container" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Allergies</label>
                            <input type="text" id="allergies_input" placeholder="Type allergy and press Enter"
                                class="mb-1">
                            <small class="text-gray-500 text-xs block">Type and press Enter to add multiple
                                allergies</small>
                            <div id="allergies-container" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                        <div class="form-group">
                            <label>Blood Type</label>
                            <select name="blood_type">
                                <option value="" {{ !old('blood_type', $patient->blood_type) ? 'selected' : '' }}>
                                    Select</option>
                                <option value="A+"
                                    {{ old('blood_type', $patient->blood_type) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-"
                                    {{ old('blood_type', $patient->blood_type) == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+"
                                    {{ old('blood_type', $patient->blood_type) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-"
                                    {{ old('blood_type', $patient->blood_type) == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="O+"
                                    {{ old('blood_type', $patient->blood_type) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-"
                                    {{ old('blood_type', $patient->blood_type) == 'O-' ? 'selected' : '' }}>O-</option>
                                <option value="AB+"
                                    {{ old('blood_type', $patient->blood_type) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-"
                                    {{ old('blood_type', $patient->blood_type) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="unknown"
                                    {{ old('blood_type', $patient->blood_type) == 'unknown' ? 'selected' : '' }}>Unknown
                                </option>
                            </select>
                            @error('blood_type')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Alcohol Consumption</label>
                            <div class="radio-group">
                                @php
                                    $alcohol = strtolower(
                                        old('alcohol_consumption', $patient->alcohol_consumption ?? 'none'),
                                    );
                                @endphp
                                <label><input type="radio" name="alcohol_consumption" value="none"
                                        {{ $alcohol == 'none' ? 'checked' : '' }}> None</label>
                                <label><input type="radio" name="alcohol_consumption" value="occasional"
                                        {{ $alcohol == 'occasional' ? 'checked' : '' }}> Occasional</label>
                                <label><input type="radio" name="alcohol_consumption" value="regular"
                                        {{ $alcohol == 'regular' ? 'checked' : '' }}> Regular</label>
                            </div>
                            @error('alcohol_consumption')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="required">Registration Date</label>
                            <input type="date" name="registration_date" required
                                value="{{ old('registration_date', $patient->registration_date) }}">
                            @error('registration_date')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="button-container">
                        <a href="{{ route('patients.index') }}" class="cancel-btn text-center">
                            Cancel
                        </a>
                        <button type="submit" class="register-btn">Update Patient</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- STYLES -->
    <style>
        /* === FORM CONTAINER === */
        .patient-form-container {
            background-color: #f6fcff;
            width: 800px;
            max-width: 95%;
            margin: 0 auto;
            padding: 30px 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .form-title {
            text-align: center;
            color: #2b6de8;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .section-title {
            color: #1f3b57;
            font-size: 20px;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #e0f0ff;
            font-weight: 600;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 18px;
        }

        .form-group {
            flex: 1;
            min-width: 250px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1f3b57;
            margin-bottom: 6px;
        }

        .form-group label.required::after {
            content: " *";
            color: #ef4444;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid #c8e1f3;
            font-size: 15px;
            background-color: #fff;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        .patient-id {
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid #c8e1f3;
            background-color: #eaf4fb;
            font-weight: 600;
            color: #355f8c;
            min-height: 46px;
        }

        .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
        }

        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .register-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(to right, #10b981, #059669);
            color: #fff;
            transition: all 0.3s;
        }

        .register-btn:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(5, 150, 105, 0.3);
        }

        .cancel-btn {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            background-color: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cancel-btn:hover {
            background-color: #e2e8f0;
            transform: translateY(-2px);
            text-decoration: none;
        }

        .text-red-500 {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
    </style>

    <!-- jQuery and jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

    <script>
        $(document).ready(function() {
            // ============ MEDICAL CONDITIONS & ALLERGIES ============

            // Initialize arrays
            let medicalConditions = [];
            let allergies = [];

            // Load existing values from database
            @if ($patient->known_medical_conditions)
                medicalConditions = "{{ $patient->known_medical_conditions }}".split(',').map(item => item.trim())
                    .filter(item => item !== '');
            @endif

            @if ($patient->allergies)
                allergies = "{{ $patient->allergies }}".split(',').map(item => item.trim()).filter(item => item !==
                    '');
            @endif

            // Debug - check values in console
            console.log('Loaded medical conditions:', medicalConditions);
            console.log('Loaded allergies:', allergies);

            // Function to render medical condition tags
            function renderConditionTags() {
                const container = $('#conditions-container');
                container.empty();

                medicalConditions.forEach((condition, index) => {
                    const tag = $(`
                        <span class="condition-tag">
                            ${condition}
                            <button type="button" onclick="removeCondition(${index})">×</button>
                        </span>
                    `);
                    container.append(tag);
                });

                updateHiddenInputs();
            }

            // Function to render allergy tags
            function renderAllergyTags() {
                const container = $('#allergies-container');
                container.empty();

                allergies.forEach((allergy, index) => {
                    const tag = $(`
                        <span class="allergy-tag">
                            ${allergy}
                            <button type="button" onclick="removeAllergy(${index})">×</button>
                        </span>
                    `);
                    container.append(tag);
                });

                updateHiddenInputs();
            }

            // Global remove functions
            window.removeCondition = function(index) {
                medicalConditions.splice(index, 1);
                renderConditionTags();
            };

            window.removeAllergy = function(index) {
                allergies.splice(index, 1);
                renderAllergyTags();
            };

            // Update hidden inputs
            function updateHiddenInputs() {
                // Remove existing hidden inputs
                $('input[name="known_medical_conditions"]').remove();
                $('input[name="allergies"]').remove();

                // Add new hidden inputs
                $('#patientForm').append(`
                    <input type="hidden" name="known_medical_conditions" value="${medicalConditions.join(', ')}">
                `);

                $('#patientForm').append(`
                    <input type="hidden" name="allergies" value="${allergies.join(', ')}">
                `);

                console.log('Updated hidden inputs - Conditions:', medicalConditions.join(', '));
                console.log('Updated hidden inputs - Allergies:', allergies.join(', '));
            }

            // Render initial tags
            renderConditionTags();
            renderAllergyTags();

            // ============ AUTOCOMPLETE & ENTER KEY ============

            // Load medical conditions for autocomplete
            let medicalConditionsList = [];

            $.ajax({
                url: '{{ asset('data/medical-conditions.json') }}',
                dataType: 'json',
                cache: true,
                success: function(data) {
                    medicalConditionsList = data;
                },
                error: function() {
                    medicalConditionsList = [
                        "Hypertension", "Diabetes", "Asthma", "Arthritis", "Migraine",
                        "Anxiety", "Depression", "High Cholesterol", "Heart Disease",
                        "Allergic Rhinitis", "GERD", "Osteoporosis", "COPD"
                    ];
                }
            });

            // Load allergies for autocomplete
            let allergiesList = [];

            $.ajax({
                url: '{{ asset('data/allergies.json') }}',
                dataType: 'json',
                cache: true,
                success: function(data) {
                    allergiesList = data;
                },
                error: function() {
                    allergiesList = [
                        "Penicillin", "Sulfa Drugs", "NSAIDs", "Aspirin", "Ibuprofen",
                        "Codeine", "Latex", "Pollen", "Dust Mites", "Peanuts"
                    ];
                }
            });

            // Medical conditions autocomplete
            $('#known_medical_conditions_input').autocomplete({
                source: function(request, response) {
                    const term = request.term.toLowerCase();
                    const filtered = medicalConditionsList.filter(item =>
                        item.toLowerCase().includes(term)
                    );
                    response(filtered.slice(0, 15));
                },
                minLength: 1,
                delay: 100,
                select: function(event, ui) {
                    event.preventDefault();
                    const condition = ui.item.value;
                    if (condition && !medicalConditions.includes(condition)) {
                        medicalConditions.push(condition);
                        renderConditionTags();
                    }
                    $(this).val('');
                    return false;
                }
            });

            // Add condition on Enter key
            $('#known_medical_conditions_input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const condition = $(this).val().trim();
                    if (condition && !medicalConditions.includes(condition)) {
                        medicalConditions.push(condition);
                        renderConditionTags();
                    }
                    $(this).val('');
                    return false;
                }
            });

            // Allergies autocomplete
            $('#allergies_input').autocomplete({
                source: function(request, response) {
                    const term = request.term.toLowerCase();
                    const filtered = allergiesList.filter(item =>
                        item.toLowerCase().includes(term)
                    );
                    response(filtered.slice(0, 15));
                },
                minLength: 1,
                delay: 100,
                select: function(event, ui) {
                    event.preventDefault();
                    const allergy = ui.item.value;
                    if (allergy && !allergies.includes(allergy)) {
                        allergies.push(allergy);
                        renderAllergyTags();
                    }
                    $(this).val('');
                    return false;
                }
            });

            // Add allergy on Enter key
            $('#allergies_input').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    const allergy = $(this).val().trim();
                    if (allergy && !allergies.includes(allergy)) {
                        allergies.push(allergy);
                        renderAllergyTags();
                    }
                    $(this).val('');
                    return false;
                }
            });

            // ============ AGE CALCULATION ============

            // Calculate age from date of birth
            $('#date_of_birth').on('change', function() {
                const dob = new Date($(this).val());
                if ($(this).val() && !isNaN(dob.getTime())) {
                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const monthDiff = today.getMonth() - dob.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }

                    if (age >= 0 && age <= 120) {
                        $('#age').val(age);
                    }
                }
            });

            // Add calculate age button
            const dobGroup = $('#date_of_birth').closest('.form-group');
            if (dobGroup.find('.calculate-age-btn').length === 0) {
                const calcBtn = $(`
                    <button type="button" class="calculate-age-btn mt-2 text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200">
                        Calculate Age from Date
                    </button>
                `);

                calcBtn.on('click', function() {
                    const dob = new Date($('#date_of_birth').val());
                    if (!$('#date_of_birth').val() || isNaN(dob.getTime())) {
                        alert('Please select a valid date of birth first.');
                        return;
                    }

                    const today = new Date();
                    let age = today.getFullYear() - dob.getFullYear();
                    const monthDiff = today.getMonth() - dob.getMonth();

                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                        age--;
                    }

                    if (age >= 0 && age <= 120) {
                        $('#age').val(age);
                    } else {
                        alert('Invalid date of birth. Age must be between 0 and 120.');
                    }
                });

                dobGroup.append(calcBtn);
            }

            // ============ PHONE NUMBER FORMATTING ============

            window.formatPhoneNumber = function(input) {
                let phone = input.value.replace(/\D/g, '');

                if (phone.length > 0) {
                    if (phone.length <= 3) {
                        phone = '(' + phone;
                    } else if (phone.length <= 6) {
                        phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3);
                    } else {
                        phone = '(' + phone.substring(0, 3) + ') ' + phone.substring(3, 6) + '-' + phone
                            .substring(6, 10);
                    }
                }

                input.value = phone;
            };

            // Format phone number on page load
            const phoneInput = document.getElementById('phone_number');
            if (phoneInput && phoneInput.value) {
                window.formatPhoneNumber(phoneInput);
            }

            // ============ FORM SUBMISSION ============

            // Update hidden inputs before submit
            $('#patientForm').on('submit', function(e) {
                updateHiddenInputs();
                return true;
            });

        }); // End document.ready
    </script>
@endsection
