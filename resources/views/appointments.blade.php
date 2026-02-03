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

        .app {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 250px;
            background: linear-gradient(180deg, #d8eaf8);
            padding: 24px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 2rem;
        }

        nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 12px;
            border-radius: 12px;
            font-size: 1rem;
            text-decoration: none;
            color: black;
            font-weight: 500;
            transition: 0.2s;
        }

        nav a:hover,
        nav a.active {
            background: rgba(255, 255, 255, 0.25);
        }

        .logout {
            margin-top: auto;
            color: #ef4444;
        }

        /* Main */
        main {
            flex: 1;
            padding: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 20px;
        }

        /* +Add Appointment Modal */
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
            /* increased width for bigger form */
            width: 90%;
            background: var(--bg);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
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

        .add-form-group {
            display: flex;
            flex-direction: column;
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

        textarea {
            resize: none;
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
            transition: 0.3s;
        }

        .btn-save {
            background: var(--accent);
            color: white;
        }

        .btn-save:hover {
            background: #084298;
        }

        .btn-reset,
        .btn-cancel {
            background: #e5e7eb;
        }

        .btn-reset:hover,
        .btn-cancel:hover {
            background: #cbd5e1;
        }

        /* Table */
        .overflow-x-auto {
            overflow-x: auto;
        }

        .responsive-table th,
        .responsive-table td {
            padding: 0.75rem;
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

        /* Discharge Modal Styles */
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

        #dischargeModal .modal-container {
            display: flex;
            max-width: 95%;
            width: 100%;
            background: #f4f9ff;
            border-radius: .5rem;
            overflow: hidden;
        }

        #dischargeModal .panel {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            max-height: 80vh;
        }

        #dischargeModal .left-panel {
            background: #fff;
            border-right: 1px solid #ddd;
        }

        #dischargeModal .right-panel {
            background: #f9fafb;
        }

        #dischargeModal h2 {
            text-align: center;
            color: #0284c7;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        #dischargeModal table {
            width: 100%;
            border-collapse: collapse;
        }

        #dischargeModal table th,
        #dischargeModal table td {
            border: 1px solid #ddd;
            padding: 5px;
        }

        #dischargeModal .modal-actions {
            display: flex;
            gap: .5rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        #dischargeModal .modal-actions button {
            padding: .5rem 1rem;
            border-radius: .25rem;
            font-weight: 600;
        }

        #dischargeModal .modal-actions .cancel {
            background: #e5e7eb;
        }

        #dischargeModal .modal-actions .complete {
            background: #0284c7;
            color: #fff;
        }

        #dischargeModal .modal-actions .print {
            background: #10b981;
            color: #fff;
        }

        #dischargeModal button.add-service {
            width: 100%;
            margin-top: .5rem;
            background: #0284c7;
            color: #fff;
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
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">+
                    Add Appointment</button>
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
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="appointmentsBody">
                        <tr id="row-A001">
                            <td>A001</td>
                            <td>John Smith</td>
                            <td>Dr. Sarah Johnson</td>
                            <td>General Consultation</td>
                            <td>2026-01-19</td>
                            <td class="text-center" id="pay-A001"><span class="badge badge-unpaid">Unpaid</span></td>
                            <td class="text-center">
                                <span class="action-btn edit">Edit</span>
                                <span class="action-btn discharge"
                                    onclick="openDischargeModal('A001','John Smith','General Consultation',25000)">Discharge</span>
                            </td>
                        </tr>
                        <tr id="row-A002">
                            <td>A002</td>
                            <td>Mary Johnson</td>
                            <td>Dr. May Lin</td>
                            <td>Child Health</td>
                            <td>2026-01-20</td>
                            <td class="text-center" id="pay-A002"><span class="badge badge-unpaid">Unpaid</span></td>
                            <td class="text-center">
                                <span class="action-btn edit">Edit</span>
                                <span class="action-btn discharge"
                                    onclick="openDischargeModal('A002','Mary Johnson','Child Health',30000)">Discharge</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- +Add Appointment Modal --}}
    <div id="addModal">
        <div class="add-form-card">
            <div class="add-form-title">Appointment Information</div>
            <form onsubmit="saveAppointment(); return false;">
                <div class="add-form-grid">
                    <div class="add-form-group">
                        <label>Patient Name</label>
                        <input type="text" id="add_patient" placeholder="Select patient">
                    </div>
                    <div class="add-form-group">
                        <label>Doctor</label>
                        <select id="add_doctor">
                            <option>Select doctor</option>
                            <option>Dr. Sarah Johnson – General</option>
                            <option>Dr. May Lin – OG</option>
                            <option>Dr. Kyaw Myint – Cardiology</option>
                        </select>
                    </div>
                    <div class="add-form-group">
                        <label>Service</label>
                        <select id="add_service">
                            <option>Select service</option>
                            <option>General Consultation</option>
                            <option>Child Health</option>
                            <option>Diabetes Care</option>
                            <option>Women’s Health</option>
                        </select>
                    </div>
                    <div class="add-form-group">
                        <label>Appointment Date</label>
                        <input type="date" id="add_date">
                    </div>
                    <div class="add-form-group">
                        <label>Phone</label>
                        <input type="tel" id="add_phone" placeholder="09xxxxxxxx">
                    </div>
                </div>
                <div class="add-form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Discharge Modal (unchanged) --}}
    <div id="dischargeModal">
        <div class="modal-container">
            <div class="panel left-panel">
                <h2>Patient & Services</h2>
                <div class="mb-2"><label>Appointment ID</label><input id="discharge_appointment_code" readonly
                        class="w-full border rounded px-2 py-1 bg-gray-100 text-sm"></div>
                <div class="mb-2"><label>Patient Name</label><input id="discharge_patient" readonly
                        class="w-full border rounded px-2 py-1 bg-gray-100 text-sm"></div>
                <label>Services</label>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="serviceList"></tbody>
                </table>
                <button type="button" onclick="addServiceRow()" class="add-service">+ Add Service</button>
            </div>
            <div class="panel right-panel">
                <h2>Payment & Receipt</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                    <div><label>Payment Method</label><select id="payment_method"
                            class="w-full border rounded px-2 py-1 text-sm">
                            <option>Cash</option>
                            <option>Card</option>
                            <option>Mobile Payment</option>
                        </select></div>
                    <div><label>Discount</label><input type="number" id="discount_amount" value="0"
                            class="w-full border rounded px-2 py-1 text-sm"></div>
                    <div><label>Paid</label><input type="number" id="paid_amount"
                            class="w-full border rounded px-2 py-1 text-sm"></div>
                    <div><label>Change / Due</label><input id="balance_amount" readonly
                            class="w-full border rounded px-2 py-1 bg-gray-100 text-sm"></div>
                </div>
                <div id="billPreview"></div>
                <div class="modal-actions">
                    <button type="button" onclick="closeDischargeModal()" class="cancel">Cancel</button>
                    <button type="button" onclick="completeDischarge()" class="complete">Complete</button>
                    <button type="button" onclick="window.print()" class="print">Print</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const addModal = document.getElementById('addModal');
        const dischargeModal = document.getElementById('dischargeModal');
        let services = [],
            currentId = null;

        function openAddModal() {
            addModal.classList.add('modal-open');
        }

        function closeAddModal() {
            addModal.classList.remove('modal-open');
        }

        function saveAppointment() {
            const id = 'A' + Math.floor(Math.random() * 900 + 100);
            const patient = document.getElementById('add_patient').value;
            const doctor = document.getElementById('add_doctor').value;
            const service = document.getElementById('add_service').value;
            const date = document.getElementById('add_date').value;
            const phone = document.getElementById('add_phone').value;

            if (!patient || !doctor || !service || !date) {
                alert('Please fill all required fields!');
                return;
            }

            const tbody = document.getElementById('appointmentsBody');
            const tr = document.createElement('tr');
            tr.id = 'row-' + id;
            tr.innerHTML = `
        <td>${id}</td>
        <td>${patient}</td>
        <td>${doctor}</td>
        <td>${service}</td>
        <td>${date}</td>
        <td class="text-center" id="pay-${id}"><span class="badge badge-unpaid">Unpaid</span></td>
        <td class="text-center">
            <span class="action-btn edit">Edit</span>
            <span class="action-btn discharge" onclick="openDischargeModal('${id}','${patient}','${service}',0)">Discharge</span>
        </td>`;
            tbody.appendChild(tr);
            closeAddModal();
            document.getElementById('add_patient').value = '';
            document.getElementById('add_doctor').value = '';
            document.getElementById('add_service').value = '';
            document.getElementById('add_date').value = '';
            document.getElementById('add_phone').value = '';
        }

        // ===== Discharge Modal Functions =====
        function openDischargeModal(id, patient = '', service = '', price = 0) {
            currentId = id;
            services = [{
                name: service,
                price: price
            }];
            discharge_appointment_code.value = id;
            discharge_patient.value = patient;
            paid_amount.value = price;
            discount_amount.value = 0;
            renderServiceList();
            updateBillPreview();
            dischargeModal.classList.add('modal-open');
        }

        function closeDischargeModal() {
            dischargeModal.classList.remove('modal-open');
        }

        function addServiceRow() {
            services.push({
                name: '',
                price: 0
            });
            renderServiceList();
        }

        function removeServiceRow(i) {
            services.splice(i, 1);
            renderServiceList();
        }

        function renderServiceList() {
            serviceList.innerHTML = '';
            services.forEach((s, i) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td><input value="${s.name}" oninput="services[${i}].name=this.value;updateBillPreview()"></td>
        <td><input type="number" value="${s.price}" oninput="services[${i}].price=parseFloat(this.value)||0;updateBillPreview()"></td>
        <td class="text-center"><button onclick="removeServiceRow(${i})" class="text-red-600">X</button></td>`;
                serviceList.appendChild(tr);
            });
            updateBillPreview();
        }

        function updateBillPreview() {
            let total = 0;
            billPreview.innerHTML = '';
            services.forEach(s => {
                if (s.name) {
                    total += s.price;
                    billPreview.innerHTML +=
                        `<div class="flex justify-between"><span>${s.name}</span><span>${s.price}</span></div>`;
                }
            });
            const d = parseFloat(discount_amount.value) || 0;
            const p = parseFloat(paid_amount.value) || 0;
            const bal = p - (total - d);
            balance_amount.value = bal;
            billPreview.innerHTML += `<hr class="my-1"><div class="flex justify-between"><b>Total</b><b>${total}</b></div>
    <div class="flex justify-between"><span>Discount</span><span>${d}</span></div>
    <div class="flex justify-between"><span>Paid</span><span>${p}</span></div>
    <div class="flex justify-between"><span>Change / Due</span><span>${bal}</span></div>`;
        }

        discount_amount.addEventListener('input', updateBillPreview);
        paid_amount.addEventListener('input', updateBillPreview);

        function completeDischarge() {
            const dischargeData = {
                appointment_id: currentId,
                patient_name: discharge_patient.value,
                doctor_name: "Doctor Name Placeholder", // adjust if needed
                services: services,
                total: services.reduce((sum, s) => sum + (s.price || 0), 0),
                paid: parseFloat(paid_amount.value) || 0,
                balance: parseFloat(balance_amount.value) || 0,
                payment_method: payment_method.value,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route('discharge.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(dischargeData)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('pay-' + currentId).innerHTML =
                            '<span class="badge badge-paid">Paid</span>';
                        const row = document.getElementById('row-' + currentId);
                        row.querySelector('.action-btn.discharge').outerHTML =
                            '<span class="text-gray-400">Completed</span>';
                        closeDischargeModal();
                    }
                });
        }
    </script>
@endsection
