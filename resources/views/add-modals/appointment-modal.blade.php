<div id="appointmentModal" class="hidden">
    <div class="add-form-card ml-60">
        <div class="add-form-title">Appointment Information</div>

        <form method="POST" action="{{ route('appointments.store') }}">
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

                <!-- Doctor -->
                <div class="add-form-group">
                    <label>Doctor</label>
                    <input list="doctorList" id="doctorNameInput" placeholder="Type doctor name" required>
                    <datalist id="doctorList">
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->full_name }}" data-id="{{ $doctor->id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Service -->
                <div class="add-form-group">
                    <label>Service</label>
                    <input list="serviceList" id="serviceNameInput" placeholder="Type service name" required>
                    <datalist id="serviceList">
                        @foreach ($services as $service)
                            <option value="{{ $service->service_name }}" data-id="{{ $service->id }}"></option>
                        @endforeach
                    </datalist>
                </div>

                <!-- Date -->
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
    function openAddModal() {
        document.getElementById('appointmentModal').classList.remove('hidden');
    }

    function closeAddModal() {
        document.getElementById('appointmentModal').classList.add('hidden');
    }

    // Map datalist selections → hidden IDs
    function bindDatalist(inputId, listId, hiddenId) {
        const input = document.getElementById(inputId);
        const list = document.getElementById(listId);
        const hidden = document.getElementById(hiddenId);

        input.addEventListener('change', () => {
            const option = Array.from(list.options)
                .find(o => o.value === input.value);
            hidden.value = option ? option.dataset.id : '';
        });
    }

    bindDatalist('patientNameInput', 'patientList', 'patientIdInput');
    bindDatalist('doctorNameInput', 'doctorList', 'doctorIdInput');
    bindDatalist('serviceNameInput', 'serviceList', 'serviceIdInput');
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
        position: fixed;
        inset: 0;
        z-index: 50;
        background: rgba(0, 0, 0, .5);
        justify-content: center;
        align-items: center;
        overflow: auto;
        padding: 1rem;
    }

    #appointmentModal .modal-open {
        display: flex !important;
    }

    #appointmentModal .add-form-card {
        max-width: 900px;
        width: 90%;
        background: var(--bg);
        border-radius: 16px;
        padding: 40px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
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
</style>