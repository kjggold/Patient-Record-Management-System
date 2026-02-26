@extends('layouts.app')

@section('title', 'Discharges | MediCore')

@section('content')

    <style>
        /* Stat Cards */
        .stat-card {
            background: hsl(201, 100%, 92%);
            border-radius: 12px;
            padding: 14px 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .05);
        }

        .stat-title {
            font-size: 11px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .table-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(52, 5, 5, 0.06);
        }

        .discharge-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .discharge-table th {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .03em;
            padding: 10px 6px;
            text-align: left;
            background: #98e4ff;
            color: #444141;
            font-weight: 600;
            white-space: nowrap;
        }

        .discharge-table td {
            padding: 12px 6px;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
            vertical-align: middle;
            word-break: break-word;
        }

        /* Column width specifications */
        .discharge-table th:nth-child(1), .discharge-table td:nth-child(1) { width: 8%; }
        .discharge-table th:nth-child(2), .discharge-table td:nth-child(2) { width: 10%; }
        .discharge-table th:nth-child(3), .discharge-table td:nth-child(3) { width: 10%; }
        .discharge-table th:nth-child(4), .discharge-table td:nth-child(4) { width: 18%; }
        .discharge-table th:nth-child(5), .discharge-table td:nth-child(5) { width: 8%; }
        .discharge-table th:nth-child(6), .discharge-table td:nth-child(6) { width: 8%; }
        .discharge-table th:nth-child(7), .discharge-table td:nth-child(7) { width: 8%; }
        .discharge-table th:nth-child(8), .discharge-table td:nth-child(8) { width: 10%; }
        .discharge-table th:nth-child(9), .discharge-table td:nth-child(9) { width: 10%; }
        .discharge-table th:nth-child(10), .discharge-table td:nth-child(10) { width: 8%; }

        /* Date column - show only date, no time */
        .discharge-table td:nth-child(9) {
            white-space: nowrap;
            font-size: 13px;
            color: #64748b;
        }

        /* Action button column */
        .discharge-table td:last-child {
            white-space: nowrap;
            text-align: center;
        }

        .action-btn {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 500;
            cursor: pointer;
        }

        .action-btn:hover {
            background: #2563eb;
        }

        /* Service badge styling */
        .service-badge {
            background: #e6f0ff;
            color: #0d6efd;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            display: inline-block;
            cursor: pointer;
        }

        .service-badge:hover {
            background: #0d6efd;
            color: white;
        }

        /* Service detail dropdown */
        .service-detail {
            background: white;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #3b82f6;
            padding: 8px;
            border-radius: 8px;
            font-size: 10px;
            display: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: absolute;
            z-index: 100;
            min-width: 180px;
            left: 100%;
            top: 0;
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
            font-size: 9px;
            color: #3b82f6;
            margin-left: 3px;
            cursor: pointer;
            background: #e6f0ff;
            padding: 2px 5px;
            border-radius: 10px;
            font-weight: 500;
            display: inline-block;
        }

        /* Filter section styles */
        .filter-input {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 11px;
            height: 32px;
        }

        .filter-button {
            background: white;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 11px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .clear-button {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 6px 8px;
            font-size: 11px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #475569;
            text-decoration: none;
        }

        .clear-button:hover {
            background: #e2e8f0;
        }

        /* Dropdown menu - fits within screen */
        .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
            z-index: 50;
            min-width: 160px;
            max-width: 200px;
            background: white;
            border-radius: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border: 1px solid #e2e8f0;
            padding: 4px 0;
            font-size: 11px;
        }

        /* Ensure dropdown stays within viewport on the right */
        @media (max-width: 1200px) {
            .dropdown-menu {
                right: 0;
                left: auto;
            }
        }

        .dropdown-item {
            width: 100%;
            text-align: left;
            padding: 6px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
            border: none;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
        }

        .dropdown-item.active {
            background: #eff6ff;
            color: #2563eb;
        }

        /* Page header */
        .page-title {
            font-size: 22px;
            font-weight: 600;
            color: #334155;
        }

        .page-subtitle {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
    </style>

    @php
        use App\Models\Discharge;
        use Illuminate\Support\Facades\DB;

        // Get filter parameters
        $dateType = request('date_type', 'all');
        $selectedDate = request('selected_date', now()->format('Y-m-d'));

        // Build query based on date type - using created_at
        $query = Discharge::query();

        if ($dateType != 'all') {
            if ($dateType == 'today') {
                $query->whereDate('created_at', today());
            } elseif ($dateType == 'yesterday') {
                $query->whereDate('created_at', today()->subDay());
            } elseif ($dateType == 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateType == 'month') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            } elseif ($dateType == 'custom') {
                $query->whereDate('created_at', $selectedDate);
            }
        }

        $discharges = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $totalDischarges = $discharges->count();
        $totalRevenue = 0;

        foreach ($discharges as $row) {
            $allServices = is_array($row->services) ? $row->services : json_decode($row->services, true);
            $totalServicesPrice = 0;

            if (!empty($allServices)) {
                foreach ($allServices as $service) {
                    if (is_array($service)) {
                        $totalServicesPrice += $service['price'] ?? 0;
                    }
                }
            } else {
                $totalServicesPrice = $row->service_price ?? 0;
            }

            $netTotal = $totalServicesPrice - ($row->discount ?? 0);
            $totalRevenue += $netTotal;
        }

        // Date display text
        $dateDisplay = '';
        if($dateType == 'today') {
            $dateDisplay = 'Today (' . today()->format('M j, Y') . ')';
        } elseif($dateType == 'yesterday') {
            $dateDisplay = 'Yesterday (' . today()->subDay()->format('M j, Y') . ')';
        } elseif($dateType == 'week') {
            $dateDisplay = 'This Week (' . now()->startOfWeek()->format('M j') . ' - ' . now()->endOfWeek()->format('M j, Y') . ')';
        } elseif($dateType == 'month') {
            $dateDisplay = 'This Month (' . now()->format('F Y') . ')';
        } elseif($dateType == 'custom') {
            $dateDisplay = \Carbon\Carbon::parse($selectedDate)->format('F j, Y');
        } else {
            $dateDisplay = 'All Time';
        }
    @endphp

    <div class="app flex min-h-screen">
        @include('layouts.sidebar')
        <main class="flex-1 p-4 ml-60">
            <div class="mb-4">
                <h1 class="page-title">Discharged Patients</h1>
                <p class="page-subtitle">Completed appointments and payments</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
                <div class="stat-card">
                    <div class="stat-title">Total Discharges</div>
                    <div id="statTotal" class="stat-value">{{ number_format($totalDischarges) }}</div>
                    @if($dateType != 'all')
                        <div class="text-xs text-slate-500 mt-1">{{ $dateDisplay }}</div>
                    @endif
                </div>
                <div class="stat-card">
                    <div class="stat-title">Total Revenue</div>
                    <div id="statRevenue" class="stat-value">{{ number_format($totalRevenue) }} MMK</div>
                    @if($dateType != 'all')
                        <div class="text-xs text-slate-500 mt-1">{{ $dateDisplay }}</div>
                    @endif
                </div>
                <div class="stat-card">
                    <div class="stat-title">Search & Date</div>
                    <div class="flex flex-col gap-2 mt-1">
                        {{-- Search and Dropdown row --}}
                        <div class="flex gap-2">
                            {{-- Search Input --}}
                            <input id="searchInput" type="text" placeholder="Patient or ID"
                                class="filter-input w-1/2" value="{{ request('search') }}">

                            {{-- Date Type Dropdown --}}
                            <div class="relative w-1/2" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" type="button"
                                        class="filter-button">
                                    <span>{{ $dateType == 'all' ? 'All Time' : ($dateType == 'custom' ? 'Custom' : ucfirst($dateType)) }}</span>
                                    <svg :class="{'rotate-180': open}" class="w-3 h-3 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                {{-- Dropdown Menu - positioned to fit screen --}}
                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="dropdown-menu">
                                    <form method="GET" action="{{ route('discharge.index') }}">
                                        <input type="hidden" name="search" value="{{ request('search') }}">

                                        {{-- All Time --}}
                                        <button type="submit" name="date_type" value="all"
                                                class="dropdown-item {{ $dateType == 'all' ? 'active' : '' }}">
                                            All Time
                                        </button>

                                        {{-- Today --}}
                                        <button type="submit" name="date_type" value="today"
                                                class="dropdown-item {{ $dateType == 'today' ? 'active' : '' }}">
                                            <span>Today</span>
                                            <span class="text-gray-500">{{ today()->format('M j') }}</span>
                                        </button>

                                        {{-- Yesterday --}}
                                        <button type="submit" name="date_type" value="yesterday"
                                                class="dropdown-item {{ $dateType == 'yesterday' ? 'active' : '' }}">
                                            <span>Yesterday</span>
                                            <span class="text-gray-500">{{ today()->subDay()->format('M j') }}</span>
                                        </button>

                                        {{-- This Week --}}
                                        <button type="submit" name="date_type" value="week"
                                                class="dropdown-item {{ $dateType == 'week' ? 'active' : '' }}">
                                            <span>This Week</span>
                                            <span class="text-gray-500">{{ now()->startOfWeek()->format('M j') }}-{{ now()->endOfWeek()->format('j') }}</span>
                                        </button>

                                        {{-- This Month --}}
                                        <button type="submit" name="date_type" value="month"
                                                class="dropdown-item {{ $dateType == 'month' ? 'active' : '' }}">
                                            <span>This Month</span>
                                            <span class="text-gray-500">{{ now()->format('M Y') }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Calendar and Clear row - SWITCHED: Clear on left, Calendar on right --}}
                        <div class="flex gap-2">
                            {{-- Clear Filter Button (now on left) --}}
                            @if(request()->anyFilled(['search', 'selected_date']) || $dateType != 'all')
                                <a href="{{ route('discharge.index') }}" class="clear-button w-1/2">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </a>
                            @else
                                <div class="w-1/2"></div> {{-- Placeholder when no clear button --}}
                            @endif

                            {{-- Calendar Date Filter (now on right) --}}
                            <input id="dateFilter" type="date"
                                   class="filter-input w-1/2"
                                   value="{{ request('selected_date', date('Y-m-d')) }}"
                                   onchange="window.location.href='{{ route('discharge.index') }}?date_type=custom&selected_date=' + this.value + '&search=' + document.getElementById('searchInput').value">
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="overflow-x-auto">
                    <table class="min-w-full discharge-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Disc</th>
                                <th>Paid</th>
                                <th>Balance</th>
                                <th>Date</th>
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

                                    // Calculate total price from all services
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

                                    // Calculate balance
                                    $netTotal = $displayPrice - ($row->discount ?? 0);
                                    $balanceAmount = ($row->paid ?? 0) - $netTotal;

                                    if ($balanceAmount > 0) {
                                        $balanceText = 'Change: ' . number_format(abs($balanceAmount), 0);
                                    } elseif ($balanceAmount < 0) {
                                        $balanceText = 'Due: ' . number_format(abs($balanceAmount), 0);
                                    } else {
                                        $balanceText = 'Settled';
                                    }

                                    // Format date - only show date (no time)
                                    $formattedDate = $row->created_at ? $row->created_at->format('m/d/Y') : '-';
                                @endphp

                                <tr id="discharge_{{ $row->id }}">
                                    <td class="text-xs">{{ $row->appointment_id ?? $row->id }}</td>
                                    <td class="text-xs">{{ \Str::limit($row->patient_name ?? '-', 15) }}</td>
                                    <td class="text-xs">{{ \Str::limit($row->doctor_name ?? '-', 15) }}</td>
                                    <td>
                                        <div class="service-container">
                                            <div style="display: flex; align-items: center; flex-wrap: wrap;">
                                                <span class="service-badge">{{ \Str::limit($serviceName, 12) }}</span>
                                                @if($hasMultipleServices)
                                                    <span class="view-services-link" onclick="toggleServices('srv_{{ $row->id }}')">
                                                        +{{ count($allServices) - 1 }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if($hasMultipleServices)
                                                <div id="srv_{{ $row->id }}" class="service-detail">
                                                    <div style="font-weight: 600; margin-bottom: 6px; color: #2563eb;">All Services</div>
                                                    @foreach($allServices as $service)
                                                        @php
                                                            $sName = is_array($service) ? ($service['name'] ?? 'Unknown') : $service;
                                                            $sPrice = is_array($service) ? ($service['price'] ?? 0) : 0;
                                                        @endphp
                                                        <div style="display: flex; justify-content: space-between; margin-top: 4px;">
                                                            <span>{{ \Str::limit($sName, 18) }}</span>
                                                            <span class="money-value">{{ number_format($sPrice, 0) }}</span>
                                                        </div>
                                                    @endforeach
                                                    <div style="margin-top: 6px; padding-top: 4px; border-top: 1px dashed #cbd5e1; font-weight: 600; display: flex; justify-content: space-between;">
                                                        <span>Total:</span>
                                                        <span class="money-value">{{ number_format($totalAllServicesPrice, 0) }} MMK</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-xs">
                                        <span class="money-value">{{ number_format($displayPrice, 0) }}</span>
                                    </td>
                                    <td class="text-xs">{{ number_format($row->discount ?? 0, 0) }}</td>
                                    <td class="text-xs">{{ number_format($row->paid ?? 0, 0) }}</td>
                                    <td class="text-xs">{{ $balanceText }}</td>
                                    <td class="text-xs">{{ $formattedDate }}</td>
                                    <td>
                                        <button class="action-btn" onclick="printSingle({{ $row->id }})">Print</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-6 text-slate-500 text-xs">
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

            // Live search with debounce
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', this.value);
                    url.searchParams.set('date_type', '{{ $dateType }}');
                    if ('{{ $selectedDate }}') {
                        url.searchParams.set('selected_date', '{{ $selectedDate }}');
                    }
                    window.location.href = url.toString();
                }, 500);
            });

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
                    <html>
                        <head>
                            <title>Receipt #${cells[0]?.innerText}</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                    padding: 20px;
                                    max-width: 350px;
                                    margin: 0 auto;
                                    background: white;
                                    font-size: 11px;
                                }
                                h2 { text-align: center; color: #2563eb; margin-bottom: 15px; font-size: 14px; }
                                hr { border: none; border-top: 1px solid #e2e8f0; margin: 8px 0; }
                                table { width: 100%; }
                                td { padding: 4px 0; }
                                .service-detail {
                                    background: #f8fafc;
                                    border-left: 3px solid #2563eb;
                                    padding: 8px;
                                    border-radius: 6px;
                                    font-size: 10px;
                                }
                                .service-detail.show { display: block; }
                                .money-value { font-weight: 600; }
                            </style>
                        </head>
                        <body>
                            <h2>Discharge Receipt</h2>
                            <hr>
                            <table>
                                <tr><td><strong>Appointment:</strong></td><td style="text-align: right;">${cells[0]?.innerText || '-'}</td></tr>
                                <tr><td><strong>Patient:</strong></td><td style="text-align: right;">${cells[1]?.innerText || '-'}</td></tr>
                                <tr><td><strong>Doctor:</strong></td><td style="text-align: right;">${cells[2]?.innerText || '-'}</td></tr>
                                <tr><td><strong>Main Service:</strong></td><td style="text-align: right;">${mainService}</td></tr>
                                <tr><td><strong>Date:</strong></td><td style="text-align: right;">${cells[8]?.innerText || '-'}</td></tr>
                            </table>
                            <hr>
                            <table>
                                <tr><td><strong>Total Price:</strong></td><td style="text-align: right;">${cells[4]?.innerText || '0'} MMK</td></tr>
                                <tr><td><strong>Discount:</strong></td><td style="text-align: right;">${cells[5]?.innerText || '0'} MMK</td></tr>
                                <tr><td><strong>Paid:</strong></td><td style="text-align: right;">${cells[6]?.innerText || '0'} MMK</td></tr>
                                <tr><td><strong>Balance:</strong></td><td style="text-align: right;">${cells[7]?.innerText || '-'}</td></tr>
                            </table>
                            ${allServicesHtml ? `<hr><div>${allServicesHtml}</div>` : ''}
                            <hr>
                        </body>
                    </html>
                `;

                const iframe = document.createElement('iframe');
                iframe.style.position = 'absolute';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                document.body.appendChild(iframe);

                const iframeDoc = iframe.contentWindow.document;
                iframeDoc.open();
                iframeDoc.write(html);
                iframeDoc.close();

                iframe.contentWindow.focus();
                iframe.contentWindow.print();

                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 1000);
            }
        });
    </script>
@endsection