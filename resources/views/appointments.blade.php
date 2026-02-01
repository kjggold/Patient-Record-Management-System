@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
    <div class="app flex">

        {{-- SIDEBAR --}}
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">

            <!-- HEADER -->
            <div class="flex w-full sm:w-auto gap-2 mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Appointments</h1>
            </div>

            <!-- Top Actions -->
            <div class="flex justify-end items-center mb-6 gap-3">
                <div class="flex gap-2">
                    <input type="text" placeholder="Search by name..." class="border rounded px-3 py-2 w-64">
                </div>

                <!-- + Add Patient Button -->
                <button onclick="openAddModal()" class="bg-sky-600 text-white px-5 py-2 rounded-lg shadow hover:bg-sky-700">
                    + Add Appointment
                </button>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient</th>
                            <th>Doctor</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($appointments as $a)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3">{{ $a->id }}</td>

                                <td class="px-4 py-3">
                                    @if($a->patient)
                                        {{ $a->patient->full_name }}
                                        @else
                                            <span class="text-gray-400">Not Assigned</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">
                                    @if($a->doctor)
                                        {{ $a->doctor->full_name }}
                                        @else
                                            <span class="text-gray-400">Not Assigned</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3">{{ $a->appointment_date }}</td>
                                <td class="px-4 py-3">{{ $a->appointment_time }}</td>
                                <td class="px-4 py-3">{{ $a->status }}</td>
                                <td class="px-4 py-3 text-center space-x-2">
                                    <button class="text-amber-600 hover:underline" onclick="openViewModal()">View</button>
                                    <button class="text-amber-600 hover:underline" onclick="">Edit</button>
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-500">No patients found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    </main>
    </div>

    {{-- ================= MODAL: ADD APPOINTMENT ================= --}}
    <div id="addModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 overflow-auto py-10">
        <div class="form-card max-w-4xl w-full">

            <div class="form-title">Appointment Information</div>

            <form method="POST" action="{{ route('appointments.store') }} " id="appointmentform">
                @csrf
                <div class="form-grid grid-cols-2 gap-6">

                    <div class="form-group">
                        <label>Patient Name</label>
                        <select name="patient_name" id="patientSelect" required>
                            <option value="">Select patient</option>
                            @foreach ($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->full_name }}</option>
                            @endforeach
                        </select>                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="doctor_name" required>
                            <option value="">Select doctor</option>
                            @foreach ($doctors as $d)
                                <option value="{{ $d->id }}">{{ $d->full_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Service</label>
                        <select name="service">
                            <option value="">Select service</option>
                            @foreach ($services as $s)
                                <option value="{{ $s->id }}">{{ $s->service_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="appointment_date" required>
                    </div>

                    <div class="form-group">
                        <label>Appointment Time</label>
                        <input type="time" name="appointment_time" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" id="phoneInput" placeholder="09xxxxxxxx">
                    </div>

                    <div class="form-group col-span-2">
                        <label>Notes (Optional)</label>
                        <textarea name="notes_optional" rows="3" placeholder="Symptoms or remarks..."></textarea>
                    </div>

                </div>

                <div class="form-actions mt-6">
                    <button type="button" onclick="closeAddModal()" class="btn btn-reset">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= SCRIPTS ================= --}}
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const patientSelect = document.getElementById('patientSelect');
            const phoneInput = document.getElementById('phoneInput');
            
            // Set default date to today
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[name="appointment_date"]').value = today;
            
            // Auto-fill phone when patient is selected
            patientSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const phone = selectedOption.getAttribute('data-phone');
                
                if (phone) {
                    phoneInput.value = phone;
                } else {
                    phoneInput.value = '';
                    phoneInput.placeholder = 'Phone number not available';
                }
            });
            
            // Reset button functionality
            document.querySelector('.reset-btn').addEventListener('click', function() {
                if (confirm('Clear form and go back?')) {
                    document.getElementById('appointmentform').reset();
                    
                    // Reset the phone input separately
                    phoneInput.value = '';
                    phoneInput.placeholder = 'Phone will auto-fill when patient is selected';
                    
                    // Reset date to today
                    document.querySelector('input[name="appointment_date"]').value = today;
                    
                    // Redirect to the same page
                    window.location.href = 'appointment.index';
                }
            });
        });
    </script>

    {{-- ================= STYLES ================= --}}
    <style>
        :root {
            --main: #22d3ee;
            --accent: #0d6efd;
            --bg: #f4f9ff;
            --card: #ffffff;
            --text: #0f172a;
        }

        body {
            background: var(--bg);
        }

        .app {
            display: flex;
            min-height: 100vh;
        }

        .form-card {
            max-width: 800px;
            background: var(--card);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }

        .form-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--text);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #334155;
        }

        input,
        select,
        textarea {
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #cfe6ff;
            outline: none;
            font-size: 14px;
        }

        textarea {
            resize: none;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-save {
            background: var(--accent);
            color: white;
        }

        .btn-save:hover {
            background: #084298;
        }

        .btn-reset {
            background: #e5e7eb;
        }

        .btn-reset:hover {
            background: #cbd5e1;
        }

        .table-container {
            background: white;
            border-radius: 14px;
            overflow-x-auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
        }

        thead {
            background: #e0f2fe;
            color: #0f172a;
        }

        tbody tr:hover {
            background: #f1f9ff;
        }

        .actions button {
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: 0.2s;
        }

        .actions button:hover {
            text-decoration: underline;
        }
    </style>
@endsection