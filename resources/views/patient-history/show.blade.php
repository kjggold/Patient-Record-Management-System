@extends('layouts.app')

@section('title', $patient->name . ' - Patient History')

@section('content')
<div class="app flex min-h-screen">
    @include('layouts.sidebar')

    <div class="flex-1 p-6 bg-gray-50">
        <div class="mb-6">
            <a href="{{ route('patient-history.index') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-block">
                ← Back to Patient History
            </a>
            <h1 class="text-2xl font-bold text-gray-800">{{ $patient->name }}</h1>
            <p class="text-gray-600">Complete patient history and details</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Patient Info Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Patient Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium">{{ $patient->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone</p>
                        <p class="font-medium">{{ $patient->phone ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Date of Birth</p>
                        <p class="font-medium">{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Gender</p>
                        <p class="font-medium">{{ $patient->gender ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="font-medium">{{ $patient->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            {{-- Statistics Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Patient Statistics</h2>
                <div class="space-y-4">
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-sm text-blue-700">Total Visits</p>
                        <p class="text-2xl font-bold text-blue-800">{{ $visitCount }}</p>
                    </div>
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-700">Total Amount</p>
                        <p class="text-2xl font-bold text-green-800">${{ number_format($totalAmount) }}</p>
                    </div>
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <p class="text-sm text-purple-700">Last Visit</p>
                        <p class="text-xl font-bold text-purple-800">{{ $lastVisit }}</p>
                    </div>
                </div>
            </div>

            {{-- Medical History Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Medical History</h2>
                <div class="prose max-w-none">
                    @if($patient->medical_history)
                        {!! nl2br(e($patient->medical_history)) !!}
                    @else
                        <p class="text-gray-500 italic">No medical history recorded.</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- All Appointments --}}
        <div class="mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">All Appointments</h2>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($patient->appointments as $appointment)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->appointment_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $appointment->type == 'EMERGENCY' ? 'bg-red-100 text-red-800' :
                                           ($appointment->type == 'FOLLOW UP' ? 'bg-yellow-100 text-yellow-800' :
                                           'bg-blue-100 text-blue-800') }}">
                                        {{ $appointment->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->service->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->doctor->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">${{ number_format($appointment->amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $appointment->status == 'completed' ? 'bg-green-100 text-green-800' :
                                           ($appointment->status == 'cancelled' ? 'bg-red-100 text-red-800' :
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection