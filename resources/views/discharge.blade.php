@extends('layouts.app')

@section('title', 'Discharges | MediCore')

@section('content')

    <style>
        /* Existing CSS */
        .stat-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, .05);
        }

        .stat-title {
            font-size: 13px;
            color: #64748b;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
        }

        .table-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(52, 5, 5, 0.06);
        }

        .discharge-table th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: .03em;
            padding: 12px;
        }

        .discharge-table td {
            padding: 12px;
            font-size: 14px;
            border-top: 1px solid #4e79ce;
            vertical-align: top;
        }

        .badge-paid {
            background: #22c55e;
            color: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .service-preview {
            color: #0d6efd;
            cursor: pointer;
            font-size: 13px;
        }

        .service-box {
            background: #f8fafc;
            border-radius: 8px;
            padding: 8px 10px;
            line-height: 1.6;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .action-btn {
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 8px;
            background: #0d6efd;
            color: white;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #0b5ed7;
        }

        .empty-box {
            padding: 60px 20px;
            text-align: center;
            color: #64748b;
        }
    </style>

    <div class="app flex min-h-screen">
        @include('layouts.sidebar')
        <main class="flex-1 p-6">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Discharged Patients</h1>
                <p class="text-sm text-slate-500 mt-1">Completed appointments and payments</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <div class="stat-card">
                    <div class="stat-title">Total Discharges</div>
                    <div id="statTotal" class="stat-value">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Total Revenue</div>
                    <div id="statRevenue" class="stat-value">0</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Search & Date</div>
                    <div class="flex gap-2 mt-2">
                        <input id="searchInput" type="text" placeholder="Patient or ID"
                            class="border rounded-lg px-3 py-2 w-full">
                        <input id="dateFilter" type="date" class="border rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="table-card">
                <table class="min-w-full discharge-table">
                    <thead class="bg-blue-50">
                        <tr>
                            <th>Appointment</th>
                            <th>Patient</th>
                            <th>Services</th>
                            <th>Total</th>
                            <th>Discount</th>
                            <th>Paid</th>
                            <th>Change / Due</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="dischargeBody">
                        @foreach ($discharges as $row)
                            @php
                                $services = is_array($row->services)
                                    ? $row->services
                                    : json_decode($row->services, true);
                                $services = $services ?? [];
                                $balance = $row->paid - ($row->total - ($row->discount ?? 0));
                            @endphp

                            <tr id="discharge_{{ $row->id }}">
                                <td>{{ $row->appointment_id }}</td>
                                <td>{{ $row->patient_name ?? '-' }}</td>
                                <td>
                                    <span class="service-preview" onclick="toggleService('srv_{{ $row->id }}')">View
                                        services</span>
                                    <div id="srv_{{ $row->id }}" class="service-box">
                                        @foreach ($services as $s)
                                            {{ $s['name'] ?? '-' }} - {{ $s['price'] ?? 0 }}<br>
                                        @endforeach
                                    </div>
                                </td>
                                <td data-value="{{ $row->total }}">{{ number_format($row->total, 0, '.', ',') }}</td>
                                <td data-value="{{ $row->discount }}">{{ number_format($row->discount, 0, '.', ',') }}</td>
                                <td data-value="{{ $row->paid }}">{{ number_format($row->paid, 0, '.', ',') }}</td>
                                <td data-value="{{ $balance }}">{{ number_format($balance, 0, '.', ',') }}</td>
                                <td>{{ $row->created_at }}</td>
                                <td><button class="action-btn" onclick="printSingle({{ $row->id }})">Print</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div id="emptyBox" class="empty-box {{ count($discharges) === 0 ? '' : 'hidden' }}">
                    No discharged records found.
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const dateFilter = document.getElementById('dateFilter');
            const statTotal = document.getElementById('statTotal');
            const statRevenue = document.getElementById('statRevenue');
            const dischargeBody = document.getElementById('dischargeBody');
            const emptyBox = document.getElementById('emptyBox');

            window.toggleService = function(id) {
                const el = document.getElementById(id);
                el.style.display = (el.style.display === '' || el.style.display === 'none') ? 'block' : 'none';
            }

            function renderDischarges() {
                const rows = Array.from(dischargeBody.querySelectorAll('tr'));
                const search = searchInput.value.toLowerCase();
                const filterDate = dateFilter.value;

                let visibleCount = 0;
                let revenue = 0;

                rows.forEach(row => {
                    const patientName = row.cells[1].innerText.toLowerCase();
                    const appointmentId = row.cells[0].innerText;
                    const dateTime = row.cells[7].innerText;
                    const total = parseFloat(row.cells[3].dataset.value) || 0;
                    const discount = parseFloat(row.cells[4].dataset.value) || 0;

                    let show = true;
                    if (search) show = patientName.includes(search) || appointmentId.includes(search);
                    if (filterDate) show = show && (new Date(dateTime).toISOString().slice(0, 10) ===
                        filterDate);

                    row.style.display = show ? '' : 'none';
                    if (show) {
                        visibleCount++;
                        revenue += (total - discount);
                    }
                });

                statTotal.innerText = visibleCount;
                statRevenue.innerText = revenue.toLocaleString(); // formatted with commas
                emptyBox.classList.toggle('hidden', visibleCount > 0);
            }

            searchInput.addEventListener('input', renderDischarges);
            dateFilter.addEventListener('change', renderDischarges);

            renderDischarges();

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

            // -----------------------------
            // Auto-refresh discharges every 5 seconds
            async function fetchDischarges() {
                const res = await fetch("{{ route('discharge.index') }}");
                const parser = new DOMParser();
                const doc = parser.parseFromString(await res.text(), 'text/html');
                const newRows = doc.querySelectorAll('#dischargeBody tr');

                dischargeBody.innerHTML = '';
                newRows.forEach(r => dischargeBody.appendChild(r.cloneNode(true)));

                renderDischarges();
            }

            setInterval(fetchDischarges, 5000); // every 5s
        });
    </script>

@endsection
