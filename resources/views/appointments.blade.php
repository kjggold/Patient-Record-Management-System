@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
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
        #addModal {
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

        /* Discharge Modal */
        #dischargeModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 60;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            padding: 1rem;
            overflow: auto;
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

        #dischargeModal .left-panel h3,
        #dischargeModal .right-panel h3 {
            font-weight: 700;
            margin-bottom: 10px;
            color: #0284c7;
        }

        #dischargeModal .left-panel p {
            margin: 6px 0;
            font-weight: 500;
        }

        #dischargeModal table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        #dischargeModal table th,
        #dischargeModal table td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
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
    </style>

    <div class="app flex min-h-screen">
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700">Appointments</h1>
            </div>

            <div class="flex justify-end items-center mb-6 gap-3">
                <input type="text" placeholder="Search by id..." class="border rounded px-3 py-2 w-64">
                <button type="button" onclick="openAddModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">+Add Appointment</button>
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
                                        onclick="openDischargeModal('{{ $appointment->id }}','{{ $appointment->patient->full_name ?? '' }}','{{ $appointment->doctor->full_name ?? '' }}','{{ $appointment->service->service_name ?? '' }}','{{ $appointment->appointment_date }}','{{ $appointment->service->price ?? 0 }}')">Discharge</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
                        <input list="patientList" id="patientNameInput" placeholder="Type patient name">
                        <input type="hidden" name="patient_id" id="patientIdInput">
                        <datalist id="patientList">
                            @foreach ($patients as $patient)
                                <option data-id="{{ $patient->id }}" value="{{ $patient->full_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="add-form-group">
                        <label>Doctor</label>
                        <input list="doctorList" id="doctorNameInput" placeholder="Type doctor name">
                        <input type="hidden" name="doctor_id" id="doctorIdInput">
                        <datalist id="doctorList">
                            @foreach ($doctors as $doctor)
                                <option data-id="{{ $doctor->id }}" value="{{ $doctor->full_name }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="add-form-group">
                        <label>Service</label>
                        <input list="serviceList" id="serviceNameInput" placeholder="Type service name">
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

                </div>
                <div class="add-form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Discharge Modal --}}
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
                <h3>Mini POS / Services</h3>
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
                            <input type="number" id="dischargeDiscount" value="0" min="0">
                        </div>
                        <div class="add-form-group">
                            <label>Paid</label>
                            <input type="number" id="dischargePaid" value="0" min="0">
                        </div>
                        <div class="add-form-group">
                            <label>Balance / Change</label>
                            <input type="number" id="dischargeBalance" readonly>
                        </div>
                    </div>

                    <div class="slip">
                        <h4>Receipt / Slip</h4>
                        <div id="slipItems"></div>
                        <div class="row total"><span>Total:</span> <span id="slipTotal">0.00</span></div>
                        <div class="row"><span>Discount:</span> <span id="slipDiscount">0.00</span></div>
                        <div class="row total"><span>Payable:</span> <span id="slipPayable">0.00</span></div>
                        <div class="row"><span>Paid:</span> <span id="slipPaid">0.00</span></div>
                        <div class="row total"><span>Balance / Change:</span> <span id="slipBalance">0.00</span></div>
                    </div>

                    <div style="margin-top:10px; display:flex; justify-content:flex-end; gap:14px;">
                        <button type="button" class="btn btn-cancel" onclick="closeDischargeModal()">Cancel</button>
                        <button type="button" class="btn btn-save" onclick="completeDischarge()">Complete
                            Discharge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- POS datalist --}}
    <datalist id="posServiceList">
        @foreach ($services as $service)
            <option value="{{ $service->service_name }}" data-price="{{ $service->price }}"></option>
        @endforeach
    </datalist>

    <script>
        const dischargeModal = document.getElementById('dischargeModal');
        const dischargeAppointmentId = document.getElementById('dischargeAppointmentId');
        const dischargeAppointmentIdText = document.getElementById('dischargeAppointmentIdText');
        const dischargePatientName = document.getElementById('dischargePatientName');
        const dischargeDoctorName = document.getElementById('dischargeDoctorName');
        const dischargeServiceName = document.getElementById('dischargeServiceName');
        const dischargeDate = document.getElementById('dischargeDate');
        const serviceTableBody = document.querySelector('#serviceTable tbody');
        const dischargeDiscount = document.getElementById('dischargeDiscount');
        const dischargePaid = document.getElementById('dischargePaid');
        const dischargeBalance = document.getElementById('dischargeBalance');
        const slipTotal = document.getElementById('slipTotal');
        const slipDiscount = document.getElementById('slipDiscount');
        const slipPayable = document.getElementById('slipPayable');
        const slipPaid = document.getElementById('slipPaid');
        const slipBalance = document.getElementById('slipBalance');

        const dischargeBody = document.getElementById('dischargeBody');
        const statTotal = document.getElementById('statTotal');
        const statRevenue = document.getElementById('statRevenue');

        function openAddModal() {
            document.getElementById('addModal').classList.add('modal-open');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('modal-open');
        }

        function openDischargeModal(id, patient, doctor, service, date, price) {
            dischargeModal.style.display = 'flex';

            dischargeAppointmentId.value = id;
            dischargeAppointmentIdText.textContent = id;

            dischargePatientName.textContent = patient;
            dischargeDoctorName.textContent = doctor;
            dischargeServiceName.textContent = service;
            dischargeDate.textContent = date;

            serviceTableBody.innerHTML = '';
            addServiceRow(service, price);

            dischargeDiscount.value = 0;
            dischargePaid.value = 0;

            updateTotals();
        }

        function closeDischargeModal() {
            dischargeModal.style.display = 'none';
        }

        function bindServiceAutoPrice(input) {
            input.addEventListener('change', function() {
                const list = document.getElementById('posServiceList');
                const option = Array.from(list.options).find(o => o.value === this.value);
                if (option) {
                    this.closest('tr').querySelector('input[name="price[]"]').value =
                        option.dataset.price || 0;
                    updateTotals();
                }
            });
        }

        function addServiceRow(name = '', price = 0) {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>
                <input type="text" name="services[]" class="serviceInput"
                       list="posServiceList" value="${name}">
            </td>
            <td>
                <input type="number" name="price[]" value="${price}" min="0"
                       oninput="updateTotals()">
            </td>
            <td>
                <button type="button"
                        onclick="this.closest('tr').remove();updateTotals();"
                        style="background:red;color:white;border:none;padding:2px 6px;border-radius:4px;cursor:pointer;">x</button>
            </td>
        `;
            serviceTableBody.appendChild(row);
            bindServiceAutoPrice(row.querySelector('.serviceInput'));
        }

        function updateTotals() {
            let total = 0;
            serviceTableBody.querySelectorAll('tr').forEach(row => {
                total += parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
            });

            const discount = parseFloat(dischargeDiscount.value) || 0;
            const paid = parseFloat(dischargePaid.value) || 0;
            const payable = total - discount;
            const balance = paid - payable;

            slipTotal.textContent = total.toFixed(2);
            slipDiscount.textContent = discount.toFixed(2);
            slipPayable.textContent = payable.toFixed(2);
            slipPaid.textContent = paid.toFixed(2);
            slipBalance.textContent = balance.toFixed(2);

            dischargeBalance.value = balance.toFixed(2);
        }

        dischargeDiscount.addEventListener('input', updateTotals);
        dischargePaid.addEventListener('input', updateTotals);

        function completeDischarge() {
            const id = dischargeAppointmentId.value;

            const services = [];
            serviceTableBody.querySelectorAll('tr').forEach(row => {
                services.push({
                    name: row.querySelector('input[name="services[]"]').value,
                    price: parseFloat(row.querySelector('input[name="price[]"]').value) || 0
                });
            });

            const total = parseFloat(slipTotal.textContent) || 0;
            const discount = parseFloat(dischargeDiscount.value) || 0;
            const paid = parseFloat(dischargePaid.value) || 0;
            const balance = parseFloat(dischargeBalance.value) || 0;

            fetch(`{{ url('/appointments') }}/${id}/discharge`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        services,
                        total,
                        discount,
                        paid,
                        balance
                    })
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok || !data.success) throw new Error(data.message || 'Error during discharge.');

                    // Remove appointment row instantly
                    document.getElementById(`row-${id}`).remove();

                    // Close modal
                    closeDischargeModal();

                    // Append new discharge row directly
                    const newRow = document.createElement('tr');
                    newRow.id = `discharge_${id}`;
                    let servicesHtml = '';
                    services.forEach(s => {
                        servicesHtml += `${s.name} - ${s.price}<br>`;
                    });

                    const payableBalance = paid - (total - discount);
                    const now = new Date().toISOString().slice(0, 19).replace('T', ' ');

                    newRow.innerHTML = `
                <td>${id}</td>
                <td>${dischargePatientName.textContent}</td>
                <td>
                    <span class="service-preview" onclick="toggleService('srv_${id}')">View services</span>
                    <div id="srv_${id}" class="service-box">${servicesHtml}</div>
                </td>
                <td>${total.toFixed(2)}</td>
                <td>${discount.toFixed(2)}</td>
                <td>${paid.toFixed(2)}</td>
                <td>${payableBalance.toFixed(2)}</td>
                <td>${now}</td>
                <td><button class="action-btn" onclick="printSingle(${id})">Print</button></td>
            `;
                    dischargeBody.prepend(newRow);

                    // Update stats
                    recalcStats();
                })
                .catch(err => alert(err.message));
        }

        function recalcStats() {
            let totalDischarges = 0;
            let revenue = 0;

            dischargeBody.querySelectorAll('tr').forEach(row => {
                if (row.style.display !== 'none') {
                    totalDischarges++;
                    const total = parseFloat(row.cells[3].innerText) || 0;
                    const discount = parseFloat(row.cells[4].innerText) || 0;
                    revenue += total - discount;
                }
            });

            statTotal.innerText = totalDischarges;
            statRevenue.innerText = revenue.toFixed(2);
        }

        window.toggleService = function(id) {
            const el = document.getElementById(id);
            el.style.display = (el.style.display === '' || el.style.display === 'none') ? 'block' : 'none';
        }

        window.printSingle = function(id) {
            let tr = document.getElementById(`discharge_${id}`);
            if (!tr) return;

            let html = `<h2>Discharge Receipt</h2>`;
            html += `<p><b>Appointment:</b> ${tr.cells[0].innerText}</p>`;
            html += `<p><b>Patient:</b> ${tr.cells[1].innerText}</p><hr>`;
            const servicesBox = tr.querySelector('.service-box');
            if (servicesBox) html += `<p>${servicesBox.innerHTML}</p>`;
            html += `<hr><p>Total: ${tr.cells[3].innerText}</p>`;
            html += `<p>Discount: ${tr.cells[4].innerText}</p>`;
            html += `<p>Paid: ${tr.cells[5].innerText}</p>`;
            html += `<p>Change / Due: ${tr.cells[6].innerText}</p>`;
            html += `<p style="margin-top:12px;">${tr.cells[7].innerText}</p>`;

            const w = window.open('', '_blank');
            w.document.write(
                `<html><head><title>Receipt</title><style>body{font-family:Arial;padding:20px;}</style></head><body>${html}<script>window.print();<\/script></body></html>`
            );
            w.document.close();
        }

        // Datalist -> hidden id
        document.querySelectorAll('#patientNameInput, #doctorNameInput, #serviceNameInput')
            .forEach(input => {
                input.addEventListener('input', function() {
                    const list = document.getElementById(this.list.id);
                    const option = Array.from(list.options).find(o => o.value === this.value);
                    if (option) this.nextElementSibling.value = option.dataset.id || '';
                });
            });
    </script>


@endsection
