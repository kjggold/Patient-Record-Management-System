@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    <div class="app flex min-h-screen">
        {{-- Side bar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 p-6 bg-gray-50">
            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Patient History</h1>
                <p class="text-gray-600">Manage and view patient records, appointments, and reports</p>
            </div>

            {{-- Filter Section --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    {{-- Date Filter Buttons --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('patient-history.index', ['date_type' => 'today']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'today' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Today
                        </a>
                        <a href="{{ route('patient-history.index', ['date_type' => 'yesterday']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'yesterday' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Yesterday
                        </a>
                        <a href="{{ route('patient-history.index', ['date_type' => 'tomorrow']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'tomorrow' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Tomorrow
                        </a>
                        <a href="{{ route('patient-history.index', ['date_type' => 'week']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'week' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            This Week
                        </a>
                        <a href="{{ route('patient-history.index', ['date_type' => 'month']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'month' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            This Month
                        </a>
                        <a href="{{ route('patient-history.index', ['date_type' => 'all']) }}"
                           class="px-4 py-2 rounded-lg {{ $dateType == 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All Time
                        </a>
                    </div>

                    {{-- Calendar Picker for Specific Date --}}
                    <form action="{{ route('patient-history.filter-by-date') }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="date" name="date" value="{{ $selectedDate }}"
                               class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <input type="hidden" name="date_type" value="specific">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Go
                        </button>
                    </form>
                </div>
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('patient-history.index') }}" class="mb-8">
                <div class="flex items-center bg-white rounded-lg shadow-sm border border-gray-300 px-4 py-3">
                    <svg class="w-5 h-5 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by name, problem, time, doctor, service..."
                           class="w-full focus:outline-none text-gray-800 placeholder-gray-500">
                    <input type="hidden" name="date_type" value="{{ $dateType }}">
                    <input type="hidden" name="date" value="{{ $selectedDate }}">
                    <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Search
                    </button>
                </div>
            </form>

            {{-- Patient List --}}
            <div class="space-y-6">
                @forelse($patients as $patient)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        {{-- Patient Header --}}
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-4">
                            <div class="mb-4 md:mb-0">
                                <h2 class="text-xl font-semibold text-gray-800">{{ $patient->name }}</h2>
                                <p class="text-gray-500 text-sm">Last visit: {{ $patient->last_visit }}</p>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Email:</span> {{ $patient->email ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Phone:</span> {{ $patient->phone ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-gray-800">{{ $patient->total_visits }} Times</p>
                                <p class="text-lg font-bold text-blue-600">${{ number_format($patient->total_amount) }}</p>
                            </div>
                        </div>

                        {{-- Appointments --}}
                        @if($patient->appointments->count() > 0)
                        <div class="space-y-4 mb-6">
                            <h3 class="font-medium text-gray-700 mb-2">Appointments:</h3>
                            @foreach($patient->appointments as $appointment)
                            <div class="flex flex-col md:flex-row md:items-center p-4 bg-gray-50 rounded-lg">
                                <div class="w-full md:w-1/4 mb-2 md:mb-0">
                                    <p class="font-bold text-gray-800">{{ $appointment->appointment_date->format('j M Y') }}</p>
                                    <p class="text-sm text-gray-500">{{ $appointment->appointment_date->format('l') }}</p>
                                </div>
                                <div class="md:ml-4 flex-1">
                                    <div class="flex flex-col md:flex-row md:justify-between md:items-center mb-2">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full mb-2 md:mb-0">
                                            {{ $appointment->type }}
                                        </span>
                                        <span class="text-gray-500 text-sm">{{ $appointment->appointment_time }}</span>
                                    </div>
                                    <h3 class="font-medium text-gray-800 mb-1">{{ $appointment->title }}</h3>
                                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                        <span>
                                            <span class="font-medium">Doctor:</span>
                                            {{ $appointment->doctor->name ?? 'Not Assigned' }}
                                        </span>
                                        <span>
                                            <span class="font-medium">Service:</span>
                                            {{ $appointment->service->name ?? 'General Checkup' }}
                                        </span>
                                        <span>
                                            <span class="font-medium">Amount:</span>
                                            ${{ number_format($appointment->amount, 2) }}
                                        </span>
                                    </div>
                                    @if($appointment->notes)
                                    <p class="mt-2 text-sm text-gray-600">
                                        <span class="font-medium">Notes:</span> {{ $appointment->notes }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <p class="text-yellow-700">No appointments found for the selected date range.</p>
                        </div>
                        @endif

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 pt-6 border-t border-gray-100">
                            {{-- Schedule Appointment Modal Trigger --}}
                            <button onclick="openScheduleModal('{{ $patient->id }}', '{{ $patient->name }}')"
                                class="px-5 py-2 border border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 transition">
                                SCHEDULE
                            </button>
                            <a href="{{ route('patient-history.download-report', $patient) }}"
                                class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition text-center">
                                DOWNLOAD REPORT
                            </a>
                            <a href="{{ route('patient-history.show', $patient) }}"
                                class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition text-center">
                                VIEW DETAILS
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No patients found</h3>
                    <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                </div>
                @endforelse

                {{-- Pagination --}}
                @if($patients->hasPages())
                <div class="mt-6">
                    {{ $patients->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Schedule Appointment Modal --}}
    <div id="scheduleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-lg bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Schedule Appointment</h3>
                <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="scheduleForm" method="POST">
                @csrf
                <input type="hidden" id="patientId" name="patient_id">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor</label>
                        <select name="doctor_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Service</label>
                        <select name="service_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Service</option>
                            @foreach($services as $service)
                            <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                {{ $service->name }} (${{ number_format($service->price, 2) }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                            <input type="date" name="appointment_date" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   min="{{ now()->format('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Time</label>
                            <input type="time" name="appointment_time" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="NORMAL CHECKUP">Normal Checkup</option>
                            <option value="EMERGENCY">Emergency</option>
                            <option value="FOLLOW UP">Follow Up</option>
                            <option value="SPECIALIST">Specialist</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount ($)</label>
                        <input type="number" name="amount" step="0.01" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               id="amountInput">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeScheduleModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openScheduleModal(patientId, patientName) {
        document.getElementById('patientId').value = patientId;
        document.getElementById('scheduleModal').classList.remove('hidden');
        document.getElementById('scheduleForm').action = `/patient-history/${patientId}/schedule`;
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
        document.getElementById('scheduleForm').reset();
    }

    // Auto-fill amount based on selected service
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.querySelector('select[name="service_id"]');
        const amountInput = document.getElementById('amountInput');

        if (serviceSelect && amountInput) {
            serviceSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                if (price) {
                    amountInput.value = price;
                }
            });
        }
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('scheduleModal');
        if (event.target == modal) {
            closeScheduleModal();
        }
    }
</script>
@endpush