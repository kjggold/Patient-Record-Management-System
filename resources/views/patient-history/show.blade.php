@extends('layouts.app')

@section('title', $patient->full_name . ' - Patient Details')

@section('content')
    @php
        $historyData = $allHistory ?? collect();
    @endphp

    <div class="app flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        <div class="flex-1 p-6 bg-gray-50 ml-60">
            {{-- Header --}}
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('patient-history.index') }}" class="mr-4 text-sky-600 hover:text-sky-800 transition">
                        <!-- Back Icon -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">{{ $patient->full_name }}'s History</h1>
                        <p class="text-sm text-gray-600 mt-1">Patient ID: {{ $patient->id }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 bg-sky-100 text-sky-700 text-xs font-medium rounded-full">
                        Total Visits: {{ $totalVisits ?? 0 }}
                    </span>
                    <a href="{{ route('patient-history.download-report', $patient) }}"
                        class="px-4 py-2 border border-green-600 text-green-600 text-sm font-medium rounded-lg hover:bg-green-50 transition flex items-center shadow-sm">
                        <!-- Download Icon -->
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Download Report
                    </a>
                </div>
            </div>

            {{-- Patient Details Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 max-w-6xl mx-auto">
                {{-- Personal Info --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Personal Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Full Name</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->full_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Date of Birth</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'Not specified' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Age</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->age ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Gender</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $patient->sex_gender ? ucfirst($patient->sex_gender) : 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Phone Number</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->phone_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->email ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Address</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Registration Date</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $patient->registration_date ? \Carbon\Carbon::parse($patient->registration_date)->format('F j, Y') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Medical Info --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Medical Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Blood Type</p>
                            <p class="text-sm font-medium text-gray-800">{{ $patient->blood_type ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Alcohol Consumption</p>
                            <p class="text-sm font-medium text-gray-800">
                                {{ $patient->alcohol_consumption ? ucfirst($patient->alcohol_consumption) : 'None' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Assigned Doctor</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if ($patient->doctor) Dr.
                                    {{ $patient->doctor->full_name }} @if ($patient->doctor->speciality)
                                        <span class="text-gray-500">({{ $patient->doctor->speciality }})</span>
                                    @endif
                                @else
                                    Not Assigned @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Last Visit</p>
                            <p class="text-sm font-medium text-gray-800">
                                @if ($historyData->isNotEmpty())
                                    {{ \Carbon\Carbon::parse($historyData->first()['date'])->format('M d, Y') }}
                                @else
                                    No visits yet
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Medical History (Conditions + Allergies) --}}
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                        <h2 class="text-lg font-semibold text-gray-800">Medical History</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Medical Conditions</h3>
                            @if ($patient->known_medical_conditions && trim($patient->known_medical_conditions) !== '')
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $patient->known_medical_conditions) as $condition)
                                        @if (trim($condition))
                                            <span
                                                class="px-3 py-1 bg-red-100 text-red-700 text-xs font-medium rounded-full">{{ trim($condition) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Not provided</p>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Allergies</h3>
                            @if ($patient->allergies && trim($patient->allergies) !== '')
                                <div class="flex flex-wrap gap-2">
                                    @foreach (explode(',', $patient->allergies) as $allergy)
                                        @if (trim($allergy))
                                            <span
                                                class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">{{ trim($allergy) }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Not provided</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Appointment History Table --}}
            <div class="mt-6 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Appointment History</h2>
                    <span class="text-sm text-gray-500">{{ $historyData->count() }} records</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Doctor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($historyData as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($record['date'])->format('M j, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record['doctor_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $record['service_name'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded-full {{ $record['status'] === 'Discharged' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $record['status'] }}
                                        </span>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No appointment
                                        history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endsection

    @push('styles')
        <style>
            .app {
                background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            }
        </style>
    @endpush
