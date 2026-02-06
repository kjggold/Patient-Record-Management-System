<!-- ADD PATIENT MODAL -->
    <div id="patientModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 overflow-auto py-10">
        <div class="patient-form-container">
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
                <div class="form-row">
                    <div class="form-group">
                        <label>Known Medical Conditions</label>
                        <input type="text" id="known_medical_conditions" name="known_medical_conditions" rows="2"
                            placeholder="Type to search medical conditions.">
                        <small class="text-gray-500 text-xs mt-1 block">Type and press Enter to add multiple conditions</small>
                    </div>
                    <div class="form-group">
                        <label>Allergies</label>
                        <input id="allergies" name="allergies" rows="2"
                            placeholder="Type to search allergies.">
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
                    <button type="button" onclick="closePatientModal()" class="cancel-btn">Cancel</button>
                    <button type="submit" class="register-btn">Register Patient</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* === MODAL & FORM === */
        #patientModal  .patient-form-container {
            background-color: #f6fcff;
            width: 800px;
            max-width: 95%;
            padding: 30px 35px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        #patientModal  .form-title {
            text-align: center;
            color: #2b6de8;
            font-size: 28px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        #patientModal  .section-title {
            color: #1f3b57;
            font-size: 20px;
            margin: 14px 0 10px 0;   /* ↓ tighter top & bottom */
                padding-bottom: 6px;
            border-bottom: 2px solid #e0f0ff;
            font-weight: 600;
        }

        #patientModal  .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 18px;
        }

        #patientModal  .form-group {
            flex: 1;
            min-width: 250px;
        }

        #patientModal  .form-group label {
            display: block;
            font-weight: 600;
            color: #1f3b57;
            margin-bottom: 6px;
        }

        #patientModal  .form-group input,
        #patientModal  .form-group select,
        #patientModal  .form-group textarea {
            width: 100%;
            padding: 8px 12px;   /* ↓ smaller padding */
            border-radius: 10px;
            border: 2px solid #c8e1f3;
            font-size: 14px;     /* ↓ slightly smaller text */
            background-color: #fff;
        }

        #patientModal  .form-group input:focus,
        #patientModal  .form-group select:focus,
        #patientModal  .form-group textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        #patientModal  .patient-id {
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid #c8e1f3;
            background-color: #eaf4fb;
            font-weight: 600;
            color: #355f8c;
            min-height: 46px;
        }

        #patientModal  .radio-group {
            display: flex;
            gap: 20px;
            margin-top: 5px;
        }

        #patientModal  .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #patientModal  .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        #patientModal  .register-btn {
            flex: 1;
            border: none;
            padding: 10px 14px;  /* ↓ less vertical padding */
                font-size: 15px;     /* ↓ slightly smaller */
                border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: #fff;
            transition: all 0.3s;
        }

        #patientModal .register-btn:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        #patientModal .cancel-btn {
            flex: 1;
            border: none;
            padding: 10px 14px;  /* ↓ less vertical padding */
            font-size: 15px;     /* ↓ slightly smaller */
            border-radius: 12px;
            font-weight: 600;
            background-color: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
        }

        #patientModal .cancel-btn:hover {
            background-color: #e2e8f0;
            transform: translateY(-2px);
        }

        #patientModal .dob-inputs {
            display: flex;
            gap: 10px;
        }

        #patientModal .dob-inputs input {
            flex: 1;
            text-align: center;
        }
    </style>