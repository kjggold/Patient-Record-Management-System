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
                            <input type="number" name="age" placeholder="Enter Age" min="0" max="120" required
                                   value="{{ old('age', $patient->age) }}">
                            @error('age')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Date of Birth</label>
                            <input type="date" name="date_of_birth" required
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
                                <option value="" disabled {{ !old('sex_gender', $patient->sex_gender) ? 'selected' : '' }}>Select</option>
                                <option value="male" {{ old('sex_gender', $patient->sex_gender) == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('sex_gender', $patient->sex_gender) == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('sex_gender')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Phone Number</label>
                            <input type="tel" name="phone_number" placeholder="Enter Phone Number" required
                                   value="{{ old('phone_number', $patient->phone_number) }}">
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
                            <textarea id="known_medical_conditions" name="known_medical_conditions" rows="2" 
                                placeholder="List known medical conditions">{{ old('known_medical_conditions', $patient->known_medical_conditions) }}</textarea>
                            @error('known_medical_conditions')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Allergies</label>
                            <textarea id="allergies" name="allergies" rows="2" 
                                placeholder="List any allergies">{{ old('allergies', $patient->allergies) }}</textarea>
                            @error('allergies')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Blood Type</label>
                            <select name="blood_type">
                                <option value="" {{ !old('blood_type', $patient->blood_type) ? 'selected' : '' }}>Select</option>
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
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Alcohol Consumption</label>
                            <div class="radio-group">
                                <label><input type="radio" name="alcohol_consumption" value="none" 
                                    {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'none' ? 'checked' : '' }}> None</label>
                                <label><input type="radio" name="alcohol_consumption" value="occasional"
                                    {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'occasional' ? 'checked' : '' }}> Occasional</label>
                                <label><input type="radio" name="alcohol_consumption" value="regular"
                                    {{ old('alcohol_consumption', $patient->alcohol_consumption) == 'regular' ? 'checked' : '' }}> Regular</label>
                            </div>
                            @error('alcohol_consumption')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="required">Assigned Doctor</label>
                            <select name="assigned_doctor" required>
                                <option value="" disabled {{ !old('assigned_doctor', $patient->assigned_doctor) ? 'selected' : '' }}>Select Doctor</option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" 
                                        {{ old('assigned_doctor', $patient->assigned_doctor) == $doctor->id ? 'selected' : '' }}>
                                        {{ $doctor->full_name }} ({{ $doctor->speciality }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_doctor')
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
@endsection