@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
    <div class="app">

        @include('layouts.sidebar')

        <main class="flex-1 p-6 ml-60">

            {{-- HEADER --}}
            <div class="flex w-full sm:w-auto gap-2 mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Edit Appointment</h1>
            </div>

            {{-- CARD --}}
            <div class="doctor-card mx-auto">

                <div class="header-section">
                    <h2>Edit Appointment Information</h2>
                </div>

                <form method="POST" action="{{ route('appointments.update', $appointment->id) }}">
                    @csrf
                    @method('PUT')

                    {{-- Appointment ID --}}
                    <div class="form-group">
                        <label>Appointment ID</label>
                        <div class="doctor-id">{{ $appointment->id }}</div>
                    </div>

                    {{-- GRID --}}
                    <div class="form-grid">

                        <div class="form-group">
                            <label>Patient Name <span class="required">*</span></label>
                            <input list="patientList" id="patientNameInput" name="patient_name"
                                placeholder="Type to search..." 
                                value="{{ old('patient_name', $appointment->patient->full_name ?? '') }}" 
                                oninput="updatePatientId()"
                                required>
                            <datalist id="patientList">
                                @foreach ($patients as $p)
                                    <option value="{{ $p->full_name }}" data-id="{{ $p->id }}"></option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="patient_id" id="patientIdHidden" value="{{ old('patient_id', $appointment->patient_id) }}">
                            @error('patient_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Service <span class="required">*</span></label>
                            <input list="serviceList" id="serviceNameInput" name="service_name"
                                placeholder="Type to search..." 
                                value="{{ old('service_name', $appointment->service->service_name ?? '') }}"
                                oninput="updateServiceId()"
                                required>
                            <datalist id="serviceList">
                                @foreach ($services as $s)
                                    <option value="{{ $s->service_name }}" data-id="{{ $s->id }}"
                                        data-price="{{ $s->service_fee }}"></option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="service_id" id="serviceIdHidden" value="{{ old('service_id', $appointment->service_id) }}">
                            @error('service_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Speciality (Filter)</label>
                            <input list="specialityList" id="specialityInput" 
                                placeholder="Type to filter doctors by speciality..." 
                                value="{{ old('speciality', $appointment->doctor->speciality ?? '') }}"
                                oninput="filterDoctorsBySpeciality()">
                            <datalist id="specialityList">
                                @foreach ($doctors->unique('speciality') as $doctor)
                                    <option value="{{ $doctor->speciality }}">{{ $doctor->speciality }}</option>
                                @endforeach
                            </datalist>
                        </div>

                        <div class="form-group">
                            <label>Doctor <span class="required">*</span></label>
                            <input list="doctorList" id="doctorNameInput" name="doctor_name"
                                placeholder="Type to search doctor..." 
                                value="{{ old('doctor_name', $appointment->doctor->full_name ?? '') }}"
                                oninput="updateDoctorId()"
                                onchange="updateSpecialityFromDoctor()" 
                                required>
                            <datalist id="doctorList">
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->full_name }}" 
                                            data-id="{{ $doctor->id }}" 
                                            data-speciality="{{ $doctor->speciality }}"
                                            title="{{ $doctor->speciality }}">
                                        {{ $doctor->full_name }} - {{ $doctor->speciality }}
                                    </option>
                                @endforeach
                            </datalist>
                            <input type="hidden" name="doctor_id" id="doctorIdHidden" value="{{ old('doctor_id', $appointment->doctor_id) }}">
                            @error('doctor_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Date <span class="required">*</span></label>
                            <input type="date" name="appointment_date"
                                value="{{ old('appointment_date', $appointment->appointment_date) }}" required>
                            @error('appointment_date')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>

                    {{-- BUTTONS --}}
                    <div class="footer-buttons">
                        <a href="{{ route('appointments.index') }}" class="cancel-btn">
                            Cancel
                        </a>
                        <button type="submit" class="submit-btn">
                            Update Appointment
                        </button>
                    </div>

                </form>
            </div>
        </main>
    </div>

    <style>
        /* CARD */
        .doctor-card {
            background: #f6fcff;
            width: 500px;
            max-width: 95vw;
            padding: 25px 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
        }

        /* HEADER */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0f2fe;
        }

        .doctor-card h2 {
            color: #2b6de8;
            font-size: 24px;
            font-weight: 700;
        }

        /* GRID */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 25px;
        }

        .form-group label {
            font-weight: 600;
            color: #1f3b57;
            margin-bottom: 8px;
            display: block;
        }

        /* INPUT */
        .form-group input,
        .form-group select,
        .doctor-id {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 2px solid #c8e1f3;
            font-size: 14px;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, .2);
        }

        /* ID */
        .doctor-id {
            background: #eaf4fb;
            font-weight: 600;
            color: #355f8c;
        }

        /* BUTTONS */
        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e0f2fe;
        }

        .submit-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }

        .cancel-btn {
            padding: 12px 24px;
            border: 1px solid #94a3b8;
            border-radius: 8px;
            color: #64748b;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .cancel-btn:hover {
            border-color: #ef4444;
            color: #ef4444;
        }

        /* REQUIRED */
        .required {
            color: #ef4444;
        }

        /* MOBILE */
        @media(max-width:768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .footer-buttons {
                flex-direction: column;
            }

            .submit-btn,
            .cancel-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* ERROR */
        .text-red-500 {
            color: #ef4444;
            font-size: .85rem;
        }
    </style>

    <script>
        const doctorsData = [
            @foreach ($doctors as $doctor)
            {
                id: {{ $doctor->id }},
                name: "{{ $doctor->full_name }}",
                speciality: "{{ $doctor->speciality }}"
            },
            @endforeach
        ];

        // Store current doctor value to prevent clearing
        let currentDoctorValue = "{{ old('doctor_name', $appointment->doctor->full_name ?? '') }}";

        // Function to filter doctors based on selected speciality
        function filterDoctorsBySpeciality() {
            const specialityInput = document.getElementById('specialityInput').value.toLowerCase();
            const doctorDatalist = document.getElementById('doctorList');
            const doctorInput = document.getElementById('doctorNameInput');
            
            // Store current input value
            const currentValue = doctorInput.value;
            
            // Remove all current options
            while (doctorDatalist.firstChild) {
                doctorDatalist.removeChild(doctorDatalist.firstChild);
            }
            
            // Filter and add doctors that match the speciality
            let hasMatches = false;
            doctorsData.forEach(doctor => {
                if (specialityInput === '' || doctor.speciality.toLowerCase().includes(specialityInput)) {
                    hasMatches = true;
                    const option = document.createElement('option');
                    option.value = doctor.name;
                    option.setAttribute('data-id', doctor.id);
                    option.setAttribute('data-speciality', doctor.speciality);
                    option.textContent = `${doctor.name} - ${doctor.speciality}`;
                    doctorDatalist.appendChild(option);
                }
            });
            
            // If no matches, show a "no results" indicator
            if (!hasMatches && specialityInput !== '') {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No doctors found for this speciality';
                option.disabled = true;
                doctorDatalist.appendChild(option);
            }
            
            // Restore the input value
            doctorInput.value = currentValue || currentDoctorValue;
        }

        // Function to update speciality and hidden ID when doctor is selected
        function updateSpecialityFromDoctor() {
            const doctorInput = document.getElementById('doctorNameInput').value;
            const doctorOptions = document.getElementById('doctorList').options;
            const specialityInput = document.getElementById('specialityInput');
            
            // Find the selected doctor in the datalist
            for (let i = 0; i < doctorOptions.length; i++) {
                const option = doctorOptions[i];
                if (option.value === doctorInput) {
                    const doctorSpeciality = option.getAttribute('data-speciality');
                    const doctorId = option.getAttribute('data-id');
                    if (doctorSpeciality) {
                        specialityInput.value = doctorSpeciality;
                    }
                    if (doctorId) {
                        document.getElementById('doctorIdHidden').value = doctorId;
                    }
                    break;
                }
            }
        }

        // Function to update patient ID
        function updatePatientId() {
            const patientInput = document.getElementById('patientNameInput').value;
            const patientOptions = document.getElementById('patientList').options;
            
            for (let i = 0; i < patientOptions.length; i++) {
                if (patientOptions[i].value === patientInput) {
                    document.getElementById('patientIdHidden').value = patientOptions[i].getAttribute('data-id');
                    break;
                }
            }
        }

        // Function to update service ID
        function updateServiceId() {
            const serviceInput = document.getElementById('serviceNameInput').value;
            const serviceOptions = document.getElementById('serviceList').options;
            
            for (let i = 0; i < serviceOptions.length; i++) {
                if (serviceOptions[i].value === serviceInput) {
                    document.getElementById('serviceIdHidden').value = serviceOptions[i].getAttribute('data-id');
                    break;
                }
            }
        }

        // Function to update doctor ID
        function updateDoctorId() {
            const doctorInput = document.getElementById('doctorNameInput').value;
            const doctorOptions = document.getElementById('doctorList').options;
            
            for (let i = 0; i < doctorOptions.length; i++) {
                if (doctorOptions[i].value === doctorInput) {
                    document.getElementById('doctorIdHidden').value = doctorOptions[i].getAttribute('data-id');
                    break;
                }
            }
            
            // Also update speciality
            updateSpecialityFromDoctor();
        }

        // Add event listeners
        document.getElementById('doctorNameInput').addEventListener('input', function(e) {
            // Update current value
            currentDoctorValue = this.value;
            // Update doctor ID with delay to allow datalist selection
            setTimeout(updateDoctorId, 100);
        });

        document.getElementById('patientNameInput').addEventListener('input', function() {
            setTimeout(updatePatientId, 100);
        });

        document.getElementById('serviceNameInput').addEventListener('input', function() {
            setTimeout(updateServiceId, 100);
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initial filter to show all doctors
            filterDoctorsBySpeciality();
            
            // Set initial values
            const doctorInput = document.getElementById('doctorNameInput');
            const patientInput = document.getElementById('patientNameInput');
            const serviceInput = document.getElementById('serviceNameInput');
            
            // Trigger ID updates for initial values
            setTimeout(() => {
                if (doctorInput.value) updateDoctorId();
                if (patientInput.value) updatePatientId();
                if (serviceInput.value) updateServiceId();
            }, 200);
            
            // Add event listener for speciality input changes
            document.getElementById('specialityInput').addEventListener('input', function() {
                filterDoctorsBySpeciality();
            });
        });

        // Handle form submission to ensure IDs are present
        document.querySelector('form').addEventListener('submit', function(e) {
            const patientId = document.getElementById('patientIdHidden').value;
            const doctorId = document.getElementById('doctorIdHidden').value;
            const serviceId = document.getElementById('serviceIdHidden').value;
            
            if (!patientId || !doctorId || !serviceId) {
                e.preventDefault();
                alert('Please select valid Patient, Doctor, and Service from the lists.');
            }
        });
    </script>
@endsection