@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
    <style>
        /* Print styles */
        @media print {
            body * {
                visibility: hidden !important;
            }

            #billPreview,
            #billPreview * {
                visibility: visible !important;
            }

            #billPreview {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                font-size: 12px;
                background: #fff;
                padding: 1rem;
            }
        }

        /* Table responsive */
        @media (max-width:640px) {
            table.responsive-table thead {
                display: none;
            }

            table.responsive-table tbody tr {
                display: block;
                margin-bottom: 0.75rem;
                border-radius: 0.5rem;
                padding: 0.5rem;
                background: #fff;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
            }

            table.responsive-table tbody tr td {
                display: flex;
                justify-content: space-between;
                padding: 0.25rem 0.5rem;
                border: none;
            }

            table.responsive-table tbody tr td::before {
                content: attr(data-label);
                font-weight: 600;
                flex-basis: 40%;
            }

            table.responsive-table tbody tr td:last-child {
                justify-content: flex-end;
            }
        }

        /* Action buttons */
        .action-btn {
            font-weight: 500;
            cursor: pointer;
            transition: 0.2s;
        }

        .action-btn.edit {
            color: #f59e0b;
        }

        .action-btn.discharge {
            color: #10b981;
        }

        .action-btn:hover {
            text-decoration: underline;
        }

        /* Base modal styles */
        #dischargeModal,
        #addModal {
            display: none;
            /* hidden by default */
            position: fixed;
            /* stay on top */
            inset: 0;
            /* cover full screen */
            z-index: 50;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            overflow: auto;
            padding: 1rem;
        }

        /* Show modal */
        .modal-open {
            display: flex !important;
        }

        /* Modal container */
        #dischargeModal .modal-container,
        #addModal>div {
            max-width: 95%;
            /* prevent overflow */
            width: 100%;
            box-sizing: border-box;
        }

        /* Discharge Modal */
        #dischargeModal .modal-container {
            display: flex;
            flex-direction: row;
            background: #f4f9ff;
            border-radius: 0.5rem;
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
            font-weight: 700;
            text-align: center;
            color: #0284c7;
            margin-bottom: 1rem;
        }

        /* Table inside modal */
        #serviceList input {
            width: 100%;
            padding: 0.25rem;
            border-radius: 0.25rem;
            border: 1px solid #ccc;
        }

        /* Receipt / bill preview */
        #billPreview {
            font-family: 'Courier New', Courier, monospace;
            background: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            max-height: 250px;
            overflow-y: auto;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        /* Modal actions */
        #dischargeModal .modal-actions {
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }

        #dischargeModal .modal-actions button {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 600;
        }

        #dischargeModal .modal-actions .cancel {
            background: #e5e7eb;
            color: #111;
        }

        #dischargeModal .modal-actions .complete {
            background: #0284c7;
            color: #fff;
        }

        #dischargeModal .modal-actions .print {
            background: #10b981;
            color: #fff;
        }

        /* Add service button */
        #dischargeModal button.add-service {
            width: 100%;
            margin-top: 0.5rem;
            background: #0284c7;
            color: #fff;
        }

        @media (max-width:768px) {
            #dischargeModal .modal-container {
                flex-direction: column;
            }

            #dischargeModal .left-panel {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
        }

        /* Table hover */
        table.responsive-table tbody tr:hover {
            background: #f0f9ff;
            transition: 0.2s;
        }
    </style>

    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700">Appointments</h1>
            </div>
            <div class="flex justify-end items-center mb-6 gap-3">
                <div class="flex gap-2">
                    <input type="text" placeholder="Search by id..." class="border rounded px-3 py-2 w-64">
                </div>
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Appointment
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 responsive-table">
                    <thead class="bg-sky-100">
                        <tr>
                            <th class="px-4 py-2 text-left">ID</th>
                            <th class="px-4 py-2 text-left">Patient</th>
                            <th class="px-4 py-2 text-left">Doctor</th>
                            <th class="px-4 py-2 text-left">Service</th>
                            <th class="px-4 py-2 text-left">Date</th>
                            <th class="px-4 py-2 text-left">Time</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td data-label="ID">A001</td>
                            <td data-label="Patient">John Smith</td>
                            <td data-label="Doctor">Dr. Sarah Johnson</td>
                            <td data-label="Service">General Consultation</td>
                            <td data-label="Date">2026-01-19</td>
                            <td data-label="Time">10:00 AM</td>
                            <td data-label="Status" class="text-center">
                                <span
                                    class="px-2 py-1 rounded-full text-sm font-semibold bg-blue-200 text-blue-800">Scheduled</span>
                            </td>
                            <td data-label="Actions" class="text-center space-x-2">
                                <span class="action-btn edit">Edit</span>
                                <span class="action-btn discharge"
                                    onclick="openDischargeModal('A001','John Smith','General Consultation',25000)">Discharge</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    {{-- ADD MODAL --}}
    <div id="addModal">
        <div class="bg-white rounded-xl shadow-lg max-w-3xl w-full p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Appointment Information</h2>
            <form method="POST" action="#">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><label class="block text-gray-700 mb-1">Patient Name</label><input type="text"
                            name="patient_name" required class="w-full border rounded px-3 py-2"></div>
                    <div><label class="block text-gray-700 mb-1">Doctor</label><input type="text" name="doctor_name"
                            class="w-full border rounded px-3 py-2"></div>
                    <div><label class="block text-gray-700 mb-1">Service</label><input type="text" name="service"
                            class="w-full border rounded px-3 py-2"></div>
                    <div><label class="block text-gray-700 mb-1">Appointment Date</label><input type="date"
                            name="date" class="w-full border rounded px-3 py-2"></div>
                    <div><label class="block text-gray-700 mb-1">Appointment Time</label><input type="time"
                            name="time" class="w-full border rounded px-3 py-2"></div>
                    <div><label class="block text-gray-700 mb-1">Phone</label><input type="tel" name="phone"
                            class="w-full border rounded px-3 py-2"></div>
                    <div class="md:col-span-2"><label class="block text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-3">
                    <button type="button" onclick="closeAddModal()" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- DISCHARGE MODAL --}}
    <div id="dischargeModal">
        <div class="modal-container">
            <div class="panel left-panel">
                <h2>Patient & Services</h2>
                <div class="mb-2">
                    <label>Appointment ID</label>
                    <input type="text" id="discharge_appointment_code" readonly
                        class="w-full border rounded px-2 py-1 bg-gray-100 text-sm">
                </div>
                <div class="mb-2">
                    <label>Patient Name</label>
                    <input type="text" id="discharge_patient" readonly
                        class="w-full border rounded px-2 py-1 bg-gray-100 text-sm">
                </div>
                <label>Services</label>
                <table class="w-full text-sm border rounded">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-2 py-1 border text-left">Service</th>
                            <th class="px-2 py-1 border text-right">Price</th>
                            <th class="px-2 py-1 border text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody id="serviceList"></tbody>
                </table>
                <button type="button" onclick="addServiceRow()" class="add-service">+ Add Service</button>
            </div>

            <div class="panel right-panel">
                <h2>Payment & Receipt</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">
                    <div>
                        <label>Payment Method</label>
                        <select id="payment_method" class="w-full border rounded px-2 py-1 text-sm">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="mobile">Mobile Payment</option>
                        </select>
                    </div>
                    <div>
                        <label>Discount</label>
                        <input type="number" id="discount_amount" value="0"
                            class="w-full border rounded px-2 py-1 text-sm">
                    </div>
                    <div>
                        <label>Paid</label>
                        <input type="number" id="paid_amount" class="w-full border rounded px-2 py-1 text-sm">
                    </div>
                    <div>
                        <label>Change / Due</label>
                        <input type="text" id="balance_amount" readonly
                            class="w-full border rounded px-2 py-1 bg-gray-100 text-sm">
                    </div>
                </div>
                <div class="mb-2">
                    <label>Remarks</label>
                    <textarea id="remarks" rows="2" class="w-full border rounded px-2 py-1 text-sm"></textarea>
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
        let services = [];

        function openAddModal() {
            document.getElementById('addModal').classList.add('modal-open');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.remove('modal-open');
        }

        function openDischargeModal(id, patient, service, price) {
            services = [{
                name: service,
                price: price
            }];
            document.getElementById('discharge_appointment_code').value = id;
            document.getElementById('discharge_patient').value = patient;
            document.getElementById('paid_amount').value = price;
            document.getElementById('discount_amount').value = 0;
            renderServiceList();
            updateBillPreview();
            document.getElementById('dischargeModal').classList.add('modal-open');
        }

        function closeDischargeModal() {
            document.getElementById('dischargeModal').classList.remove('modal-open');
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
            const tbody = document.getElementById('serviceList');
            tbody.innerHTML = '';
            services.forEach((s, i) => {
                const row = document.createElement('tr');
                row.innerHTML =
                    `<td class="border px-2 py-1"><input type="text" value="${s.name}" class="w-full border rounded px-1 py-0.5 text-sm" oninput="services[${i}].name=this.value;updateBillPreview()"></td>
            <td class="border px-2 py-1"><input type="number" value="${s.price}" class="w-full border rounded px-1 py-0.5 text-sm" oninput="services[${i}].price=parseFloat(this.value)||0;updateBillPreview()"></td>
            <td class="border px-2 py-1 text-center"><button type="button" onclick="removeServiceRow(${i})" class="text-red-600 px-1 text-sm">X</button></td>`;
                tbody.appendChild(row);
            });
            updateBillPreview();
        }

        function updateBillPreview() {
            const table = document.getElementById('billPreview');
            table.innerHTML = '';
            let total = 0;
            services.forEach(s => {
                if (s.name && s.price) {
                    total += s.price;
                    table.innerHTML +=
                        `<div class="flex justify-between"><span>${s.name}</span><span>${s.price}</span></div>`;
                }
            });
            const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
            const paid = parseFloat(document.getElementById('paid_amount').value) || 0;
            const balance = paid - (total - discount);
            document.getElementById('balance_amount').value = balance;
            table.innerHTML += `<hr class="my-1"><div class="flex justify-between"><span>Total</span><span>${total}</span></div>
        <div class="flex justify-between"><span>Discount</span><span>${discount}</span></div>
        <div class="flex justify-between"><span>Paid</span><span>${paid}</span></div>
        <div class="flex justify-between"><span>Change/Due</span><span>${balance}</span></div>`;
        }

        document.getElementById('discount_amount').addEventListener('input', updateBillPreview);
        document.getElementById('paid_amount').addEventListener('input', updateBillPreview);

        function completeDischarge() {
            alert("Discharge completed!");
            closeDischargeModal();
        }
    </script>
@endsection
