@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
    <style>
        /* ===== YOUR ORIGINAL CSS – UNCHANGED ===== */
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

        #addModal,
        #dischargeModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            justify-content: center;
            align-items: center;
            overflow: auto;
            padding: 1rem;
            background: rgba(0, 0, 0, .5);
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
            display: block;
        }

        .add-form-group input,
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
            color: #fff;
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

        #dischargeModal .modal-container {
            display: flex;
            max-width: 1000px;
            width: 100%;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
        }

        #dischargeModal .left-panel {
            flex: 0 0 30%;
            background: #f0f4f8;
            padding: 20px;
        }

        #dischargeModal .right-panel {
            flex: 1;
            padding: 20px;
            overflow: auto;
        }


        #dischargeModal h3 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #0284c7;
        }

        #dischargeModal table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        #dischargeModal th,
        #dischargeModal td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        #dischargeModal table input {
            width: 100%;
            padding: 4px 6px;
            border-radius: 4px;
            border: 1px solid #cfe6ff;
        }

        #dischargeModal .add-service {
            background: #0d6efd;
            color: #fff;
            width: 100%;
            margin-bottom: 10px;
            border: none;
            padding: 6px;
            border-radius: 6px;
            cursor: pointer;
        }

        #dischargeModal .slip {
            background: #fff;
            border: 1px solid #ddd;
            padding: 10px;
            margin-top: 15px;
            font-size: 14px;
            max-height: 400px;
            overflow-y: auto;
        }

        #dischargeModal .slip h4 {
            margin-bottom: 8px;
            font-weight: 700;
            color: #0284c7;
        }

        #dischargeModal .slip .row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        #dischargeModal .slip .row.total {
            font-weight: 700;
            border-top: 1px dashed #ccc;
            padding-top: 4px;
            margin-top: 4px;
        }

        #notification {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
        }

        #notification div {
            background: #22c55e;
            color: #fff;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .2);
            opacity: 0;
            transition: .5s;
        }
    </style>

    <div class="app flex min-h-screen">
        @include('layouts.sidebar')

        <main class="flex-1 p-6">

            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700">Appointments</h1>
            </div>

            <div class="flex justify-end items-center mb-6 gap-3">
                <input type="text" id="searchInput" placeholder="Search by ID or Patient Name..."
                    class="border rounded px-3 py-2 w-64">

                <button type="button" onclick="openAddModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    +Add Appointment
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 responsive-table">
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
                    <tbody id="appointmentsBody">
                        @foreach ($appointments as $appointment)
                            <tr id="row-{{ $appointment->id }}">
                                <td>{{ $appointment->id }}</td>
                                <td>{{ $appointment->patient->full_name ?? '-' }}</td>
                                <td>{{ $appointment->doctor->full_name ?? '-' }}</td>
                                <td>{{ $appointment->service->service_name ?? '-' }}</td>
                                <td>{{ $appointment->appointment_date }}</td>
                                <td class="text-center">
                                    <span class="action-btn edit">Edit</span>
                                    <span class="action-btn discharge"
                                        onclick="openDischargeModal(
                                    '{{ $appointment->id }}',
                                    '{{ $appointment->patient->full_name ?? '' }}',
                                    '{{ $appointment->doctor->full_name ?? '' }}',
                                    '{{ $appointment->service->service_name ?? '' }}',
                                    '{{ $appointment->appointment_date }}',
                                    '{{ $appointment->service->service_fee ?? 0 }}',
                                    '{{ $appointment->doctor->consultation_fee ?? 0 }}'
                                  )">Discharge</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </main>
    </div>

    <div id="notification">
        <div id="notificationMessage"></div>
    </div>

    {{-- ================= ADD MODAL ================= --}}
    <div id="addModal">
        <div class="add-form-card">
            <div class="add-form-title">Appointment Information</div>

            <form id="addAppointmentForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf

                <div class="add-form-grid">

                    <div class="add-form-group">
                        <label>Patient Name</label>
                        <input list="patientList" id="patientNameInput" required>
                        <datalist id="patientList">
                            @foreach ($patients as $p)
                                <option value="{{ $p->full_name }}" data-id="{{ $p->id }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Doctor</label>
                        <input list="doctorList" id="doctorNameInput" required>
                        <datalist id="doctorList">
                            @foreach ($doctors as $d)
                                <option value="{{ $d->full_name }}" data-id="{{ $d->id }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Service</label>
                        <input list="serviceList" id="serviceNameInput" required>
                        <datalist id="serviceList">
                            @foreach ($services as $s)
                                <option value="{{ $s->service_name }}" data-id="{{ $s->id }}"
                                    data-price="{{ $s->service_fee }}"></option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="add-form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="appointment_date" required>
                    </div>

                </div>

                <div class="add-form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= DISCHARGE MODAL ================= --}}
    <div id="dischargeModal">
        <div class="modal-container">

            <div class="left-panel">
                <h3>Patient Info</h3>
                <p><strong>Appointment ID:</strong> <span id="dischargeAppointmentIdText"></span></p>
                <p><strong>Patient Name:</strong> <span id="dischargePatientName"></span></p>
                <p><strong>Doctor Name:</strong> <span id="dischargeDoctorName"></span></p>
                <p><strong>Service:</strong> <span id="dischargeServiceName"></span></p>
                <p><strong>Date:</strong> <span id="dischargeDate"></span></p>
            </div>

            <div class="right-panel">

                <h3>Bills</h3>

                <form id="dischargeForm">
                    <input type="hidden" id="dischargeAppointmentId">

                    <table id="serviceTable">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                    <button type="button" class="add-service" onclick="addServiceRow()">+ Add Service</button>

                    <div class="add-form-grid">
                        <div class="add-form-group">
                            <label>Discount</label>
                            <input type="number" id="dischargeDiscount" value="0">
                        </div>

                        <div class="add-form-group">
                            <label>Paid</label>
                            <input type="number" id="dischargePaid" value="0">
                        </div>

                        <div class="add-form-group">
                            <label>Balance / Change</label>
                            <input type="number" id="dischargeBalance" readonly>
                        </div>

                        <div class="add-form-group">
                            <label>Comment</label>
                            <textarea id="dischargeComment"></textarea>
                        </div>
                    </div>

                    <div class="slip">
                        <h4>Receipt / Slip</h4>
                        <div class="row total"><span>Total:</span><span id="slipTotal">0</span></div>
                        <div class="row"><span>Discount:</span><span id="slipDiscount">0</span></div>
                        <div class="row total"><span>Payable:</span><span id="slipPayable">0</span></div>
                        <div class="row"><span>Paid:</span><span id="slipPaid">0</span></div>
                        <div class="row total"><span>Balance / Change:</span><span id="slipBalance">0</span></div>
                    </div>

                    <div style="margin-top:10px;display:flex;justify-content:flex-end;gap:14px;">
                        <button type="button" class="btn btn-cancel" onclick="closeDischargeModal()">Cancel</button>
                        <button type="button" class="btn btn-save" onclick="completeDischarge()">Complete
                            Discharge</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <datalist id="posServiceList">
        @foreach ($services as $s)
            <option value="{{ $s->service_name }}" data-price="{{ $s->service_fee }}"></option>
        @endforeach
    </datalist>

    <script>
        const dischargeModal = document.getElementById('dischargeModal');
        const serviceTableBody = document.querySelector('#serviceTable tbody');

        const dischargeAppointmentId = document.getElementById('dischargeAppointmentId');
        const dischargeAppointmentIdText = document.getElementById('dischargeAppointmentIdText');
        const dischargePatientName = document.getElementById('dischargePatientName');
        const dischargeDoctorName = document.getElementById('dischargeDoctorName');
        const dischargeServiceName = document.getElementById('dischargeServiceName');
        const dischargeDate = document.getElementById('dischargeDate');

        const dischargeDiscount = document.getElementById('dischargeDiscount');
        const dischargePaid = document.getElementById('dischargePaid');
        const dischargeBalance = document.getElementById('dischargeBalance');
        const dischargeComment = document.getElementById('dischargeComment');

        const slipTotal = document.getElementById('slipTotal');
        const slipDiscount = document.getElementById('slipDiscount');
        const slipPayable = document.getElementById('slipPayable');
        const slipPaid = document.getElementById('slipPaid');
        const slipBalance = document.getElementById('slipBalance');

        const notificationMessage = document.getElementById('notificationMessage');
        const searchInput = document.getElementById('searchInput');

        function openAddModal() {
            addModal.classList.add('modal-open');
        }

        function closeAddModal() {
            addModal.classList.remove('modal-open');
        }

        /* ================= DISCHARGE ================= */

        function openDischargeModal(id, patient, doctor, service, date, servicePrice, consultationFee) {

            dischargeModal.style.display = 'flex';

            dischargeAppointmentId.value = id;
            dischargeAppointmentIdText.textContent = id;
            dischargePatientName.textContent = patient;
            dischargeDoctorName.textContent = doctor;
            dischargeServiceName.textContent = service;
            dischargeDate.textContent = date;

            serviceTableBody.innerHTML = '';

            addServiceRow('Booking Fee', 3000);
            addServiceRow(service, parseFloat(servicePrice) || 0);
            addServiceRow('Consultation Fee', parseFloat(consultationFee) || 0);

            dischargeDiscount.value = 0;
            dischargePaid.value = 0;
            dischargeComment.value = '';

            updateTotals();
        }

        function closeDischargeModal() {
            dischargeModal.style.display = 'none';
        }

        function addServiceRow(name = '', price = 0) {

            const row = document.createElement('tr');
            row.innerHTML = `
        <td><input type="text" list="posServiceList" value="${name}"></td>
        <td><input type="number" value="${price}" min="0"></td>
        <td>
            <button type="button"
                onclick="this.closest('tr').remove();updateTotals();"
                style="background:red;color:white;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;">
                Remove
            </button>
        </td>`;

            serviceTableBody.appendChild(row);

            const nameInput = row.querySelector('input[type=text]');
            const priceInput = row.querySelector('input[type=number]');

            nameInput.addEventListener('change', function() {
                if (this.value === 'Booking Fee' || this.value === 'Consultation Fee') return;
                const opt = [...document.getElementById('posServiceList').options]
                    .find(o => o.value === this.value);
                if (opt) {
                    priceInput.value = parseFloat(opt.dataset.price || 0);
                    updateTotals();
                }
            });

            priceInput.addEventListener('input', updateTotals);
        }

        function updateTotals() {

            const prices = [...serviceTableBody.querySelectorAll('input[type=number]')]
                .map(i => parseFloat(i.value) || 0);

            const total = prices.reduce((a, b) => a + b, 0);
            const discount = parseFloat(dischargeDiscount.value) || 0;
            const payable = total - discount;
            const paid = parseFloat(dischargePaid.value) || 0;
            const balance = paid - payable;

            slipTotal.textContent = total.toLocaleString();
            slipDiscount.textContent = discount.toLocaleString();
            slipPayable.textContent = payable.toLocaleString();
            slipPaid.textContent = paid.toLocaleString();
            slipBalance.textContent = balance.toLocaleString();
            dischargeBalance.value = balance;
        }

        dischargeDiscount.addEventListener('input', updateTotals);
        dischargePaid.addEventListener('input', updateTotals);

        async function completeDischarge() {

            const data = {
                appointment_id: dischargeAppointmentId.value,
                services: [...serviceTableBody.querySelectorAll('input[type=text]')].map(i => i.value),
                price: [...serviceTableBody.querySelectorAll('input[type=number]')].map(i => parseFloat(i.value) ||
                    0),
                discount: parseFloat(dischargeDiscount.value) || 0,
                paid: parseFloat(dischargePaid.value) || 0,
                balance: parseFloat(dischargeBalance.value) || 0,
                comment: dischargeComment.value
            };

            const res = await fetch('{{ route('discharge.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (result.success) {
                showNotification('✅ Discharge completed!');
                const row = document.getElementById('row-' + data.appointment_id);
                if (row) row.remove();
                closeDischargeModal();
            }
        }

        function showNotification(msg) {
            notificationMessage.textContent = msg;
            notificationMessage.style.opacity = 1;
            setTimeout(() => notificationMessage.style.opacity = 0, 2000);
        }

        /* ================= ADD APPOINTMENT ================= */

        document.getElementById('addAppointmentForm').addEventListener('submit', async function(e) {

            e.preventDefault();

            const patientName = patientNameInput.value;
            const doctorName = doctorNameInput.value;
            const serviceName = serviceNameInput.value;

            const p = [...patientList.options].find(o => o.value === patientName);
            const d = [...doctorList.options].find(o => o.value === doctorName);
            const s = [...serviceList.options].find(o => o.value === serviceName);

            if (!p || !d || !s) {
                showNotification('❌ Please select valid Patient, Doctor, and Service.');
                return;
            }

            const fd = new FormData();
            fd.append('patient_id', p.dataset.id);
            fd.append('doctor_id', d.dataset.id);
            fd.append('service_id', s.dataset.id);
            fd.append('appointment_date', this.querySelector('[name=appointment_date]').value);
            fd.append('_token', '{{ csrf_token() }}');

            const res = await fetch(this.action, {
                method: 'POST',
                body: fd
            });
            const result = await res.json();

            if (result.success) {

                const a = result.appointment;

                const tr = document.createElement('tr');
                tr.id = 'row-' + a.id;

                tr.innerHTML = `
            <td>${a.id}</td>
            <td>${a.patient_name}</td>
            <td>${a.doctor_name}</td>
            <td>${a.service_name}</td>
            <td>${a.appointment_date}</td>
            <td class="text-center">
                <span class="action-btn edit">Edit</span>
                <span class="action-btn discharge"
                  onclick="openDischargeModal(
                    '${a.id}',
                    '${a.patient_name}',
                    '${a.doctor_name}',
                    '${a.service_name}',
                    '${a.appointment_date}',
                    ${a.service_fee ?? 0},
                    ${a.consultation_fee ?? 0}
                  )">Discharge</span>
            </td>`;

                appointmentsBody.appendChild(tr);

                this.reset();
                closeAddModal();
                showNotification('✅ Appointment added!');
            }
        });

        /* ================= SEARCH ================= */

        searchInput.addEventListener('input', async function() {

            const q = this.value.trim();

            const res = await fetch('/appointments/search?q=' + encodeURIComponent(q), {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const rows = await res.json();

            const tbody = appointmentsBody;
            tbody.innerHTML = '';

            rows.forEach(a => {

                const tr = document.createElement('tr');
                tr.id = 'row-' + a.id;

                tr.innerHTML = `
            <td>${a.id}</td>
            <td>${a.patient_name}</td>
            <td>${a.doctor_name}</td>
            <td>${a.service_name}</td>
            <td>${a.appointment_date}</td>
            <td class="text-center">
                <span class="action-btn edit">Edit</span>
                <span class="action-btn discharge"
                  onclick="openDischargeModal(
                    '${a.id}',
                    '${a.patient_name}',
                    '${a.doctor_name}',
                    '${a.service_name}',
                    '${a.appointment_date}',
                    ${a.service_fee ?? 0},
                    ${a.consultation_fee ?? 0}
                  )">Discharge</span>
            </td>`;

                tbody.appendChild(tr);
            });
        });
    </script>
@endsection
