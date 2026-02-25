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

                        {{-- Patient --}}
                        <div class="form-group">
                            <label>Patient <span class="required">*</span></label>
                            <select name="patient_id" id="patientSelect" required>
                                <option value="">Select Patient</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Service --}}
                        <div class="form-group">
                            <label>Service <span class="required">*</span></label>
                            <select name="service_id" id="serviceSelect" required>
                                <option value="">Select Service</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->id }}"
                                        {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->service_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Speciality --}}
                        <div class="form-group">
                            <label>Speciality</label>
                            <select id="specialitySelect" class="w-full p-3 rounded-lg border border-gray-300 bg-white" onchange="filterDoctorsBySpeciality()">
                                <option value="">All Specialities</option>
                                @php
                                    $uniqueSpecialities = $doctors->pluck('speciality')->unique()->sort();
                                @endphp
                                @foreach ($uniqueSpecialities as $speciality)
                                    <option value="{{ $speciality }}">{{ $speciality }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Doctor --}}
                        <div class="form-group">
                            <label>Doctor <span class="required">*</span></label>
                            <select name="doctor_id" id="doctorSelect" required onchange="updateSpecialityFromDoctor()">
                                <option value="">Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                        data-speciality="{{ $doctor->speciality }}"
                                        {{ old('doctor_id', $appointment->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->full_name }} - {{ $doctor->speciality }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Date --}}
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
        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Get the currently selected doctor and set speciality
            const doctorSelect = document.getElementById('doctorSelect');
            const specialitySelect = document.getElementById('specialitySelect');
            
            // Set the speciality based on the selected doctor
            if (doctorSelect.value) {
                updateSpecialityFromDoctor();
            }
        });

        function filterDoctorsBySpeciality() {
            const speciality = document.getElementById('specialitySelect').value;
            const doctorSelect = document.getElementById('doctorSelect');
            const options = doctorSelect.options;
            
            // Store the currently selected value
            const currentValue = doctorSelect.value;
            
            // Show/hide options based on speciality
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.value === '') continue; // Skip the placeholder option
                
                const doctorSpeciality = option.getAttribute('data-speciality');
                
                if (!speciality || (doctorSpeciality && doctorSpeciality === speciality)) {
                    option.style.display = '';
                    option.disabled = false;
                } else {
                    option.style.display = 'none';
                    option.disabled = true;
                }
            }
            
            // If the currently selected doctor is not in the filtered list, clear selection
            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            if (selectedOption && selectedOption.disabled) {
                doctorSelect.value = '';
            }
        }

        function updateSpecialityFromDoctor() {
            const doctorSelect = document.getElementById('doctorSelect');
            const specialitySelect = document.getElementById('specialitySelect');
            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const doctorSpeciality = selectedOption.getAttribute('data-speciality');
                
                // Find and select matching speciality
                for (let i = 0; i < specialitySelect.options.length; i++) {
                    if (specialitySelect.options[i].value === doctorSpeciality) {
                        specialitySelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }

        // Reset filter when needed
        function resetFilter() {
            const specialitySelect = document.getElementById('specialitySelect');
            specialitySelect.value = '';
            filterDoctorsBySpeciality();
        }
    </script>
@endsection