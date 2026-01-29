<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Appointment Information</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: "Segoe UI", Arial, sans-serif;
        }

        body {
            background-color: #eef7fb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .patient-form-container {
            background-color: #f6fcff;
            width: 800px;
            max-width: 100%;
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

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 2px solid #c8e1f3;
            font-size: 15px;
            outline: none;
            background-color: #ffffff;
        }

        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        }

        /* Button container */
        .button-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .save-btn, .reset-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .save-btn {
            background: linear-gradient(to right, #3b82f6, #2563eb);
            color: white;
        }

        .save-btn:hover {
            background: linear-gradient(to right, #2563eb, #1d4ed8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.3);
        }

        .reset-btn {
            background-color: #f1f5f9;
            color: #64748b;
            border: 2px solid #cbd5e1;
        }

        .reset-btn:hover {
            background-color: #e2e8f0;
            transform: translateY(-2px);
        }

        /* Required field indicator */
        .required::after {
            content: " *";
            color: #ef4444;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .patient-form-container {
                padding: 20px;
            }
            
            .form-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .form-group {
                min-width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="patient-form-container">    
    <!-- Success/Error messages -->
    @if(session('success'))
        <div style="background-color: #d1fae5; border-left: 4px solid #10b981; padding: 12px; margin-bottom: 20px; border-radius: 5px;">
            <strong style="color: #10b981;">Success!</strong>
            <span style="color: #065f46;">{{ session('success') }}</span>
        </div>
    @endif
    
    @if($errors->any())
        <div style="background-color: #fee; border-left: 4px solid #ef4444; padding: 12px; margin-bottom: 20px; border-radius: 5px;">
            <strong style="color: #ef4444;">Please fix the following errors:</strong>
            <ul style="margin: 8px 0 0 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li style="color: #ef4444;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="POST" action="{{ route('submit_appointment') }}" id="appointmentform">
        @csrf
        
        <h1 class="form-title">Appointment Information</h1>
        
        <div class="form-row">
            <div class="form-group">
                <label class="required">Patient Name</label>
                <select name="patient_name" id="patientSelect" required>
                    <option value="" disabled selected>Select Patient</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" data-phone="{{ $patient->phone_number }}">
                            {{ $patient->full_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="required">Doctor Name</label>
                <select name="doctor_name" required>
                    <option value="" disabled selected>Select Doctor</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="required">Service Name</label>
                <select name="service" required>
                    <option value="" disabled selected>Select Service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->service_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label class="required">Appointment Date</label>
                <input type="date" name="appointment_date" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label class="required">Appointment Time</label>
                <input type="time" name="appointment_time" required>
            </div>
            
            <div class="form-group">
                <label class="required">Phone</label>
                <input type="text" name="phone" id="phoneInput" placeholder="Phone will auto-fill when patient is selected" required>
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label>Notes (Optional)</label>
                <textarea name="notes_optional" rows="3" placeholder="Symptoms or Remarks"></textarea>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-container">
            <button type="submit" class="save-btn">Save Appointment</button>
            <button type="button" class="reset-btn">Reset</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const patientSelect = document.getElementById('patientSelect');
    const phoneInput = document.getElementById('phoneInput');
    
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="appointment_date"]').value = today;
    
    // Auto-fill phone when patient is selected
    patientSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const phone = selectedOption.getAttribute('data-phone');
        
        if (phone) {
            phoneInput.value = phone;
        } else {
            phoneInput.value = '';
            phoneInput.placeholder = 'Phone number not available';
        }
    });
    
    // Reset button functionality
    document.querySelector('.reset-btn').addEventListener('click', function() {
        if (confirm('Clear form and go back?')) {
            document.getElementById('appointmentform').reset();
            
            // Reset the phone input separately
            phoneInput.value = '';
            phoneInput.placeholder = 'Phone will auto-fill when patient is selected';
            
            // Reset date to today
            document.querySelector('input[name="appointment_date"]').value = today;
            
            // Redirect to the same page
            window.location.href = '/add_appointment';
        }
    });
});
</script>
</body>
</html>