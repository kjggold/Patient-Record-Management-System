@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    @php
        use App\Models\Patient;
        use App\Models\Appointment;
        use Illuminate\Support\Facades\DB;
        use Carbon\Carbon;
        use Illuminate\Support\Str;

        $dateType = request('date_type', 'all');
        $selectedDate = request('selected_date', now()->format('Y-m-d'));

        if ($dateType === 'all') {
            $totalPatients = Patient::count();
        } else {
            $totalPatients = Appointment::where(function ($query) use ($dateType, $selectedDate) {
                if ($dateType === 'today') {
                    $query->whereDate('appointment_date', today());
                } elseif ($dateType === 'yesterday') {
                    $query->whereDate('appointment_date', today()->subDay());
                } elseif ($dateType === 'week') {
                    $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType === 'month') {
                    $query->whereMonth('appointment_date', now()->month);
                } elseif ($dateType === 'custom') {
                    $query->whereDate('appointment_date', $selectedDate);
                }
            })
                ->distinct('patient_id')
                ->count('patient_id');
        }

        $dateDisplay = '';
        if ($dateType === 'today') {
            $dateDisplay = 'Today (' . today()->format('M j, Y') . ')';
        } elseif ($dateType === 'yesterday') {
            $dateDisplay = 'Yesterday (' . today()->subDay()->format('M j, Y') . ')';
        } elseif ($dateType === 'week') {
            $dateDisplay =
                'This Week (' .
                now()->startOfWeek()->format('M j') .
                ' - ' .
                now()->endOfWeek()->format('M j, Y') .
                ')';
        } elseif ($dateType === 'month') {
            $dateDisplay = 'This Month (' . now()->format('F Y') . ')';
        } elseif ($dateType === 'custom') {
            $dateDisplay = Carbon::parse($selectedDate)->format('F j, Y');
        } else {
            $dateDisplay = 'All Time';
        }
    @endphp

    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            {{-- Header --}}
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Patient History</h1>
                    </div>

                    {{-- Buttons & Filter --}}
                    <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                        {{-- Total Patients --}}
                        <div
                            class="bg-gradient-to-r from-sky-500/20 to-sky-600/20 backdrop-blur-sm rounded-lg shadow border border-sky-200/50 px-3 py-2 min-w-[160px] relative z-10">
                            <div class="flex flex-col">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-xs font-medium text-sky-800">Total Patients</p>
                                        <p class="text-lg font-bold text-sky-900">{{ $totalPatients }}</p>
                                    </div>
                                    <p class="text-xs text-sky-700 font-medium">
                                        {{ $dateType == 'all' ? 'All Time' : \Carbon\Carbon::parse($selectedDate)->format('M j') }}
                                    </p>
                                </div>

                                <div class="mt-1 pt-2 border-t border-sky-200/50">
                                    <p class="text-xs text-sky-600 truncate">{{ $dateDisplay }}</p>
                                </div>

                                @if (request()->anyFilled(['search', 'selected_date']) || $dateType != 'all')
                                    <div class="mt-2 pt-2 border-t border-sky-200/50">
                                        <a href="{{ route('patient-history.index') }}"
                                            class="text-xs text-sky-700 hover:text-sky-900 transition flex items-center justify-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Clear Filter
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Date Filter --}}
                        <div class="flex flex-col gap-1.5">
                            <div class="relative w-40" x-data="{ open: false }" @click.away="open = false">
                                <button @click="open = !open" type="button"
                                    class="w-full px-3 py-1.5 text-sm bg-gradient-to-r from-sky-500/20 to-sky-600/20 backdrop-blur-sm border border-sky-200/50 rounded-lg transition flex items-center justify-between {{ $dateType == 'all' ? 'text-sky-800 font-medium' : 'text-sky-700 hover:from-sky-500/30 hover:to-sky-600/30' }}">
                                    <span>{{ $dateType == 'all' ? 'All Time' : 'Filter by Date' }}</span>
                                    <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                    <form method="GET" action="{{ route('patient-history.index') }}" class="space-y-1">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        @foreach (['today', 'yesterday', 'week', 'month', 'all'] as $dt)
                                            <button type="submit" name="date_type" value="{{ $dt }}"
                                                class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between {{ $dateType == $dt ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                                <span>{{ ucfirst($dt == 'week' ? 'This Week' : ($dt == 'all' ? 'All Time' : $dt)) }}</span>
                                                @if ($dt !== 'all')
                                                    <span
                                                        class="text-xs text-gray-500">{{ (($dt == 'today' ? today()->format('M j') : $dt == 'yesterday') ? today()->subDay()->format('M j') : $dt == 'week') ? now()->startOfWeek()->format('M j') . '-' . now()->endOfWeek()->format('j') : now()->format('M Y') }}</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </form>
                                </div>
                            </div>

                            <form method="GET" action="{{ route('patient-history.index') }}" class="w-40">
                                <div class="relative">
                                    <input type="date" name="selected_date" value="{{ $selectedDate }}"
                                        class="w-full px-3 py-1.5 text-sm border border-sky-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500 bg-white"
                                        onchange="this.form.submit()">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="date_type" value="custom">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="flex justify-between items-center mb-6 gap-3">
                <form method="GET" action="{{ route('patient-history.index') }}" class="flex items-center gap-2"
                    id="searchForm">
                    <input type="text" name="search" placeholder="Search by name, phone, ID, doctor..."
                        class="border border-sky-300 rounded px-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-sky-500"
                        value="{{ request('search') ?? '' }}">
                    <input type="hidden" name="date_type" value="{{ $dateType }}">
                    <input type="hidden" name="selected_date" value="{{ $selectedDate }}">
                    <button type="submit" class="px-4 py-2 bg-sky-600 text-white rounded hover:bg-sky-700">Search</button>
                </form>
            </div>

            {{-- Patient Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6"
                id="patientCardsContainer">
                @forelse($patients as $patient)
                    @php
                        $appointmentsQuery = Appointment::where('patient_id', $patient->id);
                        if ($dateType != 'all') {
                            $appointmentsQuery->where(function ($q) use ($dateType, $selectedDate) {
                                if ($dateType == 'today') {
                                    $q->whereDate('appointment_date', today());
                                } elseif ($dateType == 'yesterday') {
                                    $q->whereDate('appointment_date', today()->subDay());
                                } elseif ($dateType == 'week') {
                                    $q->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                                } elseif ($dateType == 'month') {
                                    $q->whereMonth('appointment_date', now()->month);
                                } elseif ($dateType == 'custom') {
                                    $q->whereDate('appointment_date', $selectedDate);
                                }
                            });
                        }
                        $appointmentsCount = $appointmentsQuery->count();
                        $lastAppointment = $appointmentsQuery->latest('appointment_date')->first();
                    @endphp

                    @if ($dateType == 'all' || $appointmentsCount > 0)
                        <div class="patient-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full"
                            data-id="{{ $patient->id }}" data-name="{{ strtolower($patient->full_name) }}"
                            data-phone="{{ strtolower($patient->phone_number ?? '') }}"
                            data-doctor="{{ strtolower($patient->doctor->full_name ?? '') }}"
                            data-address="{{ strtolower($patient->address ?? '') }}"
                            data-blood="{{ strtolower($patient->blood_type ?? '') }}"
                            data-age="{{ $patient->age ?? '' }}">
                            {{-- Patient Card Content --}}
                            <div class="patient-card bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full"
                                data-id="{{ $patient->id }}" data-name="{{ strtolower($patient->full_name) }}"
                                data-phone="{{ strtolower($patient->phone_number ?? '') }}"
                                data-doctor="{{ strtolower($patient->doctor->full_name ?? '') }}"
                                data-address="{{ strtolower($patient->address ?? '') }}"
                                data-blood="{{ strtolower($patient->blood_type ?? '') }}"
                                data-age="{{ $patient->age ?? '' }}">

                                {{-- Patient Card Header --}}
                                <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $patient->full_name }}</h2>
                                    <span class="text-sm text-gray-500">{{ $appointmentsCount }}
                                        {{ Str::plural('Appointment', $appointmentsCount) }}</span>
                                </div>

                                {{-- Patient Details --}}
                                <div class="px-4 py-3 flex-1 flex flex-col justify-between gap-2">
                                    <p class="text-sm text-gray-600"><strong>Phone:</strong>
                                        {{ $patient->phone_number ?? '-' }}</p>
                                    <p class="text-sm text-gray-600"><strong>Doctor:</strong>
                                        {{ $patient->doctor->full_name ?? '-' }}</p>
                                    <p class="text-sm text-gray-600"><strong>Blood Type:</strong>
                                        {{ $patient->blood_type ?? '-' }}</p>
                                    <p class="text-sm text-gray-600"><strong>Age:</strong> {{ $patient->age ?? '-' }}</p>
                                    @if ($lastAppointment)
                                        <p class="text-sm text-gray-500"><strong>Last Appointment:</strong>
                                            {{ \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('M j, Y') }}
                                    @endif
                                </div>

                                {{-- Optional Actions --}}
                                <div class="px-4 py-3 border-t border-gray-200 flex justify-end gap-2">
                                    <a href="{{ route('patient-history.show', $patient->id) }}"
                                        class="px-3 py-1 text-sm text-white bg-sky-600 rounded hover:bg-sky-700">View
                                        Details</a>
                                </div>
                            </div>

                        </div>
                    @endif
                @empty
                    <div
                        class="col-span-1 sm:col-span-2 lg:grid-cols-3 xl:col-span-4 bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">
                            @if ($dateType == 'all')
                                No patients found
                            @else
                                No appointments found for {{ $dateDisplay }}
                            @endif
                        </h3>
                        <p class="text-gray-500 mb-4">
                            @if ($dateType == 'all')
                                No patients are registered in the system.
                            @else
                                No patients have appointments scheduled for this date.
                            @endif
                        </p>
                        @if ($dateType != 'all')
                            <a href="{{ route('patient-history.index') }}"
                                class="inline-block px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition shadow-sm mr-2">Show
                                All Patients</a>
                        @endif
                        <a href="{{ route('patient-history.index', ['date_type' => 'all']) }}"
                            class="inline-block px-4 py-2 border border-sky-600 text-sky-600 rounded-lg hover:bg-sky-50 transition">Clear
                            Date Filter</a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($patients->hasPages() || $patients->total() > 0)
                @php
                    $current = $patients->currentPage();
                    $last = $patients->lastPage();
                    $range = 2;
                @endphp
                <div class="mt-6">
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex flex-col md:flex-row items-center justify-between">
                        <p class="text-sm text-gray-700 mb-4 md:mb-0">
                            Showing <span class="font-medium">{{ ($patients->currentPage() - 1) * 12 + 1 }}</span> to
                            <span class="font-medium">{{ min($patients->currentPage() * 12, $patients->total()) }}</span>
                            of
                            <span class="font-medium">{{ $patients->total() }}</span> patients (12 per page)
                        </p>
                        @if ($patients->hasPages())
                            <nav class="flex items-center space-x-1">
                                <a href="{{ $patients->previousPageUrl() }}"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 {{ $patients->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    <span class="sr-only">Previous</span>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                @for ($page = 1; $page <= $last; $page++)
                                    @if ($page == 1 || $page == $last || ($page >= $current - $range && $page <= $current + $range))
                                        <a href="{{ $patients->url($page) }}"
                                            class="px-3 py-2 text-sm font-medium border border-gray-300 {{ $page == $current ? 'bg-sky-50 text-sky-600 border-sky-300' : 'bg-white text-gray-500 hover:bg-gray-100' }}">{{ $page }}</a>
                                    @elseif($page == $current - ($range + 1) || $page == $current + ($range + 1))
                                        <span class="px-3 py-2 text-sm font-medium text-gray-500">...</span>
                                    @endif
                                @endfor
                                <a href="{{ $patients->nextPageUrl() }}"
                                    class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 {{ !$patients->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    <span class="sr-only">Next</span>
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                            </nav>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = searchForm.querySelector('input[name="search"]');
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });
        });
    </script>
@endsection

@push('styles')
    <style>
        .app {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        }
    </style>
@endpush
