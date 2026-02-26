<div id="appointmentModal" class="hidden">
    <div class="add-form-card ml-60">
        <div class="add-form-title">Appointment Information</div>

        <form method="POST" action="{{ route('appointments.store_dashboard') }}">
            @csrf

            <!-- Hidden IDs (these are what Laravel receives) -->
            <input type="hidden" name="patient_id" id="patientIdInput">
            <input type="hidden" name="doctor_id" id="doctorIdInput">
            <input type="hidden" name="service_id" id="serviceIdInput">

            <div class="add-form-grid">

                <!-- Patient -->
                <div class="add-form-group">
                    <label>Patient Name</label>
                    <input list="patientList" id="patientNameInput" placeholder="Type patient name" required>
                    <datalist id="patientList">
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->full_name }}" data-id="{{ $patient->id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Service -->
                <div class="add-form-group">
                    <label>Service</label>
                    <input list="serviceList" id="serviceNameInput" placeholder="Type to search..." required>
                    <datalist id="serviceList">
                        @foreach ($services as $s)
                            <option value="{{ $s->service_name }}" data-id="{{ $s->id }}" data-price="{{ $s->service_fee }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Speciality -->
                <div class="add-form-group">
                    <label>Speciality</label>
                    <select id="specialitySelect" class="w-full p-3 rounded-lg border border-gray-300 bg-white" onchange="filterDoctorsBySpeciality()">
                        <option value="">Select Speciality</option>
                        @php
                            $uniqueSpecialities = $doctors->pluck('speciality')->unique()->sort();
                        @endphp
                        @foreach ($uniqueSpecialities as $speciality)
                            <option value="{{ $speciality }}">{{ $speciality }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor -->
                <div class="add-form-group">
                    <label>Doctor</label>
                    <select id="doctorSelect" class="w-full p-3 rounded-lg border border-gray-300 bg-white" onchange="updateDoctorId()" required>
                        <option value="">Select Doctor</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}" data-speciality="{{ $doctor->speciality }}">{{ $doctor->full_name }} - {{ $doctor->speciality }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date -->
                <div class="add-form-group">
                    <label>Appointment Date</label>
                    <input type="date" name="appointment_date" id="appointmentDate" required>
                </div>

            </div>

            <div class="add-form-actions">
                <button type="button" class="btn btn-cancel" onclick="closeAppointmentModal()">Cancel</button>
                <button type="submit" class="btn btn-save">Save Appointment</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Make sure modal is hidden initially
    document.addEventListener('DOMContentLoaded', function() {
        closeAppointmentModal();
    });

    function openAppointmentModal() {
        document.getElementById('appointmentModal').classList.remove('hidden');
        document.getElementById('appointmentModal').style.display = 'flex';
    }

    function closeAppointmentModal() {
        document.getElementById('appointmentModal').classList.add('hidden');
        document.getElementById('appointmentModal').style.display = 'none';
    }
    
    // document.getElementById('addAppointmentForm').addEventListener('submit', async function(e) {
//     e.preventDefault();

//     // Get the input values
//     const patientNameInput = document.getElementById('patientNameInput').value;
//     const doctorSelect = document.getElementById('doctorSelect');
//     const serviceNameInput = document.getElementById('serviceNameInput').value;
//     const appointmentDate = this.querySelector('[name=appointment_date]').value;

//     // Get doctor ID from select
//     const doctorId = doctorSelect.value;
    
//     // Find patient and service from datalists
//     const patientOptions = [...document.getElementById('patientList').options];
//     const serviceOptions = [...document.getElementById('serviceList').options];
    
//     const selectedPatient = patientOptions.find(o => o.value === patientNameInput);
//     const selectedService = serviceOptions.find(o => o.value === serviceNameInput);

//     // Validate selections
//     if (!selectedPatient) {
//         showNotification('❌ Please select a valid Patient from the list.');
//         return;
//     }
    
//     if (!doctorId) {
//         showNotification('❌ Please select a Doctor.');
//         return;
//     }
    
//     if (!selectedService) {
//         showNotification('❌ Please select a valid Service from the list.');
//         return;
//     }
    
//     if (!appointmentDate) {
//         showNotification('❌ Please select an Appointment Date.');
//         return;
//     }

//     // Create FormData and append values
//     const fd = new FormData();
//     fd.append('patient_id', selectedPatient.dataset.id);
//     fd.append('doctor_id', doctorId);
//     fd.append('service_id', selectedService.dataset.id);
//     fd.append('appointment_date', appointmentDate);
//     fd.append('_token', csrfToken);

//     try {
//         // Show loading state
//         const submitBtn = this.querySelector('button[type="submit"]');
//         const originalText = submitBtn.textContent;
//         submitBtn.textContent = 'Saving...';
//         submitBtn.disabled = true;

//         const res = await fetch(this.action, {
//             method: 'POST',
//             body: fd,
//             headers: {
//                 'X-Requested-With': 'XMLHttpRequest',
//                 'Accept': 'application/json'
//             }
//         });
        
//         const result = await res.json();

//         if (result.success) {
//             this.reset();
//             closeAppointmentModal();
//             showNotification('✅ Appointment added successfully!');
            
//             // 🟢 ADD THIS LINE - Redirect to appointments page
//             window.location.href = '{{ route("appointments.index") }}';
            
//         } else {
//             showNotification('❌ Error: ' + (result.message || 'Failed to add appointment'));
//         }
//     } catch (error) {
//         console.error('Error:', error);
//         showNotification('❌ Network error. Please try again.');
//     } finally {
//         // Reset button state
//         const submitBtn = this.querySelector('button[type="submit"]');
//         submitBtn.textContent = 'Save Appointment';
//         submitBtn.disabled = false;
//     }
// });

    // Map datalist selections → hidden IDs (for patient and service only)
    function bindDatalist(inputId, listId, hiddenId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);

        if (!input || !list || !hidden) return;

        input.addEventListener('change', () => {
            const option = Array.from(list.options)
                .find(o => o.value === input.value);
            hidden.value = option ? option.dataset.id : '';
            console.log(`${hiddenId} set to:`, hidden.value);
        });

        // Also handle input event to clear if typing
        input.addEventListener('input', () => {
            const option = Array.from(list.options)
                .find(o => o.value === input.value);
            if (!option) {
                hidden.value = '';
            }
        });
    }

    // Bind patient and service datalists (doctor is handled separately)
    bindDatalist('patientNameInput', 'patientList', 'patientIdInput');
    bindDatalist('serviceNameInput', 'serviceList', 'serviceIdInput');

    // Handle doctor selection
    function updateDoctorId() {
        const doctorSelect = document.getElementById('doctorSelect');
        const doctorIdInput = document.getElementById('doctorIdInput');
        const specialitySelect = document.getElementById('specialitySelect');
        
        if (doctorSelect.value) {
            doctorIdInput.value = doctorSelect.value;
            
            // Update speciality based on selected doctor
            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            const doctorSpeciality = selectedOption.getAttribute('data-speciality');
            
            // Find and select matching speciality
            for (let i = 0; i < specialitySelect.options.length; i++) {
                if (specialitySelect.options[i].value === doctorSpeciality) {
                    specialitySelect.selectedIndex = i;
                    break;
                }
            }
        } else {
            doctorIdInput.value = '';
        }
        console.log('doctorIdInput set to:', doctorIdInput.value);
    }

    function filterDoctorsBySpeciality() {
        const speciality = document.getElementById('specialitySelect').value;
        const doctorSelect = document.getElementById('doctorSelect');
        const options = doctorSelect.options;
        const doctorIdInput = document.getElementById('doctorIdInput');
        
        // Show all options if no speciality selected
        if (!speciality) {
            for (let i = 0; i < options.length; i++) {
                options[i].style.display = '';
                options[i].disabled = false;
            }
            doctorSelect.value = '';
            doctorIdInput.value = '';
            return;
        }
        
        // Filter doctors by speciality
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            if (option.value === '') continue;
            
            const doctorSpeciality = option.getAttribute('data-speciality');
            if (doctorSpeciality && doctorSpeciality === speciality) {
                option.style.display = '';
                option.disabled = false;
            } else {
                option.style.display = 'none';
                option.disabled = true;
            }
        }
        
        doctorSelect.value = '';
        doctorIdInput.value = '';
    }
