@extends('layouts.app')

@section('title', 'Discharges | MediCore')

@section('content')

    <style>
        /* Stat Cards */
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

        .discharge-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Fixed layout for better column control */
        }

        .discharge-table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
            padding: 12px 8px;
            text-align: left;
            background: #f0f5ff;
            color: #1e293b;
            font-weight: 600;
            white-space: nowrap;
        }

        .discharge-table td {
            padding: 16px 8px;
            font-size: 13px;
            border-top: 1px solid #e2e8f0;
            vertical-align: top;
            word-break: break-word;
        }

        /* Column width specifications */
        .discharge-table th:nth-child(1), .discharge-table td:nth-child(1) { width: 8%; } /* Appointment_ID */
        .discharge-table th:nth-child(2), .discharge-table td:nth-child(2) { width: 10%; } /* Patient */
        .discharge-table th:nth-child(3), .discharge-table td:nth-child(3) { width: 10%; } /* Doctor */
        .discharge-table th:nth-child(4), .discharge-table td:nth-child(4) { width: 18%; } /* Service */
        .discharge-table th:nth-child(5), .discharge-table td:nth-child(5) { width: 8%; } /* Price */
        .discharge-table th:nth-child(6), .discharge-table td:nth-child(6) { width: 8%; } /* Discount */
        .discharge-table th:nth-child(7), .discharge-table td:nth-child(7) { width: 8%; } /* Paid */
        .discharge-table th:nth-child(8), .discharge-table td:nth-child(8) { width: 10%; } /* Balance */
        .discharge-table th:nth-child(9), .discharge-table td:nth-child(9) { width: 12%; } /* Time */
        .discharge-table th:nth-child(10), .discharge-table td:nth-child(10) { width: 8%; } /* Action */

        /* Fix for date column to prevent overlapping - smaller font */
        .discharge-table td:nth-child(9) {
            white-space: nowrap;
            font-size: 11px;
            color: #64748b;
        }

        /* Action button column */
        .discharge-table td:last-child {
            white-space: nowrap;
            text-align: center;
        }

        .badge-paid {
            background: #22c55e;
            color: #fff;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Service badge styling */
        .service-badge {
            background: #e6f0ff;
            color: #0d6efd;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s;
        }

        .service-badge:hover {
            background: #0d6efd;
            color: white;
        }

        /* Service detail dropdown - hidden by default */
        .service-detail {
            background: #f8fafc;
            border-left: 3px solid #0d6efd;
            padding: 12px;
            margin-top: 10px;
            border-radius: 8px;
            font-size: 12px;
            display: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: absolute;
            z-index: 100;
            min-width: 250px;
            right: 0;
        }

        .service-detail.show {
            display: block;
        }

        /* Service container for positioning */
        .service-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .view-services-link {
            font-size: 10px;
            color: #64748b;
            margin-left: 5px;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            white-space: nowrap;
        }

        .view-services-link:hover {
            color: #0d6efd;
        }

        .action-btn {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 6px;
            background: #0d6efd;
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .action-btn:hover {
            background: #0b5ed7;
        }

        .empty-box {
            padding: 60px 20px;
            text-align: center;
            color: #64748b;
        }

        /* Responsive table container */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 14px;
        }

        /* Money values */
        .money-value {
            font-weight: 600;
            color: #0f172a;
        }

        .total-services-price {
            font-size: 10px;
            color: #64748b;
            display: block;
            margin-top: 2px;
        }
    </style>

    <div class="app flex min-h-screen">
        @include('layouts.sidebar')
        <main class="flex-1 p-6 ml-60">
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
                    <div id="statRevenue" class="stat-value">0 MMK</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Search & Date</div>
                    <div class="flex gap-2 mt-2">
                        <input id="searchInput" type="text" placeholder="Patient or ID"
                            class="border rounded-lg px-3 py-2 w-full text-sm">
                        <input id="dateFilter" type="date" class="border rounded-lg px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-responsive">
                    <table class="min-w-full discharge-table">
                        <thead class="bg-blue-50">
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Disc</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Time</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="dischargeBody">
                            @forelse ($discharges as $row)
                                @php
                                    // Get service name and all services
                                    $serviceName = 'Unknown';
                                    $allServices = [];
                                    $totalAllServicesPrice = 0;

                                    // Try to get from service_name field first
                                    if (!empty($row->service_name) && $row->service_name !== 'Unknown') {
                                        $serviceName = $row->service_name;
                                    }

                                    // Get all services from JSON
                                    $allServices = is_array($row->services) ? $row->services : json_decode($row->services, true);
                                    if (!is_array($allServices)) {
                                        $allServices = [];
                                    }

                                    // Calculate total price from all services (for the Price column)
                                    if (!empty($allServices)) {
                                        foreach ($allServices as $service) {
                                            if (is_array($service)) {
                                                $price = $service['price'] ?? 0;
                                                $totalAllServicesPrice += $price;
                                            }
                                        }
                                    }

                                    // If service name is still unknown but we have services, use first service name
                                    if ($serviceName === 'Unknown' && !empty($allServices)) {
                                        $firstService = $allServices[0] ?? [];
                                        $serviceName = is_array($firstService) ? ($firstService['name'] ?? 'Unknown') : $firstService;
                                    }

                                    // Get individual service price (for backward compatibility)
                                    $individualServicePrice = $row->service_price ?? 0;

                                    // Use total price from all services as the main price
                                    $displayPrice = $totalAllServicesPrice > 0 ? $totalAllServicesPrice : $individualServicePrice;

                                    // Check if there are multiple services
                                    $hasMultipleServices = count($allServices) > 1;

                                    // Calculate balance using the total price
                                    $netTotal = $displayPrice - ($row->discount ?? 0);
                                    $balanceAmount = ($row->paid ?? 0) - $netTotal;

                                    if ($balanceAmount > 0) {
                                        $balanceText = 'Change: ' . number_format(abs($balanceAmount), 0);
                                    } elseif ($balanceAmount < 0) {
                                        $balanceText = 'Due: ' . number_format(abs($balanceAmount), 0);
                                    } else {
                                        $balanceText = 'Settled';
                                    }

                                    // Format time compactly
                                    $formattedTime = $row->created_at ? $row->created_at->format('m/d H:i') : '-';
                                @endphp

                                <tr id="discharge_{{ $row->id }}">
                                    <td>{{ $row->appointment_id }}</td>
                                    <td>{{ \Str::limit($row->patient_name ?? '-', 15) }}</td>
                                    <td>{{ \Str::limit($row->doctor_name ?? '-', 15) }}</td>
                                    <td>
                                        <div class="service-container">
                                            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                                <span class="service-badge">{{ \Str::limit($serviceName, 15) }}</span>
                                                @if($hasMultipleServices)
                                                    <span class="view-services-link" onclick="toggleServices('srv_{{ $row->id }}')">
                                                        +{{ count($allServices) - 1 }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($hasMultipleServices)
                                                <div id="srv_{{ $row->id }}" class="service-detail">
                                                    <div style="font-weight: 600; margin-bottom: 8px; color: #0d6efd;">All Services</div>
                                                    @foreach($allServices as $service)
                                                        @php
                                                            $sName = is_array($service) ? ($service['name'] ?? 'Unknown') : $service;
                                                            $sPrice = is_array($service) ? ($service['price'] ?? 0) : 0;
                                                        @endphp
                                                        <div style="display: flex; justify-content: space-between; margin-top: 6px; font-size: 11px;">
                                                            <span>{{ \Str::limit($sName, 20) }}</span>
                                                            <span class="money-value">{{ number_format($sPrice, 0) }}</span>
                                                        </div>
                                                    @endforeach
                                                    <div style="margin-top: 10px; padding-top: 8px; border-top: 1px dashed #cbd5e1; font-weight: 600; display: flex; justify-content: space-between;">
                                                        <span>Total:</span>
                                                        <span class="money-value">{{ number_format($totalAllServicesPrice, 0) }} MMK</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="money-value">{{ number_format($displayPrice, 0) }}</span>
                                        @if($hasMultipleServices && $displayPrice != $individualServicePrice)
                                            <span class="total-services-price">(all services)</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format($row->discount ?? 0, 0) }}</td>
                                    <td>{{ number_format($row->paid ?? 0, 0) }}</td>
                                    <td>{{ $balanceText }}</td>
                                    <td>{{ $formattedTime }}</td>
                                    <td>
                                        <button class="action-btn" onclick="printSingle({{ $row->id }})">Print</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-8 text-slate-500">
                                        No discharged records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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

            // Toggle services function
            window.toggleServices = function(id) {
                // Close all other open service details
                document.querySelectorAll('.service-detail.show').forEach(el => {
                    if (el.id !== id) {
                        el.classList.remove('show');
                    }
                });

                // Toggle the clicked one
                const el = document.getElementById(id);
                if (el) {
                    el.classList.toggle('show');
                }
            };

            // Close service details when clicking outside
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.service-container')) {
                    document.querySelectorAll('.service-detail.show').forEach(el => {
                        el.classList.remove('show');
                    });
                }
            });

            function renderDischarges() {
                const rows = Array.from(dischargeBody.querySelectorAll('tr'));
                const search = searchInput.value.toLowerCase();
                const filterDate = dateFilter.value;

                let visibleCount = 0;
                let revenue = 0;

                rows.forEach(row => {
                    // Skip if it's the empty state row
                    if (row.cells.length === 1 && row.cells[0]?.colSpan === 10) return;

                    const patientName = row.cells[1]?.innerText.toLowerCase() || '';
                    const doctorName = row.cells[2]?.innerText.toLowerCase() || '';
                    const serviceElement = row.cells[3]?.querySelector('.service-badge');
                    const serviceName = serviceElement?.innerText.toLowerCase() || '';
                    const appointmentId = row.cells[0]?.innerText || '';
                    const dateTime = row.cells[8]?.innerText || '';
                    const price = parseFloat(row.cells[4]?.innerText.replace(/,/g, '')) || 0;
                    const discount = parseFloat(row.cells[5]?.innerText.replace(/,/g, '')) || 0;

                    let show = true;
                    if (search) {
                        show = patientName.includes(search) ||
                               doctorName.includes(search) ||
                               serviceName.includes(search) ||
                               appointmentId.includes(search);
                    }

                    if (filterDate && dateTime) {
                        // Parse the compact date format (m/d H:i)
                        const parts = dateTime.split(' ');
                        if (parts.length > 0) {
                            const datePart = '2026-' + parts[0].replace('/', '-'); // Convert m/d to Y-m-d
                            const filterDateObj = new Date(filterDate);
                            const rowDateObj = new Date(datePart);
                            show = show && (rowDateObj.toDateString() === filterDateObj.toDateString());
                        }
                    }

                    row.style.display = show ? '' : 'none';
                    if (show) {
                        visibleCount++;
                        revenue += (price - discount);
                    }
                });

                statTotal.innerText = visibleCount;
                statRevenue.innerText = revenue.toLocaleString() + ' MMK';
            }

            searchInput.addEventListener('input', renderDischarges);
            dateFilter.addEventListener('change', renderDischarges);

            renderDischarges();

            window.printSingle = function(id) {
                let tr = document.getElementById(`discharge_${id}`);
                if (!tr) return;

                const cells = tr.cells;

                // Get service info
                const serviceBadge = cells[3]?.querySelector('.service-badge');
                const mainService = serviceBadge?.innerText || 'N/A';

                // Get all services if available
                const serviceDetail = cells[3]?.querySelector('.service-detail');
                let allServicesHtml = '';
                if (serviceDetail) {
                    allServicesHtml = serviceDetail.cloneNode(true);
                    allServicesHtml.classList.add('show');
                    allServicesHtml = allServicesHtml.outerHTML;
                }

                let html = `
                    <h2 style="text-align: center; color: #0d6efd;">Discharge Receipt</h2>
                    <hr>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td style="padding: 8px 0;"><strong>Appointment:</strong></td><td style="text-align: right;">${cells[0]?.innerText || '-'}</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Patient:</strong></td><td style="text-align: right;">${cells[1]?.innerText || '-'}</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Doctor:</strong></td><td style="text-align: right;">${cells[2]?.innerText || '-'}</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Main Service:</strong></td><td style="text-align: right;">${mainService}</td></tr>
                    </table>
                    <hr>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td style="padding: 8px 0;"><strong>Total Price:</strong></td><td style="text-align: right;">${cells[4]?.innerText || '0'} MMK</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Discount:</strong></td><td style="text-align: right;">${cells[5]?.innerText || '0'} MMK</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Paid:</strong></td><td style="text-align: right;">${cells[6]?.innerText || '0'} MMK</td></tr>
                        <tr><td style="padding: 8px 0;"><strong>Balance:</strong></td><td style="text-align: right;">${cells[7]?.innerText || '-'}</td></tr>
                    </table>
                    ${allServicesHtml ? `<hr><div style="margin-top: 16px;">${allServicesHtml}</div>` : ''}
                    <hr>
                    <p style="text-align: right; color: #64748b; font-size: 11px;">${cells[8]?.innerText || '-'}</p>
                `;

                const w = window.open('', '_blank');
                w.document.write(`
                    <html>
                        <head>
                            <title>Receipt #${cells[0]?.innerText}</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    padding: 30px;
                                    max-width: 400px;
                                    margin: 0 auto;
                                    background: white;
                                }
                                h2 { margin-bottom: 20px; }
                                hr {
                                    border: none;
                                    border-top: 1px solid #e2e8f0;
                                    margin: 15px 0;
                                }
                                table { width: 100%; }
                                td { padding: 6px 0; }
                                .service-detail {
                                    background: #f8fafc;
                                    border-left: 3px solid #0d6efd;
                                    padding: 12px;
                                    border-radius: 8px;
                                    font-size: 12px;
                                }
                                .service-detail.show { display: block; }
                                .money-value { font-weight: 600; }
                                @media print {
                                    body { padding: 15px; }
                                }
                            </style>
                        </head>
                        <body>
                            ${html}
                            <script>
                                window.onload = function() {
                                    setTimeout(function() { window.print(); }, 500);
                                }
                            <\/script>
                        </body>
                    </html>
                `);
                w.document.close();
            }
        });
    </script>
@endsection