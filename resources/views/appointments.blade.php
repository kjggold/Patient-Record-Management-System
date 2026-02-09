@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
    <style>
        :root {
            --main: #22d3ee;
            --accent: #0d6efd;
            --bg: #f4f9ff;
            --card: #ffffff;
            --text: #0f172a;
            --danger: #dc2626;
        }

        * {
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif
        }

        body {
            background: var(--bg)
        }

        .app {
            display: flex;
            min-height: 100vh
        }

        main {
            flex: 1;
            padding: 30px
        }

        .responsive-table th,
        .responsive-table td {
            padding: .75rem;
            font-size: 14px;
        }

        .action-btn {
            cursor: pointer;
            font-weight: 600;
        }

        .action-btn.edit {
            color: #0284c7;
            margin-right: 10px
        }

        .action-btn.discharge {
            color: var(--danger)
        }

        #addModal,
        #dischargeModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            background: rgba(0, 0, 0, .5);
            justify-content: center;
            align-items: center;
            overflow: auto;
            padding: 1rem;
        }

        .modal-open {
            display: flex !important;
        }

        .add-form-card {
            max-width: 900px;
            width: 90%;
            background: var(--bg);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .1);
        }

        .add-form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: rgb(98, 191, 249);
        }

        .add-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
        }

        .add-form-group label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #334155;
        }

        .add-form-group input,
        .add-form-group select,
        .add-form-group textarea {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #cfe6ff;
            outline: none;
            font-size: 15px;
            width: 100%;
        }

        .add-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 26px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: .3s;
        }

        .btn-save {
            background: var(--accent);
            color: white;
        }

        .btn-save:hover {
            background: #084298;
        }

        .btn-cancel {
            background: #e5e7eb;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
        }

        #dischargeModal .pos {
            width: 1000px;
            max-width: 95%;
            background: #fff;
            border-radius: 14px;
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            overflow: hidden;
        }

        .pos-left {
            padding: 20px;
            border-right: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .pos-right {
            padding: 20px;
        }

        .pos-left h3,
        .pos-right h3 {
            font-size: 18px;
            font-weight: 700;
            color: #0284c7;
            margin-bottom: 15px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 14px
        }

        .pos-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .pos-table th,
        .pos-table td {
            border: 1px solid #e5e7eb;
            padding: 8px;
            font-size: 13px;
        }

        .pos-table input {
            width: 100%;
            border: none;
            outline: none;
            font-size: 13px;
        }

        .add-service {
            width: 100%;
            padding: 8px;
            background: #0284c7;
            color: #fff;
            border: none;
            border-radius: 6px;
            margin-top: 8px;
        }

        .summary {
            margin-top: 15px;
            font-size: 14px;
        }

        .summary input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }

        .total {
            font-size: 18px;
            font-weight: 700;
            margin-top: 10px;
        }

        .pos-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 15px;
        }

        .pos-actions button {
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }

        .cancel {
            background: #e5e7eb
        }

        .complete {
            background: #0284c7;
            color: #fff
        }
    </style>

    <div class="app">
        @include('layouts.sidebar')

        <main>
            <h1 class="text-2xl font-semibold mb-6">Appointments</h1>

            <div class="flex justify-end mb-4">
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg">+ Add Appointment</button>
            </div>

            <table class="min-w-full responsive-table bg-white rounded-lg">
                <thead class="bg-blue-50">
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($appointments as $a)
                        <tr>
                            <td>{{ $a->id }}</td>
                            <td>{{ $a->patient->full_name }}</td>
                            <td>{{ $a->doctor->full_name }}</td>
                            <td>{{ $a->service->service_name }}</td>
                            <td>{{ $a->appointment_date }}</td>
                            <td>
                                <span class="action-btn discharge"
                                    onclick="openDischarge({{ $a->id }},'{{ $a->patient->full_name }}','{{ $a->doctor->full_name }}','{{ $a->appointment_date }}')">
                                    Discharge
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>
    </div>

    {{-- Add Appointment Modal --}}
    <div id="addModal">
        <div class="add-form-card">
            <div class="add-form-title">Appointment Information</div>
            <form id="addAppointmentForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <div class="add-form-grid">
                    <div class="add-form-group">
                        <label>Patient Name</label>
                        <input list="patientList" id="patientNameInput" placeholder="Type patient name" required>
                        <input type="hidden" name="patient_id" id="patientIdInput">
                        <datalist id="patientList">
                            @foreach ($patients as $patient)
                                <option data-id="{{ $patient->id }}" value="{{ $patient->full_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Doctor</label>
                        <input list="doctorList" id="doctorNameInput" placeholder="Type doctor name" required>
                        <input type="hidden" name="doctor_id" id="doctorIdInput">
                        <datalist id="doctorList">
                            @foreach ($doctors as $doctor)
                                <option data-id="{{ $doctor->id }}" value="{{ $doctor->full_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Service</label>
                        <input list="serviceList" id="serviceNameInput" placeholder="Type service name" required>
                        <input type="hidden" name="service_id" id="serviceIdInput">
                        <datalist id="serviceList">
                            @foreach ($services as $service)
                                <option data-id="{{ $service->id }}" data-price="{{ $service->price }}"
                                    value="{{ $service->service_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="appointment_date" required>
                    </div>

                    <div class="add-form-group">
                        <label>Phone</label>
                        <input type="phone" name="phone" placeholder="Type phone number" required>
                    </div>
                </div>

                <div class="add-form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DISCHARGE POS --}}
    <div id="dischargeModal">
        <div class="pos">
            <div class="pos-left">
                <h3>Patient Info</h3>
                <div class="info-row"><b>ID:</b> <span id="d_id"></span></div>
                <div class="info-row"><b>Name:</b> <span id="d_patient"></span></div>
                <div class="info-row"><b>Doctor:</b> <span id="d_doctor"></span></div>
                <div class="info-row"><b>Date:</b> <span id="d_date"></span></div>
            </div>

            <div class="pos-right">
                <h3>Services</h3>
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="posBody"></tbody>
                </table>

                <button class="add-service" onclick="addRow()">+ Add Service</button>

                <div class="summary">
                    <label>Discount</label>
                    <input type="number" id="discount" value="0" oninput="calc()">

                    <label>Paid</label>
                    <input type="number" id="paid" value="0" oninput="calc()">

                    <div class="total">Total: <span id="total">0</span></div>
                    <div>Balance: <span id="balance">0</span></div>
                </div>

                <div class="pos-actions">
                    <button class="cancel" onclick="closeDischarge()">Cancel</button>
                    <button class="complete" onclick="submitDischarge()">Complete</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        /* -------------------- ADD APPOINTMENT AUTOCOMPLETE -------------------- */

        function bindDatalist(inputId, listId, hiddenId) {
            const input = document.getElementById(inputId);
            const list = document.getElementById(listId);
            const hidden = document.getElementById(hiddenId);

            input.addEventListener('input', () => {
                const val = input.value.trim();
                let found = false;

                for (const opt of list.options) {
                    if (opt.value === val) {
                        hidden.value = opt.dataset.id;
                        found = true;
                        break;
                    }
                }

                if (!found) {
                    hidden.value = '';
                }
            });
        }

        bindDatalist('patientNameInput', 'patientList', 'patientIdInput');
        bindDatalist('doctorNameInput', 'doctorList', 'doctorIdInput');
        bindDatalist('serviceNameInput', 'serviceList', 'serviceIdInput');

        document.getElementById('addAppointmentForm').addEventListener('submit', function(e) {

            if (!patientIdInput.value || !doctorIdInput.value || !serviceIdInput.value) {
                e.preventDefault();
                alert('Please select patient, doctor and service from the list.');
            }
        });

        /* -------------------- MODALS -------------------- */

        let dischargeId = 0;
        let services = [];

        function openAddModal() {
            addModal.classList.add('modal-open')
        }

        function closeAddModal() {
            addModal.classList.remove('modal-open')
        }

        function openDischarge(id, p, d, date) {

            dischargeId = id;

            d_id.innerText = id;
            d_patient.innerText = p;
            d_doctor.innerText = d;
            d_date.innerText = date;

            services = [];
            posBody.innerHTML = '';

            discount.value = 0;
            paid.value = 0;

            addRow();

            dischargeModal.classList.add('modal-open');
        }

        function closeDischarge() {
            dischargeModal.classList.remove('modal-open')
        }

        /* -------------------- MINI POS -------------------- */

        function addRow() {
            services.push({
                name: '',
                price: 0
            });
            render();
        }

        function removeRow(i) {
            services.splice(i, 1);
            render();
        }

        function render() {

            posBody.innerHTML = '';

            services.forEach((s, i) => {

                posBody.innerHTML += `
        <tr>
            <td>
                <input value="${s.name}"
                       oninput="services[${i}].name=this.value">
            </td>
            <td>
                <input type="number" value="${s.price}"
                       oninput="services[${i}].price=+this.value;calc()">
            </td>
            <td>
                <button type="button" onclick="removeRow(${i})">×</button>
            </td>
        </tr>
        `;
            });

            calc();
        }

        function calc() {

            let total = services.reduce((t, s) => t + (Number(s.price) || 0), 0);
            let discountVal = +discount.value || 0;
            let paidVal = +paid.value || 0;

            totalEl = document.getElementById('total');
            balanceEl = document.getElementById('balance');

            totalEl.innerText = total.toFixed(2);
            balanceEl.innerText = (paidVal - (total - discountVal)).toFixed(2);
        }

        /* -------------------- SUBMIT DISCHARGE -------------------- */

        function submitDischarge() {

            if (services.length === 0) {
                alert('Add at least one service.');
                return;
            }

            fetch("{{ route('appointments.completeDischarge') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        appointment_id: dischargeId,
                        services: services,
                        discount: discount.value,
                        paid: paid.value
                    })
                })
                .then(r => r.json())
                .then(res => {
                    location.reload();
                })
                .catch(() => {
                    alert('Discharge failed.');
                });
        }
    </script>
@endsection
