@extends('layouts.app')

@section('title', $patient->full_name . ' - Patient Details')

@section('content')
{{-- Updated debug section --}}
@php
    // Check which variable exists and use appropriate one
    $historyData = isset($allHistory) ? $allHistory : (isset($appointments) ? $appointments : collect());

    // Calculate total appointments correctly
    $totalAppointments = 0;
    if(isset($paginatedAppointments)) {
        $totalAppointments = $paginatedAppointments->total();
    } elseif(isset($allHistory)) {
        $totalAppointments = count($allHistory);
    } elseif(isset($appointments)) {
        $totalAppointments = $appointments->total();
    }
@endphp
<div class="app flex min-h-screen">
    {{-- Side bar --}}
    @include('layouts.sidebar')

    {{-- Main Content --}}
    <div class="flex-1 p-6 bg-gray-50 ml-60">
        {{-- Header with Back Button --}}
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('patient-history.index') }}"
                       class="mr-4 text-sky-600 hover:text-sky-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $patient->full_name }}'s History</h1>
                        <p class="text-sm text-gray-600 mt-1">Patient ID: {{ $patient->id }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-sky-100 text-sky-700 text-xs font-medium rounded-full">
                        Total Visits: {{ $totalAppointments }}
                    </span>

                    {{-- Toggle View Buttons --}}
                    <button onclick="showCardsView()" id="cardsBtn"
                       class="px-4 py-2 bg-sky-600 text-white text-sm font-medium rounded-lg hover:bg-sky-400 transition flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Information
                    </button>

                    <button onclick="showAppointmentsView()" id="appointmentsBtn"
                       class="px-4 py-2 border border-sky-600 text-sky-600 text-sm font-medium rounded-lg hover:bg-sky-400 transition flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Appointments
                    </button>

                    <a href="{{ route('patient-history.download-report', $patient) }}"
                       class="px-4 py-2 border border-green-600 text-green-600 text-sm font-medium rounded-lg hover:bg-green-50 transition flex items-center shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4"></path>
                        </svg>
                        Download Report
                    </a>
                </div>
            </div>
        </div>

        {{-- Cards View Section --}}
        <div id="cardsView" class="view-section">
            {{-- Patient Details Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 max-w-6xl mx-auto">
                {{-- Personal Information Card --}}
                <div>
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800">Personal Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Full Name</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $patient->full_name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Date of Birth</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'Not specified' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Age</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $patient->age ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Gender</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $patient->sex_gender ? ucfirst($patient->sex_gender) : 'N/A' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Phone Number</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $patient->phone_number ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Email</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $patient->email ?? 'Not provided' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Address</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $patient->address ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Registration Date</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $patient->registration_date ? \Carbon\Carbon::parse($patient->registration_date)->format('F j, Y') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Medical Information Card --}}
                <div>
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800">Medical Information</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Blood Type</p>
                                    <p class="text-sm font-medium text-gray-800">{{ $patient->blood_type ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Alcohol Consumption</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $patient->alcohol_consumption ? ucfirst($patient->alcohol_consumption) : 'None' }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Assigned Doctor</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        @if($patient->doctor)
                                            Dr. {{ $patient->doctor->full_name }}
                                            @if($patient->doctor->speciality)
                                                <span class="text-gray-500">({{ $patient->doctor->speciality }})</span>
                                            @endif
                                        @else
                                            Not Assigned
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Last Visit</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        @php
                                            $historyData = isset($allHistory) ? $allHistory : (isset($appointments) ? $appointments : collect());
                                        @endphp
                                        @if($historyData->isNotEmpty())
                                            @if(isset($allHistory))
                                                {{ \Carbon\Carbon::parse($allHistory->first()['date'])->format('M d, Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($appointments->first()->appointment_date)->format('M d, Y') }}
                                            @endif
                                        @else
                                            No visits yet
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Medical History Card (Conditions & Allergies) --}}
                <div>
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <h2 class="text-lg font-semibold text-gray-800">Medical History</h2>
                        </div>
                        <div class="p-6">
                            {{-- Medical Conditions Section --}}
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Medical Conditions</h3>
                                @if($patient->known_medical_conditions && trim($patient->known_medical_conditions) !== '')
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $patient->known_medical_conditions) as $condition)
                                            @if(trim($condition))
                                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">
                                                    {{ trim($condition) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">None</p>
                                @endif
                            </div>

                            {{-- Allergies Section --}}
                            <div>
                                <h3 class="text-sm font-semibold text-gray-700 mb-3">Allergies</h3>
                                @if($patient->allergies && trim($patient->allergies) !== '')
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(explode(',', $patient->allergies) as $allergy)
                                            @if(trim($allergy))
                                                <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                                    {{ trim($allergy) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">None</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Appointments View Section with Pagination --}}
        <div id="appointmentsView" class="view-section hidden">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">Appointment History</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-sky-50 to-indigo-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if(isset($paginatedAppointments))
                                @forelse($paginatedAppointments as $index => $appointment)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                            {{ $paginatedAppointments->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $appointment->doctor->full_name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $displayStatus = $appointment->status == 'scheduled' ? 'pending' : $appointment->status;
                                                $statusColors = [
                                                    'pending' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-blue-100 text-blue-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                    'no-show' => 'bg-gray-100 text-gray-800',
                                                    'discharged' => 'bg-red-100 text-red-800',
                                                ];
                                                $statusColor = $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ ucfirst($displayStatus) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <h3 class="text-lg font-medium text-gray-700 mb-2">No appointment history found</h3>
                                            <p class="text-gray-500">This patient has no appointment records.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            @elseif(isset($allHistory))
                                @php
                                    $perPage = 10;
                                    $currentPage = request('page', 1);
                                    $collection = collect($allHistory);
                                    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                                        $collection->forPage($currentPage, $perPage),
                                        $collection->count(),
                                        $perPage,
                                        $currentPage,
                                        ['path' => url()->current()]
                                    );
                                @endphp
                                @forelse($paginated as $index => $record)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                            {{ $paginated->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($record['date'])->format('M j, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record['doctor_name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $record['service_name'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $displayStatus = $record['status'] == 'scheduled' ? 'pending' : $record['status'];
                                                $statusColors = [
                                                    'pending' => 'bg-green-100 text-green-800',
                                                    'completed' => 'bg-blue-100 text-blue-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                    'no-show' => 'bg-gray-100 text-gray-800',
                                                    'discharged' => 'bg-red-100 text-red-800',
                                                ];
                                                $statusColor = $statusColors[$displayStatus] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                                {{ ucfirst($displayStatus) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <h3 class="text-lg font-medium text-gray-700 mb-2">No appointment history found</h3>
                                            <p class="text-gray-500">This patient has no appointment records.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination - Separated from table --}}
            @if(isset($paginatedAppointments) && $paginatedAppointments->hasPages())
            <div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <!-- Showing info -->
                    <div class="text-sm text-gray-600">
                        Showing {{ $paginatedAppointments->firstItem() }} to {{ $paginatedAppointments->lastItem() }} of {{ $paginatedAppointments->total() }} {{ Str::plural('appointment', $paginatedAppointments->total()) }}
                    </div>

                    <!-- Pagination Links -->
                    <div class="flex items-center gap-1">
                        <!-- Previous Page Link -->
                        @if ($paginatedAppointments->onFirstPage())
                            <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </span>
                        @else
                            <a href="{{ $paginatedAppointments->previousPageUrl() }}&view=appointments"
                               class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                        @endif

                        <!-- Dynamic Page Numbers -->
                        @php
                            $currentPage = $paginatedAppointments->currentPage();
                            $lastPage = $paginatedAppointments->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);

                            // Adjust to show more pages if needed
                            if ($startPage > 1) {
                                $endPage = min($lastPage, $startPage + 4);
                            }
                            if ($endPage < $lastPage) {
                                $startPage = max(1, $endPage - 4);
                            }
                        @endphp

                        <!-- First page -->
                        @if ($startPage > 1)
                            <a href="{{ $paginatedAppointments->url(1) }}&view=appointments"
                               class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                1
                            </a>
                            @if ($startPage > 2)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                        @endif

                        <!-- Page Numbers -->
                        @for ($page = $startPage; $page <= $endPage; $page++)
                            @if ($page == $currentPage)
                                <span class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginatedAppointments->url($page) }}&view=appointments"
                                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    {{ $page }}
                                </a>
                            @endif
                        @endfor

                        <!-- Last page -->
                        @if ($endPage < $lastPage)
                            @if ($endPage < $lastPage - 1)
                                <span class="px-2 text-gray-400">...</span>
                            @endif
                            <a href="{{ $paginatedAppointments->url($lastPage) }}&view=appointments"
                               class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                {{ $lastPage }}
                            </a>
                        @endif

                        <!-- Next Page Link -->
                        @if ($paginatedAppointments->hasMorePages())
                            <a href="{{ $paginatedAppointments->nextPageUrl() }}&view=appointments"
                               class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @else
                            <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            @elseif(isset($allHistory))
                @php
                    $perPage = 10;
                    $currentPage = request('page', 1);
                    $collection = collect($allHistory);
                    $paginated = new \Illuminate\Pagination\LengthAwarePaginator(
                        $collection->forPage($currentPage, $perPage),
                        $collection->count(),
                        $perPage,
                        $currentPage,
                        ['path' => url()->current()]
                    );
                @endphp
                @if($paginated->hasPages())
                <div class="mt-6 bg-white rounded-xl shadow px-4 py-4 border-t">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <!-- Showing info -->
                        <div class="text-sm text-gray-600">
                            Showing {{ $paginated->firstItem() }} to {{ $paginated->lastItem() }} of {{ $paginated->total() }} {{ Str::plural('appointment', $paginated->total()) }}
                        </div>

                        <!-- Pagination Links -->
                        <div class="flex items-center gap-1">
                            <!-- Previous Page Link -->
                            @if ($paginated->onFirstPage())
                                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $paginated->previousPageUrl() }}&view=appointments"
                                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </a>
                            @endif

                            <!-- Dynamic Page Numbers -->
                            @php
                                $currentPage = $paginated->currentPage();
                                $lastPage = $paginated->lastPage();
                                $startPage = max(1, $currentPage - 2);
                                $endPage = min($lastPage, $currentPage + 2);

                                if ($startPage > 1) {
                                    $endPage = min($lastPage, $startPage + 4);
                                }
                                if ($endPage < $lastPage) {
                                    $startPage = max(1, $endPage - 4);
                                }
                            @endphp

                            <!-- First page -->
                            @if ($startPage > 1)
                                <a href="{{ $paginated->url(1) }}&view=appointments"
                                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    1
                                </a>
                                @if ($startPage > 2)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                            @endif

                            <!-- Page Numbers -->
                            @for ($page = $startPage; $page <= $endPage; $page++)
                                @if ($page == $currentPage)
                                    <span class="px-3 py-1.5 rounded border bg-sky-600 text-white font-medium border-sky-600 text-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $paginated->url($page) }}&view=appointments"
                                       class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endfor

                            <!-- Last page -->
                            @if ($endPage < $lastPage)
                                @if ($endPage < $lastPage - 1)
                                    <span class="px-2 text-gray-400">...</span>
                                @endif
                                <a href="{{ $paginated->url($lastPage) }}&view=appointments"
                                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    {{ $lastPage }}
                                </a>
                            @endif

                            <!-- Next Page Link -->
                            @if ($paginated->hasMorePages())
                                <a href="{{ $paginated->nextPageUrl() }}&view=appointments"
                                   class="px-3 py-1.5 rounded border text-gray-600 hover:bg-sky-50 hover:border-sky-300 text-sm">
                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <span class="px-3 py-1.5 rounded border text-gray-400 cursor-not-allowed text-sm">
                                    <svg class="w-3 h-3 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .app {
        background: linear-gradient(135deg, #a7bad7 0%, #e4e8f0 100%);
        min-height: 100vh;
    }

    /* View transitions */
    .view-section {
        transition: opacity 0.3s ease-in-out;
    }

    .view-section.hidden {
        display: none;
    }

    /* Dark mode styles */
    .dark-mode {
        background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    }

    .dark-mode .bg-white {
        background-color: #2d3748;
        border-color: #4a5568;
    }

    .dark-mode .text-gray-800 {
        color: #f7fafc;
    }

    .dark-mode .text-gray-500 {
        color: #cbd5e0;
    }

    .dark-mode .bg-gray-50 {
        background-color: #1a202c;
    }

    .dark-mode .bg-gradient-to-r {
        background: #2d3748 !important;
    }

    .dark-mode .bg-gray-100 {
        background-color: #4a5568;
    }

    .dark-mode .divide-gray-200 > * {
        border-color: #4a5568;
    }

    .dark-mode .hover\:bg-gray-50:hover {
        background-color: #374151;
    }

    .dark-mode .border-gray-300 {
        border-color: #4a5568;
    }

    .dark-mode .border {
        border-color: #4a5568;
    }

    .dark-mode .text-gray-600 {
        color: #cbd5e0;
    }

    .dark-mode .text-gray-700 {
        color: #e2e8f0;
    }

    .dark-mode .bg-sky-50 {
        background-color: #1e3a5f;
    }

    .dark-mode .text-sky-600 {
        color: #7dd3fc;
    }

    .dark-mode .hover\:bg-sky-50:hover {
        background-color: #1e3a5f;
    }

    .dark-mode .hover\:border-sky-300:hover {
        border-color: #7dd3fc;
    }
</style>
@endpush

@push('scripts')
<script>
    // View toggle functions
    function showCardsView() {
        document.getElementById('cardsView').classList.remove('hidden');
        document.getElementById('appointmentsView').classList.add('hidden');

        // Update button styles
        document.getElementById('cardsBtn').classList.add('bg-sky-600', 'text-white');
        document.getElementById('cardsBtn').classList.remove('border', 'border-sky-600', 'text-sky-600');

        document.getElementById('appointmentsBtn').classList.remove('bg-sky-600', 'text-white');
        document.getElementById('appointmentsBtn').classList.add('border', 'border-sky-600', 'text-sky-600');

        // Update URL without page reload
        const url = new URL(window.location);
        url.searchParams.delete('view');
        url.searchParams.delete('page'); // Reset page when switching views
        window.history.pushState({}, '', url);
    }

    function showAppointmentsView() {
        document.getElementById('cardsView').classList.add('hidden');
        document.getElementById('appointmentsView').classList.remove('hidden');

        // Update button styles
        document.getElementById('appointmentsBtn').classList.add('bg-sky-600', 'text-white');
        document.getElementById('appointmentsBtn').classList.remove('border', 'border-sky-600', 'text-sky-600');

        document.getElementById('cardsBtn').classList.remove('bg-sky-600', 'text-white');
        document.getElementById('cardsBtn').classList.add('border', 'border-sky-600', 'text-sky-600');

        // Update URL without page reload
        const url = new URL(window.location);
        url.searchParams.set('view', 'appointments');
        window.history.pushState({}, '', url);
    }

    // Dark/Light mode toggle
    function toggleTheme() {
        document.body.classList.toggle('dark-mode');
        const isDarkMode = document.body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
    }

    // Load saved theme preference and view state
    document.addEventListener('DOMContentLoaded', function() {
        // Load theme
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            document.body.classList.add('dark-mode');
        }

        // Check URL for view parameter
        const urlParams = new URLSearchParams(window.location.search);
        const view = urlParams.get('view');

        if (view === 'appointments') {
            // Small delay to ensure DOM is ready
            setTimeout(() => {
                showAppointmentsView();
            }, 10);
        } else {
            showCardsView();
        }
    });

    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const view = urlParams.get('view');

        if (view === 'appointments') {
            showAppointmentsView();
        } else {
            showCardsView();
        }
    });
</script>
@endpush