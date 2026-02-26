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
                <div class="add-form-group">
                    <label>Patient Name</label>
                    <input list="patientList" id="patientNameInput" placeholder="Type to search..." required>
                    <datalist id="patientList">
                        @foreach ($patients as $p)
                            <option value="{{ $p->full_name }}" data-id="{{ $p->id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="add-form-group">
                    <label>Service</label>
                    <input list="serviceList" id="serviceNameInput" placeholder="Type to search..." required>
                    <datalist id="serviceList">
                        @foreach ($services as $s)
                            <option value="{{ $s->service_name }}" data-id="{{ $s->id }}"
                                data-price="{{ $s->service_fee }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <div class="add-form-group">
                    <label>Speciality (Filter)</label>
                    <input list="specialityList" id="specialityInput" placeholder="Type to filter doctors by speciality..." onchange="filterDoctorsBySpeciality()">
                    <datalist id="specialityList">
                        @foreach ($doctors->unique('speciality') as $doctor)
                            <option value="{{ $doctor->speciality }}">{{ $doctor->speciality }}</option>
                        @endforeach
                    </datalist>
                </div>

                <div class="add-form-group">
                    <label>Doctor</label>
                    <input list="doctorList" id="doctorNameInput" placeholder="Type to search doctor..." onchange="updateSpecialityFromDoctor()" required>
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
                </div>

                <div class="add-form-group">
                    <label>Appointment Date</label>
                    <input type="date" name="appointment_date" required>
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
    bindDatalist('doctorNameInput', 'doctorList', 'doctorIdInput');

            // Store all doctors data for filtering
const doctorsData = [
    @foreach ($doctors as $doctor)
    {
        id: {{ $doctor->id }},
        name: "{{ $doctor->full_name }}",
        speciality: "{{ $doctor->speciality }}"
    },
    @endforeach
];

// Function to filter doctors based on selected speciality
function filterDoctorsBySpeciality() {
    const specialityInput = document.getElementById('specialityInput').value.toLowerCase();
    const doctorDatalist = document.getElementById('doctorList');
    const doctorInput = document.getElementById('doctorNameInput');

    // Clear current doctor selection
    doctorInput.value = '';

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
}

// Function to update speciality when doctor is selected
function updateSpecialityFromDoctor() {
    const doctorInput = document.getElementById('doctorNameInput').value;
    const doctorOptions = document.getElementById('doctorList').options;
    const specialityInput = document.getElementById('specialityInput');

    // Find the selected doctor in the datalist
    for (let i = 0; i < doctorOptions.length; i++) {
            const option = doctorOptions[i];
            if (option.value === doctorInput) {
                const doctorSpeciality = option.getAttribute('data-speciality');
                if (doctorSpeciality) {
                    specialityInput.value = doctorSpeciality;
                }
                break;
            }
        }
    }

    // Add event listener for when doctor is selected via keyboard/click
    document.getElementById('doctorNameInput').addEventListener('input', function(e) {
        // Small delay to allow datalist selection to register
        setTimeout(updateSpecialityFromDoctor, 100);
    });

    // Initialize speciality filter on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initial filter to show all doctors
        filterDoctorsBySpeciality();

        // Add event listener for speciality input changes
        document.getElementById('specialityInput').addEventListener('input', function() {
            filterDoctorsBySpeciality();
        });
    });
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