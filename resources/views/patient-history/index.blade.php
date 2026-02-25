@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    @php
        use App\Models\Patient;
        use App\Models\Appointment;
        use Illuminate\Support\Facades\DB;

        // Calculate statistics based on selected date
        if ($dateType == 'all') {
            $totalPatients = Patient::count();
        } else {
            // Count distinct patients who have appointments on the selected date
            $totalPatients = Appointment::where(function ($query) use ($dateType, $selectedDate) {
                if ($dateType == 'today') {
                    $query->whereDate('appointment_date', today());
                } elseif ($dateType == 'yesterday') {
                    $query->whereDate('appointment_date', today()->subDay());
                } elseif ($dateType == 'week') {
                    $query->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType == 'month') {
                    $query->whereMonth('appointment_date', now()->month);
                } elseif ($dateType == 'custom') {
                    $query->whereDate('appointment_date', $selectedDate);
                }
            })
                ->distinct('patient_id')
                ->count('patient_id');
        }

        // Date display text
        $dateDisplay = '';
        if ($dateType == 'today') {
            $dateDisplay = 'Today (' . today()->format('M j, Y') . ')';
        } elseif ($dateType == 'yesterday') {
            $dateDisplay = 'Yesterday (' . today()->subDay()->format('M j, Y') . ')';
        } elseif ($dateType == 'week') {
            $dateDisplay =
                'This Week (' .
                now()->startOfWeek()->format('M j') .
                ' - ' .
                now()->endOfWeek()->format('M j, Y') .
                ')';
        } elseif ($dateType == 'month') {
            $dateDisplay = 'This Month (' . now()->format('F Y') . ')';
        } elseif ($dateType == 'custom') {
            $dateDisplay = \Carbon\Carbon::parse($selectedDate)->format('F j, Y');
        } else {
            $dateDisplay = 'All Time';
        }
    @endphp

    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 p-6 bg-gray-50 ml-60">
            {{-- Header with Search on left --}}
            <div class="mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-semibold text-slate-700 mb-4">Patient Lists</h1>
                    </div>

                    {{-- Three Buttons Group - Now on the right side --}}
                    <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                        {{-- Total Patients Card --}}
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

                                {{-- Date display --}}
                                <div class="mt-1 pt-2 border-t border-sky-200/50">
                                    <p class="text-xs text-sky-600 truncate">
                                        {{ $dateDisplay }}
                                    </p>
                                </div>

                                {{-- Clear Filter Button inside the Total Patients card --}}
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

                        {{-- All Time Dropdown and Calendar - Stacked vertically --}}
                        <div class="flex flex-col gap-1.5">
                            {{-- All Time Button with Dropdown --}}
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

                                {{-- Dropdown Menu --}}
                                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 mt-1 w-full bg-white rounded-lg shadow-lg border border-gray-200 py-1">
                                    <form method="GET" action="{{ route('patient-history.index') }}" class="space-y-1">
                                        <input type="hidden" name="search" value="{{ request('search') }}">

                                        {{-- Today --}}
                                        <button type="submit" name="date_type" value="today"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between {{ $dateType == 'today' ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                            <span>Today</span>
                                            <span class="text-xs text-gray-500">{{ today()->format('M j') }}</span>
                                        </button>

                                        {{-- Yesterday --}}
                                        <button type="submit" name="date_type" value="yesterday"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between {{ $dateType == 'yesterday' ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                            <span>Yesterday</span>
                                            <span
                                                class="text-xs text-gray-500">{{ today()->subDay()->format('M j') }}</span>
                                        </button>

                                        {{-- Last Week --}}
                                        <button type="submit" name="date_type" value="week"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between {{ $dateType == 'week' ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                            <span>This Week</span>
                                            <span
                                                class="text-xs text-gray-500">{{ now()->startOfWeek()->format('M j') }}-{{ now()->endOfWeek()->format('j') }}</span>
                                        </button>

                                        {{-- Last Month --}}
                                        <button type="submit" name="date_type" value="month"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 flex items-center justify-between {{ $dateType == 'month' ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                            <span>This Month</span>
                                            <span class="text-xs text-gray-500">{{ now()->format('M Y') }}</span>
                                        </button>

                                        {{-- All Time --}}
                                        <button type="submit" name="date_type" value="all"
                                            class="w-full text-left px-3 py-2 text-sm hover:bg-gray-100 border-t border-gray-100 {{ $dateType == 'all' ? 'text-sky-600 bg-sky-50' : 'text-gray-700' }}">
                                            All Time
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Calendar Date Filter --}}
                            <form method="GET" action="{{ route('patient-history.index') }}" class="w-40">
                                <div class="relative">
                                    <input type="date" name="selected_date"
                                        value="{{ request('selected_date', date('Y-m-d')) }}"
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

            {{-- Search and Add Section - Aligned to the right with live search --}}
            <div class="mb-5">
                <div class="flex justify-start">
                    <div class="flex items-center gap-3">
                        {{-- Live Search Input (No form submission) --}}
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" id="liveSearchInput" placeholder="ID or Name..."
                                    class="border border-sky-300 rounded-lg px-3 py-2 w-52 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent pr-7 text-sm"
                                    value="{{ request('search') ?? '' }}" autocomplete="off" autofocus>

                                {{-- Clear search button (appears when search has value) --}}
                                <button type="button" id="clearSearchBtn" onclick="clearLiveSearch()"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 {{ !request('search') ? 'hidden' : '' }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Hidden inputs to preserve filter state --}}
                            <input type="hidden" id="dateTypeInput" value="{{ request('date_type', 'all') }}">
                            <input type="hidden" id="selectedDateInput" value="{{ request('selected_date') }}">

                            {{-- Loading indicator --}}
                            <div id="searchLoading" class="hidden">
                                <svg class="animate-spin h-4 w-4 text-sky-500" xmlns="http://www.w3.org/2000/svg"
                                    fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>

                            <button type="button" onclick="openPatientModal()"
                                class="px-4 py-2 bg-sky-600 text-white rounded-lg hover:bg-sky-700 transition shadow-sm text-sm font-medium whitespace-nowrap">
                                + Add Patient
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Search Results Summary - This will be updated via AJAX --}}
            <div id="searchResultsSummary" class="mb-4">
                @if (request('search'))
                    <div class="bg-sky-50 border border-sky-200 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center text-sm text-sky-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Search results for "<strong>{{ request('search') }}</strong>"</span>
                            <span class="ml-2 px-2 py-0.5 bg-sky-200 text-sky-800 rounded-full text-xs">
                                <span id="patientCount">{{ $patients->total() }}</span>
                                {{ Str::plural('patient', $patients->total()) }} found
                            </span>
                        </div>
                        <a href="{{ route('patient-history.index', array_merge(request()->except(['search', 'page']))) }}"
                            class="text-sm text-sky-600 hover:text-sky-800 hover:underline flex items-center">
                            Clear search
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Patient Table Container --}}
            <div id="patientTableContainer">
                @include('patient-history.patient-table', [
                    'patients' => $patients,
                    'dateType' => $dateType,
                    'selectedDate' => $selectedDate,
                ])
            </div>
        </div>
    </div>

    {{-- Include Patient Modal --}}
    @include('add-modals.patient-modal')

    @push('styles')
        <style>
            .animate-spin {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('liveSearchInput');
                const searchLoading = document.getElementById('searchLoading');
                const clearSearchBtn = document.getElementById('clearSearchBtn');
                const searchResultsSummary = document.getElementById('searchResultsSummary');
                const patientTableContainer = document.getElementById('patientTableContainer');
                const dateTypeInput = document.getElementById('dateTypeInput');
                const selectedDateInput = document.getElementById('selectedDateInput');

                let searchTimeout;
                let currentSearchTerm = searchInput.value;
                let abortController = null;

                // Focus the search input on page load
                searchInput.focus();

                // Set cursor at the end of the text
                const len = searchInput.value.length;
                searchInput.setSelectionRange(len, len);

                // Live search as you type
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value;

                    // Show/hide clear button
                    if (searchTerm.length > 0) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }

                    // Clear any existing timeout
                    clearTimeout(searchTimeout);

                    // Cancel any pending request
                    if (abortController) {
                        abortController.abort();
                    }

                    // Show loading indicator
                    searchLoading.classList.remove('hidden');

                    // Set a new timeout to search after user stops typing
                    searchTimeout = setTimeout(function() {
                        performLiveSearch(searchTerm);
                    }, 300); // 300ms delay
                });

                // Function to perform live search
                function performLiveSearch(searchTerm) {
                    // Create new abort controller
                    abortController = new AbortController();

                    // Get current filter values
                    const dateType = dateTypeInput.value;
                    const selectedDate = selectedDateInput.value;

                    // Build URL with parameters
                    const url = new URL('{{ route('patient-history.index') }}');
                    url.searchParams.append('search', searchTerm);
                    url.searchParams.append('date_type', dateType);
                    url.searchParams.append('selected_date', selectedDate);
                    url.searchParams.append('ajax', 'true');

                    // Perform AJAX request
                    fetch(url, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            },
                            signal: abortController.signal
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Update the patient table container with the new HTML
                            patientTableContainer.innerHTML = data.html;

                            // Update search results summary
                            updateSearchSummary(searchTerm, data.count);

                            // Update browser URL without reloading
                            updateBrowserUrl(searchTerm);

                            // Hide loading indicator
                            searchLoading.classList.add('hidden');

                            // Keep focus on search input
                            searchInput.focus();
                        })
                        .catch(error => {
                            if (error.name !== 'AbortError') {
                                console.error('Search error:', error);
                                searchLoading.classList.add('hidden');
                            }
                        });
                }

                // Function to update search summary
                function updateSearchSummary(searchTerm, count) {
                    let summaryHtml = '';

                    if (searchTerm && searchTerm.length > 0) {
                        const dateType = dateTypeInput.value;
                        const selectedDate = selectedDateInput.value;
                        const baseUrl = '{{ route('patient-history.index') }}';
                        const clearUrl = `${baseUrl}?date_type=${dateType}&selected_date=${selectedDate}`;

                        summaryHtml = `
                    <div class="bg-sky-50 border border-sky-200 rounded-lg p-3 flex items-center justify-between">
                        <div class="flex items-center text-sm text-sky-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Search results for "<strong>${searchTerm}</strong>"</span>
                            <span class="ml-2 px-2 py-0.5 bg-sky-200 text-sky-800 rounded-full text-xs">
                                <span id="patientCount">${count}</span> ${count === 1 ? 'patient' : 'patients'} found
                            </span>
                        </div>
                        <a href="${clearUrl}"
                           class="text-sm text-sky-600 hover:text-sky-800 hover:underline flex items-center">
                            Clear search
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    </div>
                `;
                    } else {
                        summaryHtml = '';
                    }

                    searchResultsSummary.innerHTML = summaryHtml;
                }

                // Function to update browser URL without reload
                function updateBrowserUrl(searchTerm) {
                    const url = new URL(window.location);
                    if (searchTerm && searchTerm.length > 0) {
                        url.searchParams.set('search', searchTerm);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.history.pushState({}, '', url);
                }

                // Clear search function
                window.clearLiveSearch = function() {
                    searchInput.value = '';
                    searchInput.focus();
                    clearSearchBtn.classList.add('hidden');
                    performLiveSearch('');
                };

                // Handle back/forward buttons
                window.addEventListener('popstate', function() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const searchTerm = urlParams.get('search') || '';

                    searchInput.value = searchTerm;
                    if (searchTerm) {
                        clearSearchBtn.classList.remove('hidden');
                    } else {
                        clearSearchBtn.classList.add('hidden');
                    }

                    performLiveSearch(searchTerm);
                });

                // Confirm delete function
                window.confirmDelete = function(patientId) {
                    if (confirm('Are you sure you want to delete this patient? This action cannot be undone.')) {
                        document.getElementById('delete-form-' + patientId).submit();
                    }
                };

                // Open patient modal function
                window.openPatientModal = function() {
                    const modal = document.getElementById('patientModal');
                    if (modal) {
                        modal.classList.remove('hidden');
                        document.body.classList.add('overflow-hidden');
                    } else {
                        // Try to find modal with common IDs
                        const possibleModals = ['addModal', 'patientModal', 'addPatientModal'];
                        for (const modalId of possibleModals) {
                            const foundModal = document.getElementById(modalId);
                            if (foundModal) {
                                foundModal.classList.remove('hidden');
                                document.body.classList.add('overflow-hidden');
                                break;
                            }
                        }
                    }
                };

                // Close modal function
                window.closePatientModal = function() {
                    const modal = document.getElementById('patientModal');
                    if (modal) {
                        modal.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                };

                // Close modal when clicking outside
                document.addEventListener('click', function(e) {
                    const modal = document.getElementById('patientModal');
                    if (modal && e.target === modal) {
                        closePatientModal();
                    }
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        closePatientModal();
                    }
                });
            });
        </script>
    @endpush
@endsection
