@extends('layouts.app')

@section('content')
    <div class="app flex">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">

            <!-- Header + Add Payment Button -->
            <div class="flex w-full sm:w-auto gap-2">
                <h1 class="text-2xl font-semibold text-slate-700">Payments</h1>
            </div>
            <div class="flex justify-end items-center mb-6 gap-3">

                <!-- Search Input + Button -->
                <div class="flex gap-2">
                    <input type="text" placeholder="Search by name..." class="border rounded px-3 py-2 w-64">
                </div>

                <!-- Add Patient -->
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Payments
                </button>
            </div>

            <!-- Payments Table -->
            <div class="overflow-x-auto bg-white p-4 rounded-xl shadow">
                <table id="paymentsTable" class="min-w-full text-left">
                    <thead class="bg-sky-100 text-slate-800">
                        <tr>
                            <th class="px-4 py-2 cursor-pointer" onclick="sortTable(0)">Patient</th>
                            <th class="px-4 py-2 cursor-pointer" onclick="sortTable(1)">Doctor</th>
                            <th class="px-4 py-2 cursor-pointer" onclick="sortTable(2)">Service</th>
                            <th class="px-4 py-2 cursor-pointer" onclick="sortTable(3)">Amount</th>
                            <th class="px-4 py-2 cursor-pointer" onclick="sortTable(4)">Date</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr class="hover:bg-sky-50">
                                <td class="px-4 py-2">{{ $payment->patient }}</td>
                                <td class="px-4 py-2">{{ $payment->doctor }}</td>
                                <td class="px-4 py-2">{{ $payment->service }}</td>
                                <td class="px-4 py-2">{{ number_format($payment->amount) }}</td>
                                <td class="px-4 py-2">{{ $payment->date->format('d-M-Y') }}</td>
                                <td class="px-4 py-2 text-center">
                                    <button onclick="printBill(this)"
                                        class="bg-sky-500 hover:bg-sky-700 text-white px-3 py-1 rounded-md text-sm">
                                        Print Bill
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>

    <!-- Add Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 p-4">
        <div class="bg-white rounded-xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-sky-600 mb-4">Add Payment</h3>
            <form method="POST" action="{{ route('payments.store') }}">
                @csrf
                <div class="flex flex-col gap-3">
                    <input type="text" name="patient" placeholder="Patient Name" class="border px-3 py-2 rounded w-full"
                        required>
                    <input type="text" name="doctor" placeholder="Doctor Name" class="border px-3 py-2 rounded w-full"
                        required>
                    <input type="text" name="service" placeholder="Service" class="border px-3 py-2 rounded w-full"
                        required>
                    <input type="number" name="amount" placeholder="Amount" class="border px-3 py-2 rounded w-full"
                        required>
                    <input type="date" name="date" class="border px-3 py-2 rounded w-full" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeAddModal()"
                        class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded">Add
                        Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openAddModal() {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        }

        // Filter table
        function filterTable() {
            const input = document.querySelector('input[placeholder="Search by patient..."]').value.toLowerCase();
            document.querySelectorAll('#paymentsTable tbody tr').forEach(row => {
                row.style.display = row.innerText.toLowerCase().includes(input) ? '' : 'none';
            });
        }

        // Print bill
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

        // Sort table
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