@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="app flex">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2 class="logo">MediCore</h2>
            <nav>
                <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
                <a href="{{ route('patients.index') }}"><i class="fa-solid fa-user"></i> Patients</a>
                <a href="{{ route('doctors.index') }}"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
                <a href="{{ route('appointments.index') }}"><i class="fa-solid fa-calendar-check"></i> Appointments</a>
                <a class="active" href="{{ route('payments.index') }}"><i class="fa-solid fa-credit-card"></i> Payments</a>
                <a href="{{ route('logout') }}" class="logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                <h1 class="text-2xl font-semibold text-slate-700">Payments</h1>
                <div class="flex gap-2 flex-wrap">
                    <input type="text" placeholder="Search by patient..." class="search border rounded px-3 py-2"
                        onkeyup="filterTable()">
                    <button onclick="openAddModal()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">+ Add Payment</button>
                    <button onclick="exportExcel()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">Export Excel</button>
                    <button onclick="exportPDF()"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">Export PDF</button>
                </div>
            </div>

            <div class="table-container">
                <table id="paymentsTable" class="min-w-full text-sm">
                    <thead class="bg-sky-50 cursor-pointer">
                        <tr>
                            <th onclick="sortTable(0)">Patient</th>
                            <th onclick="sortTable(1)">Doctor</th>
                            <th onclick="sortTable(2)">Service</th>
                            <th onclick="sortTable(3)">Amount</th>
                            <th onclick="sortTable(4)">Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->patient->name }}</td>
                                <td>{{ $payment->doctor->full_name }}</td>
                                <td>{{ $payment->service }}</td>
                                <td>{{ number_format($payment->amount) }}</td>
                                <td>{{ $payment->date->format('d-M-Y') }}</td>
                                <td>
                                    <button class="action-btn" onclick="printBill(this)">Print Bill</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500">No payments found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <h3>Add Payment</h3>
            <form method="POST" action="{{ route('payments.store') }}" class="space-y-3">
                @csrf
                <label>Patient</label>
                <select name="patient_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select Patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->name }}</option>
                    @endforeach
                </select>

                <label>Doctor</label>
                <select name="doctor_id" class="w-full border rounded px-3 py-2">
                    <option value="">Select Doctor</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->full_name }}</option>
                    @endforeach
                </select>

                <label>Service</label>
                <input type="text" name="service" placeholder="Service Name" class="w-full border rounded px-3 py-2">

                <label>Amount</label>
                <input type="number" name="amount" placeholder="Amount" class="w-full border rounded px-3 py-2">

                <label>Date</label>
                <input type="date" name="date" class="w-full border rounded px-3 py-2">

                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" class="close-btn" onclick="closeModal()">Close</button>
                    <button type="submit"
                        class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function openAddModal() {
            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        function filterTable() {
            const input = document.querySelector('.search').value.toLowerCase();
            document.querySelectorAll('#paymentsTable tbody tr').forEach(r => {
                r.style.display = r.innerText.toLowerCase().includes(input) ? '' : 'none';
            });
        }

        function printBill(btn) {
            const tr = btn.closest('tr');
            const data = tr.children;
            let html = `<h2>MediCore Clinic - Payment Receipt</h2><hr><table style="width:100%;margin-top:10px;">`;
            html += `<tr><td>Patient:</td><td>${data[0].innerText}</td></tr>`;
            html += `<tr><td>Doctor:</td><td>${data[1].innerText}</td></tr>`;
            html += `<tr><td>Service:</td><td>${data[2].innerText}</td></tr>`;
            html += `<tr><td>Amount:</td><td>${data[3].innerText}</td></tr>`;
            html += `<tr><td>Date:</td><td>${data[4].innerText}</td></tr></table>`;
            const w = window.open('', 'Print', 'height=500,width=400');
            w.document.write(html);
            w.document.close();
            w.print();
        }

        function exportExcel() {
            const table = document.getElementById('paymentsTable');
            const wb = XLSX.utils.table_to_book(table, {
                sheet: "Payments"
            });
            XLSX.writeFile(wb, "Payments.xlsx");
        }

        function exportPDF() {
            const printWindow = window.open('', 'Print', 'height=600,width=500');
            printWindow.document.write('<h2>MediCore Clinic - Payments Report</h2><hr>' + document.getElementById(
                'paymentsTable').outerHTML);
            printWindow.document.close();
            printWindow.print();
        }

        function sortTable(n) {
            const table = document.getElementById('paymentsTable');
            let switching = true,
                dir = "asc";
            while (switching) {
                switching = false;
                const rows = table.rows;
                for (let i = 1; i < rows.length - 1; i++) {
                    let shouldSwitch = false;
                    let x = rows[i].cells[n].innerText.toLowerCase();
                    let y = rows[i + 1].cells[n].innerText.toLowerCase();
                    if (dir === "asc" && x > y) {
                        shouldSwitch = true;
                        break;
                    }
                    if (dir === "desc" && x < y) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                } else if (dir === "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    </script>
@endsection