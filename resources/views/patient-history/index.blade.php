@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    @php
        use App\Models\Patient;
        use App\Models\Appointment;

        // Calculate statistics based on selected date - Show patients with appointments on that date
        if ($dateType == 'all') {
            $totalPatients = Patient::count();
        } else {
            $totalPatients = Patient::whereHas('appointments', function($query) use ($dateType, $selectedDate) {
                if ($dateType == 'today') {
                    $query->whereDate('appointment_date', today());
                } elseif ($dateType == 'yesterday') {
                    $query->whereDate('appointment_date', today()->subDay());
                } elseif ($dateType == 'week') {
                    $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType == 'month') {
                    $query->whereMonth('appointment_date', now()->month);
                } elseif ($dateType == 'custom' && $selectedDate) {
                    $query->whereDate('appointment_date', $selectedDate);
                }
            })->count();
        }
    @endphp

    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 p-6 bg-gray-50">
            {{-- Header with Search --}}
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Patient History</h1>
                        <p class="text-gray-600">Manage patient records, appointments, and medical history</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        {{-- Search Form --}}
                        <form method="GET" action="{{ route('patient-history.index') }}" class="flex-1 md:flex-none">
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Search by name, phone, ID..."
                                       class="w-full md:w-64 px-4 py-2 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 1114 0 7 7 0 01-14 0z"></path>
                                </svg>
                                <input type="hidden" name="date_type" value="{{ request('date_type', 'all') }}">
                                <input type="hidden" name="selected_date" value="{{ request('selected_date') }}">
                            </div>
                        </form>

                        {{-- Calendar Date Filter --}}
                        <form method="GET" action="{{ route('patient-history.index') }}" class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="date" name="selected_date" value="{{ request('selected_date', date('Y-m-d')) }}"
                                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       onchange="this.form.submit()">
                                <input type="hidden" name="search" value="{{ request('search') }}">
                                <input type="hidden" name="date_type" value="custom">
                            </div>
                            <button type="submit" name="date_type" value="all"
                                    class="px-4 py-2 {{ $dateType == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition">
                                All Time
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Statistics Card --}}
            <div class="mb-6">
                <div class="bg-gradient-to-r from-blue-300 to-blue-300 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl mr-4">
                                <svg class="w-8 h-8 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-black-100">Total Patients</p>
                                <p class="text-3xl font-bold text-black">{{ $totalPatients }}</p>
                                <p class="text-sm text-black-100 mt-2">
                                    @if($dateType == 'today')
                                        📅 Today's Appointments
                                    @elseif($dateType == 'yesterday')
                                        📅 Yesterday's Appointments
                                    @elseif($dateType == 'week')
                                        📅 This Week's Appointments
                                    @elseif($dateType == 'month')
                                        📅 This Month's Appointments
                                    @elseif($dateType == 'custom')
                                        📅 {{ \Carbon\Carbon::parse(request('selected_date'))->format('F j, Y') }} Appointments
                                    @else
                                        📊 All Patients
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Clear Filter Button --}}
                        @if(request()->anyFilled(['search', 'selected_date']) || $dateType != 'all')
                            <a href="{{ route('patient-history.index') }}"
                               class="px-4 py-2 text-sm bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition flex items-center shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Patient Cards Grid - 4 per row with comfortable background --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
                @forelse($patients as $patient)
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                    <div class="p-4 flex-1">
                        {{-- Patient Header --}}
                        <div class="mb-3">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-base font-semibold text-gray-800 truncate">{{ $patient->full_name }}</h2>
                                    @if($patient->doctor)
                                    <p class="text-xs text-blue-600 mt-1 truncate">
                                        Dr. {{ $patient->doctor->full_name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full flex-shrink-0 ml-2">
                                    ID: {{ $patient->id }}
                                </span>
                            </div>

                            <div class="space-y-1 text-xs text-gray-600">
                                @if($patient->phone_number)
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="truncate">{{ $patient->phone_number }}</span>
                                </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">Last visit: {{ $patient->last_visit ?? 'No visits yet' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Patient Information --}}
                        <div class="space-y-2 mb-3">
                            @if($patient->address)
                            <div class="flex items-start">
                                <svg class="w-3 h-3 text-gray-400 mt-0.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-xs text-gray-600 flex-1">{{ Str::limit($patient->address, 35) }}</span>
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-2">
                                @if($patient->blood_type)
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    <span class="text-xs text-gray-600 truncate">{{ $patient->blood_type }}</span>
                                </div>
                                @endif

                                @if($patient->age || $patient->sex_gender)
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-gray-400 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-xs text-gray-600 truncate">
                                        @if($patient->age && $patient->sex_gender)
                                            {{ $patient->age }} | {{ $patient->sex_gender }}
                                        @elseif($patient->age)
                                            {{ $patient->age }} years
                                        @elseif($patient->sex_gender)
                                            {{ $patient->sex_gender }}
                                        @endif
                                    </span>
                                </div>
                                @endif
                            </div>

                            {{-- Total Visits in the same line --}}
                            <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-medium text-gray-700">Total Visits</span>
                                </div>
                                <span class="text-sm font-bold text-blue-600">{{ $patient->total_visits ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions -- At the bottom of each card, same line --}}
                    <div class="px-4 pb-4 border-t border-gray-100 pt-3">
                        <div class="flex space-x-2">
                            <a href="{{ route('patient-history.show', $patient) }}"
                                class="flex-1 px-2 py-1.5 bg-blue-400 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Details
                            </a>
                            @if(method_exists($patient, 'download-report'))
                            <a href="{{ route('patient-history.download-report', $patient) }}"
                                class="px-2 py-1.5 border border-green-600 text-green-600 text-xs font-medium rounded-lg hover:bg-green-50 transition flex items-center justify-center shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Report
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 sm:col-span-2 lg:col-span-3 xl:col-span-4 bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">
                        @if($dateType == 'all')
                            No patients found
                        @else
                            No patients found for
                            @if($dateType == 'today')
                                today
                            @elseif($dateType == 'yesterday')
                                yesterday
                            @elseif($dateType == 'week')
                                this week
                            @elseif($dateType == 'month')
                                this month
                            @elseif($dateType == 'custom')
                                {{ \Carbon\Carbon::parse($selectedDate)->format('F j, Y') }}
                            @endif
                        @endif
                    </h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('patient-history.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm">
                        Clear Filters
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($patients->hasPages() || $patients->total() > 0)
            <div class="mt-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        {{-- Showing entries info --}}
                        <div class="mb-4 md:mb-0">
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ ($patients->currentPage() - 1) * 12 + 1 }}</span>
                                to
                                <span class="font-medium">{{ min($patients->currentPage() * 12, $patients->total()) }}</span>
                                of
                                <span class="font-medium">{{ $patients->total() }}</span>
                                patients
                                <span class="text-gray-500">(12 per page)</span>
                            </p>
                        </div>

                        {{-- Pagination buttons --}}
                        @if($patients->hasPages())
                        <nav class="flex items-center space-x-1">
                            {{-- Previous button --}}
                            <a href="{{ $patients->previousPageUrl() }}"
                               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 {{ $patients->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Previous</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>

                            {{-- Page numbers --}}
                            @php
                                $current = $patients->currentPage();
                                $last = $patients->lastPage();
                                $range = 2;
                            @endphp

                            @for ($page = 1; $page <= $last; $page++)
                                @if ($page == 1 || $page == $last || ($page >= $current - $range && $page <= $current + $range))
                                    <a href="{{ $patients->url($page) }}"
                                       class="px-3 py-2 text-sm font-medium border border-gray-300 {{ $page == $current ? 'bg-blue-50 text-blue-600 border-blue-300' : 'bg-white text-gray-500 hover:bg-gray-100' }}">
                                        {{ $page }}
                                    </a>
                                @elseif (($page == $current - ($range + 1)) || ($page == $current + ($range + 1)))
                                    <span class="px-3 py-2 text-sm font-medium text-gray-500">...</span>
                                @endif
                            @endfor

                            {{-- Next button --}}
                            <a href="{{ $patients->nextPageUrl() }}"
                               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 {{ !$patients->hasMorePages() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                <span class="sr-only">Next</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        </nav>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    .app {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
    }
    /* Ensure all cards have same height */
    .grid > div {
        display: flex;
        flex-direction: column;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-submit date filter form when date changes
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="selected_date"]');
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                // Find the closest form and submit it
                const form = this.closest('form');
                if (form) {
                    // Update the date_type to custom when date is selected
                    const dateTypeInput = form.querySelector('input[name="date_type"]');
                    if (dateTypeInput) {
                        dateTypeInput.value = 'custom';
                    }
                    form.submit();
                }
            });
        }

        // Auto-submit search on enter
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.closest('form').submit();
                }
            });
        }
    });
</script>
@endpush