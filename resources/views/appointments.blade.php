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
            width: 95%;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, .2);
            margin: 20px auto;
        }

        #dischargeModal .modal-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 20px;
        }

        #dischargeModal .left-panel {
            flex: 0 0 20%;
            background: #f0f4f8;
            padding: 15px;
        }

        #dischargeModal .right-panel {
            flex: 1;
            padding: 15px;
            overflow: auto;
        }

        #dischargeModal h3 {
            font-weight: 700;
            margin-bottom: 8px;
            color: #0284c7;
            font-size: 1rem;
        }

        #dischargeModal p {
            font-size: 0.85rem;
            margin-bottom: 6px;
        }

        #dischargeModal table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 0.85rem;
        }

        #dischargeModal th,
        #dischargeModal td {
            border: 1px solid #ddd;
            padding: 4px;
        }

        #dischargeModal table input {
            width: 100%;
            padding: 4px 6px;
            border-radius: 4px;
            border: 1px solid #cfe6ff;
            font-size: 0.85rem;
        }

        #dischargeModal .add-service {
            background: #0d6efd;
            color: #fff;
            width: 100%;
            margin-bottom: 8px;
            border: none;
            padding: 5px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        #dischargeModal .slip {
            background: #fff;
            border: 1px solid #ddd;
            padding: 8px;
            margin-top: 10px;
            font-size: 0.85rem;
            max-height: 400px;
            overflow-y: auto;
        }

        #dischargeModal .slip h4 {
            margin-bottom: 5px;
            font-weight: 700;
            color: #0284c7;
            font-size: 0.9rem;
        }

        #dischargeModal .slip .row {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
            padding: 3px 0;
        }

        #dischargeModal .slip .row.total {
            font-weight: 700;
            border-top: 1px dashed #ccc;
            padding-top: 4px;
            margin-top: 4px;
        }

        #dischargeModal .add-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 10px;
        }

        #dischargeModal .add-form-group label {
            font-size: 0.8rem;
            margin-bottom: 3px;
            color: #334155;
            display: block;
        }

        #dischargeModal .add-form-group input,
        #dischargeModal .add-form-group textarea {
            padding: 6px;
            border-radius: 8px;
            border: 1px solid #cfe6ff;
            outline: none;
            font-size: 0.85rem;
            width: 100%;
        }

        #dischargeModal .add-form-group textarea {
            min-height: 50px;
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

        <main class="flex-1 p-6 ml-60">


            <!-- Update the header section in appointments.blade.php -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Appointments</h1>
            </div>

            <div class="flex justify-between items-center mb-6 gap-3">
                <div class="flex gap-2">
                    <input type="text" id="searchInput" placeholder="Search by ID, Patient, Doctor or Service..."
                        class="border rounded px-3 py-2 w-80 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ request('search') ?? '' }}" autocomplete="off">
                    <button onclick="performSearch()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                        Search
                    </button>
                </div>

                <button type="button" onclick="openAddModal()"
                    class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Appointment
                </button>
            </div>

            <!-- APPOINTMENT TABLE CONTAINER -->
            <div id="appointmentTableContainer">
                @include('appointments.partials.appointment-table', [
                    'appointments' => $appointments,
                    'search' => request('search'),
                ])
            </div>
        </main>
    </div>

    <div id="notification">
        <div id="notificationMessage"></div>
    </div>

    {{-- ================= ADD MODAL ================= --}}
    <div id="addModal">
        <div class="add-form-card ml-60">
            <div class="add-form-title">Appointment Information</div>

            <form id="addAppointmentForm" method="POST" action="{{ route('appointments.store') }}">
                @csrf

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
                        <label>Speciality</label>
                        <select id="specialitySelect" class="w-full p-3 rounded-lg border border-gray-300 bg-white" onchange="filterDoctorsBySpeciality()">
                            <option value="">Select Speciality</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->speciality }}">{{ $doctor->speciality }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="add-form-group">
                        <label>Doctor</label>
                        <select name="doctor_id" id="doctorSelect" class="w-full p-3 rounded-lg border border-gray-300 bg-white" onchange="updateSpecialityFromDoctor()" required>
                            <option value="">Select Doctor</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}" data-speciality="{{ $doctor->speciality }}">{{ $doctor->full_name }} - {{ $doctor->speciality }}</option>
                            @endforeach
                        </select>
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

    {{-- ================= COMPACT DISCHARGE MODAL ================= --}}
    <div id="dischargeModal">
        <div class="modal-wrapper ml-60">
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

                        <div style="margin-top:8px;display:flex;justify-content:flex-end;gap:10px;">
                            <button type="button" class="btn btn-cancel" onclick="closeDischargeModal()"
                                style="padding:6px 16px; font-size:0.85rem;">Cancel</button>
                            <button type="button" class="btn btn-save" onclick="completeDischarge()"
                                style="padding:6px 16px; font-size:0.85rem;">Complete Discharge</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <datalist id="posServiceList">
        @foreach ($services as $s)
            <option value="{{ $s->service_name }}" data-price="{{ $s->service_fee }}"></option>
        @endforeach
    </datalist>

    <script>
        let searchTimeout = null;
        const csrfToken = '{{ csrf_token() }}';

        const addModal = document.getElementById('addModal');
        const dischargeModal = document.getElementById('dischargeModal');
        const serviceTableBody = document.querySelector('#serviceTable tbody');
        const appointmentsBody = document.getElementById('appointmentsBody');

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

        // Setup search on page load
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keyup', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        performSearch();
                    }, 500);
                });

                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        performSearch();
                    }
                });
            }
        });

        // Perform search via AJAX
        function performSearch(page = 1) {
            const searchTerm = document.getElementById('searchInput').value;

            // Show loading state
            const container = document.getElementById('appointmentTableContainer');
            container.innerHTML =
                '<div class="text-center py-8"><div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-sky-600"></div><p class="mt-2 text-gray-600">Searching...</p></div>';

            // Build URL with search term and page
            let url = '{{ route('appointments.index') }}?page=' + page;
            if (searchTerm.trim() !== '') {
                url += '&search=' + encodeURIComponent(searchTerm);
            }

            // Fetch search results
            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        container.innerHTML = data.html;

                        // Update browser URL without reloading
                        const newUrl = window.location.pathname + (searchTerm ? '?search=' + encodeURIComponent(
                            searchTerm) : '');
                        window.history.pushState({
                            path: newUrl
                        }, '', newUrl);

                        // Re-attach event listeners to pagination links
                        attachPaginationListeners();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML =
                        '<div class="text-center py-8 text-red-600">An error occurred while searching. Please try again.</div>';
                });
        }

        // Attach listeners to pagination links
        function attachPaginationListeners() {
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    const page = url.searchParams.get('page') || 1;
                    performSearch(page);
                });
            });
        }

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
                <td style="padding:4px;"><input type="text" list="posServiceList" value="${name}" style="padding:4px 6px; font-size:0.85rem;"></td>
                <td style="padding:4px;"><input type="number" value="${price}" min="0" style="padding:4px 6px; font-size:0.85rem;"></td>
                <td style="padding:4px;">
                    <button type="button"
                        onclick="this.closest('tr').remove();updateTotals();"
                        style="background:red;color:white;border:none;padding:3px 6px;border-radius:4px;cursor:pointer;font-size:0.8rem;">
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
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (result.success) {
                showNotification('✅ Discharge completed!');
                const row = document.getElementById('row-' + data.appointment_id);
                if (row) row.remove();
                closeDischargeModal();

                // Refresh the current page if we're on it
                if (window.location.pathname.includes('appointments')) {
                    performSearch();
                }
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

    // Get the input values
    const patientNameInput = document.getElementById('patientNameInput').value;
    const doctorSelect = document.getElementById('doctorSelect');
    const serviceNameInput = document.getElementById('serviceNameInput').value;
    const appointmentDate = this.querySelector('[name=appointment_date]').value;

    // Get doctor ID from select
    const doctorId = doctorSelect.value;
    
    // Find patient and service from datalists
    const patientOptions = [...document.getElementById('patientList').options];
    const serviceOptions = [...document.getElementById('serviceList').options];
    
    const selectedPatient = patientOptions.find(o => o.value === patientNameInput);
    const selectedService = serviceOptions.find(o => o.value === serviceNameInput);

    // Validate selections
    if (!selectedPatient) {
        showNotification('❌ Please select a valid Patient from the list.');
        return;
    }
    
    if (!doctorId) {
        showNotification('❌ Please select a Doctor.');
        return;
    }
    
    if (!selectedService) {
        showNotification('❌ Please select a valid Service from the list.');
        return;
    }
    
    if (!appointmentDate) {
        showNotification('❌ Please select an Appointment Date.');
        return;
    }

    // Create FormData and append values
    const fd = new FormData();
    fd.append('patient_id', selectedPatient.dataset.id);
    fd.append('doctor_id', doctorId);
    fd.append('service_id', selectedService.dataset.id);
    fd.append('appointment_date', appointmentDate);
    fd.append('_token', csrfToken);

    try {
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        const res = await fetch(this.action, {
            method: 'POST',
            body: fd,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        const result = await res.json();

        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;

        if (result.success) {
            this.reset();
            closeAddModal();
            showNotification('✅ Appointment added successfully!');
            // Refresh the appointments table
            performSearch();
        } else {
            showNotification('❌ Error: ' + (result.message || 'Failed to add appointment'));
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('❌ Network error. Please try again.');
        
        // Reset button state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.textContent = 'Save Appointment';
        submitBtn.disabled = false;
    }
});

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function() {
            location.reload();
        });

        function editAppointment(id) {
            // Implement edit functionality if needed
            console.log('Edit appointment:', id);
        }

        function filterDoctorsBySpeciality() {
            const speciality = document.getElementById('specialitySelect').value;
            const doctorSelect = document.getElementById('doctorSelect');
            const options = doctorSelect.options;
            
            // Show all options if no speciality selected
            if (!speciality) {
                for (let i = 0; i < options.length; i++) {
                    options[i].style.display = '';
                }
                doctorSelect.value = '';
                return;
            }
            
            // Filter doctors by speciality
            let firstVisible = null;
            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.value === '') continue;
                
                const doctorSpeciality = option.getAttribute('data-speciality');
                if (doctorSpeciality && doctorSpeciality.toLowerCase().includes(speciality.toLowerCase())) {
                    option.style.display = '';
                    if (!firstVisible) firstVisible = option;
                } else {
                    option.style.display = 'none';
                }
            }
            
            doctorSelect.value = '';
        }

        function updateSpecialityFromDoctor() {
            const doctorSelect = document.getElementById('doctorSelect');
            const specialitySelect = document.getElementById('specialitySelect');
            const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
            
            if (selectedOption && selectedOption.value) {
                const doctorSpeciality = selectedOption.getAttribute('data-speciality');
                // Find and select matching speciality
                for (let i = 0; i < specialitySelect.options.length; i++) {
                    if (specialitySelect.options[i].value.toLowerCase() === doctorSpeciality.toLowerCase()) {
                        specialitySelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    </script>
@endsection
