@extends('layouts.app')

@section('title', 'Appointments | MediCore')

@section('content')
    <style>

        /* +Add Appointment Modal */
        #addModal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            background: rgba(0, 0, 0, .5);
            justify-content: center;
            align-items: center;
            overflow: auto;
            padding: 1rem;
        }

        .modal-open {
            display: flex !important;
        }

        .add-form-card {
            max-width: 900px;
            /* increased width for bigger form */
            width: 90%;
            background: var(--bg);
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .add-form-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 25px;
            color: rgb(98, 191, 249);
        }

        .add-form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 22px;
        }

        .add-form-group {
            display: flex;
            flex-direction: column;
        }

        .add-form-group label {
            font-size: 16px;
            margin-bottom: 8px;
            color: #334155;
        }

        .add-form-group input,
        .add-form-group select,
        .add-form-group textarea {
            padding: 14px 16px;
            border-radius: 12px;
            border: 1px solid #cfe6ff;
            outline: none;
            font-size: 15px;
            width: 100%;
        }

        textarea {
            resize: none;
        }

        .add-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 14px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 26px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
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

        .btn-reset,
        .btn-cancel {
            background: #e5e7eb;
        }

        .btn-reset:hover,
        .btn-cancel:hover {
            background: #cbd5e1;
        }
    </style>
    <div id="addModal">
        <div class="add-form-card">
            <div class="add-form-title">Appointment Information</div>
            <form onsubmit="saveAppointment(); return false;">
                <div class="add-form-grid">
                    <div class="add-form-group">
                        <label>Patient Name</label>
                        <input type="text" id="add_patient" placeholder="Select patient">
                    </div>
                    <div class="add-form-group">
                        <label>Doctor</label>
                        <select id="add_doctor">
                            <option>Select doctor</option>
                            <option>Dr. Sarah Johnson – General</option>
                            <option>Dr. May Lin – OG</option>
                            <option>Dr. Kyaw Myint – Cardiology</option>
                        </select>
                    </div>
                    <div class="add-form-group">
                        <label>Service</label>
                        <select id="add_service">
                            <option>Select service</option>
                            <option>General Consultation</option>
                            <option>Child Health</option>
                            <option>Diabetes Care</option>
                            <option>Women’s Health</option>
                        </select>
                    </div>
                    <div class="add-form-group">
                        <label>Appointment Date</label>
                        <input type="date" id="add_date">
                    </div>
                    <div class="add-form-group">
                        <label>Phone</label>
                        <input type="tel" id="add_phone" placeholder="09xxxxxxxx">
                    </div>
                </div>
                <div class="add-form-actions">
                    <button type="button" class="btn btn-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn btn-save">Save Appointment</button>
                </div>
            </form>
        </div>
    </div>
@endsection