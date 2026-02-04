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
                <input type="text" id="searchInput" placeholder="Search by name..." class="border rounded px-3 py-2 w-64">

                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Patient
                </button>
            </div>

            <!-- PATIENT TABLE -->
            <div class="bg-white rounded-xl shadow overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-sky-50 text-slate-600">
                        <tr>
                            <th class="px-4 py-3 text-left">ID</th>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Age</th>
                            <th class="px-4 py-3 text-left">Phone</th>
                            <th class="px-4 py-3 text-left">Assigned Doctor</th>
                            <th class="px-4 py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($patients as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $p->id }}</td>
                                <td class="px-4 py-3">{{ $p->full_name }}</td>
                                <td class="px-4 py-3">{{ $p->age }}</td>
                                <td class="px-4 py-3">{{ $p->phone_number }}</td>

                                <td class="px-4 py-3">
                                    @if ($p->doctor)
                                        {{ $p->doctor->full_name }}
                                        @if ($p->doctor->speciality)
                                            <span class="text-xs text-gray-500">({{ $p->doctor->speciality }})</span>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Not Assigned</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center space-x-2">
                                    <button class="text-blue-600 hover:underline">View</button>
                                    
                                    <button onclick="window.location.href='{{ route('patients.edit', $p->id) }}'" 
                                            class="text-amber-600 hover:underline">
                                        Edit
                                    </a>
                                    <form
                                        action="/patients/{{ $p->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this patient?')"
                                    >
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
        </main>
    </div>

    <!-- VIEW PATIENT MODAL -->
    <div id="viewModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl w-full max-w-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-slate-700">Patient Details</h2>
            <div class="grid grid-cols-2 gap-4 text-sm">
                @foreach ($patients as $p)
                    <div><b>ID:</b> {{ $p->id }}</div>
                    <div><b>Name:</b>{{ $p->full_name }}</div>
                    <div><b>Age:</b>{{ $p->age }}</div>
                    <div><b>Gender:</b> {{ $p->sex_gender }}</div>
                    <div><b>Birth Date:</b>{{ $p->date_of_birth }}</div> 
                    <div><b>Phone:</b> {{ $p->phone_number }}</div>
                    <div><b>Address:</b>{{ $p->address }}</div>
                    <div><b>Known Medical Conditioins:</b>{{ $p->known_medical_conditions }}</div>
                    <div><b>Allergies:</b>{{ $p->allergies }}</div>
                    <div><b>Blood Type:</b>{{ $p->blood_type }}</div>
                    <div><b>Alcohol Consumption:</b>{{ $p->alcohol_consumption }}</div>
                    <div><b>Assigned Doctor:</b>{{ $p->assigned_doctor }}</div>
                    <div><b>Registration Date:</b>{{ $p->registration_date }}</div>
                @endforeach

            </div>
            <div class="text-right mt-6">
                <button onclick="closeViewModal()" class="px-4 py-2 bg-slate-200 rounded hover:bg-slate-300">Close</button>
            </div>
        </div>
    </div>

    <!-- ADD PATIENT MODAL -->
    <div id="addModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 overflow-auto py-10">
        <div class="patient-form-container">
            <h1 class="form-title">Patient Registration</h1>

            <form method="POST" action="{{ route('patients.store') }}" id="patientForm">
                @csrf

                <!-- Patient Information Section -->
                <h2 class="section-title">Patient Information</h2>
                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group">
                        <label class="required">Age</label>
                        <input type="number" name="age" placeholder="Enter Age" min="0" max="120" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Date of Birth</label>
                        <input type="date" name="date_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label class="required">Sex / Gender</label>
                        <div class="radio-group">
                            <label><input type="radio" name="sex_gender" value="male" checked>Male</label>
                            <label><input type="radio" name="sex_gender" value="female">Female</label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Phone Number</label>
                        <input type="tel" name="phone_number" placeholder="Enter Phone Number" required>
                    </div>
                    <div class="form-group">
                        <label class="required">Address</label>
                        <input type="text" name="address" placeholder="Enter Address" required>
                    </div>
                </div>

                <!-- Medical History Section -->
                <h2 class="section-title">Medical History</h2>
                <div class="form-row">
                    <div class="form-group" style="flex: 1 1 100%; min-width: 100%;">
                        <label>Known Medical Conditions</label>
                        <link href='https://clinicaltables.nlm.nih.gov/autocomplete-lhc-versions/19.2.4/autocomplete-lhc.min.css' rel="stylesheet">
                        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
                        <script src='https://clinicaltables.nlm.nih.gov/autocomplete-lhc-versions/19.2.4/autocomplete-lhc.min.js'></script>
                        <textarea id="known_medical_conditions" name="known_medical_conditions" rows="4"
                            placeholder="Type to search medical conditions. Press Enter or click to add multiple conditions."
                            style="width: 100%; height: 120px; resize: vertical;"></textarea>
                        <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple conditions</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group" style="flex: 1 1 100%; min-width: 100%;">
                        <label>Allergies</label>
                        <textarea id="allergies" name="allergies" rows="4"
                            placeholder="Type to search allergies. Press Enter or click to add multiple allergies."
                            style="width: 100%; height: 120px; resize: vertical;"></textarea>
                        <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple allergies</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Blood Type</label>
                        <select name="blood_type">
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
                            <label><input type="radio" name="alcohol_consumption" value="occasional">
                                Occasional</label>
                            <label><input type="radio" name="alcohol_consumption" value="regular"> Regular</label>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="required">Assigned Doctor</label>
                        <select name="assigned_doctor" required>
                            <option value="" disabled selected>Select Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->full_name }} ({{ $doctor->speciality }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="required">Registration Date</label>
                        <input type="date" name="registration_date" required>
                    </div>
                </div>

                <div class="button-container">
                    <button type="button" onclick="closeAddModal()" class="cancel-btn">Cancel</button>
                    <button type="submit" class="register-btn">Register Patient</button>
                </div>
            </form>
        </div>
    </div>

    <!-- STYLES -->
    <style>
        /* === BUTTON === */
        .add-patient-btn {
            padding: 10px 20px;
            border-radius: 11px;
            font-weight: 600;
            font-size: 15px;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
        }

        .add-patient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        /* === MODAL & FORM === */
        .patient-form-container {
            background-color: #f6fcff;
            width: 800px;
            max-width: 95%;
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
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: #fff;
            transition: all 0.3s;
        }

        .register-btn:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .cancel-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            background-color: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
        }

        .cancel-btn:hover {
            background-color: #e2e8f0;
            transform: translateY(-2px);
        }

        .dob-inputs {
            display: flex;
            gap: 10px;
        }

        .dob-inputs input {
            flex: 1;
            text-align: center;
        }
    </style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>
    <!-- SCRIPTS -->
    <script>
        function openViewModal() {
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
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
                url: '{{ asset("data/medical-conditions.json") }}',
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
                url: '{{ asset("data/allergies.json") }}',
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
                container.append(`<input type="hidden" name="known_medical_conditions_hidden" value="${medicalConditions.join('|')}">`);
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
                container.append(`<input type="hidden" name="allergies_hidden" value="${allergies.join('|')}">`);
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
        
        // Modal functions
        function openViewModal() {
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        }
        
        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }
        
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
            // Reset arrays when opening modal
            medicalConditions = [];
            allergies = [];
            // Clear any existing tags
            $('.tags-container').remove();
        }
        
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
    });

    // Age and Date of Birth synchronization
function setupAgeDateSync() {
    const ageInput = document.querySelector('input[name="age"]');
    const dobInput = document.querySelector('input[name="date_of_birth"]');
    
    if (!ageInput || !dobInput) return;
    
    // When age is entered, calculate date of birth
    // ageInput.addEventListener('input', function() {
    //     const age = parseInt(this.value);
    //     if (!isNaN(age) && age >= 0 && age <= 120) {
    //         const today = new Date();
    //         const birthYear = today.getFullYear() - age;
            
    //         // Calculate approximate birth date (using June 30 as mid-year)
    //         const birthDate = new Date(birthYear, 5, 30); // June 30th
            
    //         // Format as YYYY-MM-DD for date input
    //         const formattedDate = birthDate.toISOString().split('T')[0];
    //         dobInput.value = formattedDate;
    //     } else if (this.value === '') {
    //         dobInput.value = '';
    //     }
    // });
    
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
    button.className = 'calculate-age-btn mt-2 text-sm bg-blue-100 text-blue-600 px-3 py-1 rounded hover:bg-blue-200';
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
document.addEventListener('DOMContentLoaded', function() {
    setupAgeDateSync();
});
</script>
    

@endsection