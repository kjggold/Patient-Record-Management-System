@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    @php
        use App\Models\Patient;
        use App\Models\Appointment;

        // Calculate statistics based on selected date
        $totalPatients = Patient::when($dateType != 'all', function($query) use ($dateType) {
            if ($dateType == 'today') {
                $query->whereDate('created_at', today());
            } elseif ($dateType == 'yesterday') {
                $query->whereDate('created_at', today()->subDay());
            } elseif ($dateType == 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateType == 'month') {
                $query->whereMonth('created_at', now()->month);
            }
        })->count();
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
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
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="p-3 bg-blue-100 rounded-lg mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Patients</p>
                                <p class="text-2xl font-bold text-gray-800">{{ $totalPatients }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    @if($dateType == 'today')
                                        Today
                                    @elseif($dateType == 'yesterday')
                                        Yesterday
                                    @elseif($dateType == 'week')
                                        This Week
                                    @elseif($dateType == 'month')
                                        This Month
                                    @elseif($dateType == 'custom')
                                        {{ \Carbon\Carbon::parse(request('selected_date'))->format('F j, Y') }}
                                    @else
                                        All Time
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Clear Filter Button --}}
                        @if(request()->anyFilled(['search', 'selected_date']) || $dateType != 'all')
                            <a href="{{ route('patient-history.index') }}"
                               class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filter
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Patient Cards Grid - 3 per row with equal height --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                @forelse($patients as $patient)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200 flex flex-col h-full">
                    <div class="p-5 flex-1">
                        {{-- Patient Header --}}
                        <div class="mb-4">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1 min-w-0">
                                    <h2 class="text-lg font-semibold text-gray-800 truncate">{{ $patient->full_name }}</h2>
                                    @if($patient->doctor)
                                    <p class="text-sm text-blue-600 mt-1 truncate">
                                        Dr. {{ $patient->doctor->full_name }}
                                    </p>
                                    @endif
                                </div>
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full flex-shrink-0 ml-2">
                                    ID: {{ $patient->id }}
                                </span>
                            </div>

                            <div class="space-y-2 text-sm text-gray-600">
                                @if($patient->phone_number)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="truncate">{{ $patient->phone_number }}</span>
                                </div>
                                @endif
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span>Last visit: {{ $patient->last_visit ?? 'No visits yet' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Patient Information --}}
                        <div class="space-y-3 mb-4">
                            @if($patient->address)
                            <div class="flex items-start">
                                <svg class="w-4 h-4 text-gray-400 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 flex-1">{{ Str::limit($patient->address, 40) }}</span>
                            </div>
                            @endif

                            <div class="flex items-center justify-between">
                                @if($patient->blood_type)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">{{ $patient->blood_type }}</span>
                                </div>
                                @endif

                                @if($patient->age || $patient->sex_gender)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-sm text-gray-600">
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
                        </div>

                        {{-- Appointments Count --}}
                        <div class="mt-auto">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-700">Total Visits</span>
                                <span class="text-lg font-bold text-gray-800">{{ $patient->total_visits ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Actions -- At the bottom of each card, same line --}}
                    <div class="px-5 pb-5 border-t border-gray-100 pt-4">
                        <div class="flex space-x-2">
                            <a href="{{ route('patient-history.show', $patient) }}"
                                class="flex-1 px-3 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                View Details
                            </a>
                            @if(method_exists($patient, 'download-report'))
                            <a href="{{ route('patient-history.download-report', $patient) }}"
                                class="px-3 py-2 border border-green-600 text-green-600 text-sm font-medium rounded-lg hover:bg-green-50 transition flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Report
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No patients found</h3>
                    <p class="text-gray-500 mb-4">Try adjusting your search or filter criteria</p>
                    <a href="{{ route('patient-history.index') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Clear Filters
                    </a>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($patients->hasPages())
            <div class="mt-6">
                {{ $patients->withQueryString()->links() }}
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