</script>

<style>
    /* Keep all your existing styles exactly as they were */
    :root {
        --main: #22d3ee;
        --accent: #0d6efd;
        --bg: #f4f9ff;
        --card: #ffffff;
        --text: #0f172a;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", sans-serif;
    }

    body {
        background: var(--bg);
    }

    main {
        flex: 1;
        padding: 30px;
    }

    .overflow-x-auto {
        overflow-x: auto;
    }

    .responsive-table th,
    .responsive-table td {
        padding: .75rem;
        text-align: left;
        font-size: 14px;
    }

    .action-btn {
        cursor: pointer;
        font-weight: 500;
    }

    .action-btn.edit {
        color: #0284c7;
    }

    .action-btn.discharge {
        color: #dc2626;
    }

    /* Add Modal */
    #appointmentModal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        overflow: auto;
        padding: 1rem;
    }

    #appointmentModal:not(.hidden) {
        display: flex !important;
    }

    #appointmentModal .add-form-card {
        max-width: 900px;
        width: 90%;
        background: var(--bg);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        position: relative;
        margin: 0 auto;
    }

    #appointmentModal .add-form-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 25px;
        color: rgb(98, 191, 249);
    }

    #appointmentModal .add-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
    }

    #appointmentModal .add-form-group label {
        font-size: 16px;
        margin-bottom: 8px;
        color: #334155;
        display: block;
    }

    #appointmentModal .add-form-group input,
    #appointmentModal .add-form-group select,
    #appointmentModal .add-form-group textarea {
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid #cfe6ff;
        outline: none;
        font-size: 15px;
        width: 100%;
        background: white;
    }

    #appointmentModal .add-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 14px;
        margin-top: 30px;
    }

    #appointmentModal .btn {
        padding: 12px 26px;
        border: none;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: .3s;
    }

    #appointmentModal .btn-save {
        background: var(--accent);
        color: white;
    }

    #appointmentModal .btn-save:hover {
        background: #084298;
    }

    #appointmentModal .btn-cancel {
        background: #e5e7eb;
    }

    #appointmentModal .btn-cancel:hover {
        background: #cbd5e1;
    }

    /* Hidden class */
    .hidden {
        display: none !important;
    }
</style>