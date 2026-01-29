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
                        <tr>
                            <td>A001</td>
                            <td>John Smith</td>
                            <td>Dr. Sarah Johnson</td>
                            <td>2026-01-19</td>
                            <td>10:00 AM</td>
                            <td class="text-center"><span class="status-scheduled">Scheduled</span></td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button class="text-amber-600 hover:underline">Edit</button>
                                <button class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
                        <tr>
                            <td>A002</td>
                            <td>Emma Wilson</td>
                            <td>Dr. Michael Brown</td>
                            <td>2026-01-19</td>
                            <td>11:30 AM</td>
                            <td class="text-center"><span class="status-progress">In Progress</span></td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button class="text-amber-600 hover:underline">Edit</button>
                                <button class="text-red-600 hover:underline">Delete</button>
                            </td>
                        </tr>
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

            <form method="POST" action="{{ route('appointments.store') }}">
                @csrf
                <div class="form-grid grid-cols-2 gap-6">

                    <div class="form-group">
                        <label>Patient Name</label>
                        <input type="text" name="patient_name" placeholder="Select patient" required>
                    </div>

                    <div class="form-group">
                        <label>Doctor</label>
                        <select name="doctor_id" required>
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
                            <option value="General Consultation">General Consultation</option>
                            <option value="Child Health">Child Health</option>
                            <option value="Diabetes Care">Diabetes Care</option>
                            <option value="Women’s Health">Women’s Health</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Appointment Date</label>
                        <input type="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label>Appointment Time</label>
                        <input type="time" name="time" required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" placeholder="09xxxxxxxx">
                    </div>

                    <div class="form-group col-span-2">
                        <label>Notes (Optional)</label>
                        <textarea name="notes" rows="3" placeholder="Symptoms or remarks..."></textarea>
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
