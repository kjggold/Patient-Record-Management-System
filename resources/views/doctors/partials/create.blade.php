@extends('layouts.app')

@section('title', 'Add New Doctor')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <h2 class="logo">MediCore</h2>
        <nav>
            <a href="{{ route('dashboard') }}"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
            <a><i class="fa-solid fa-user"></i> Patients</a>
            <a href="{{ route('doctors.index') }}"><i class="fa-solid fa-user-doctor"></i> Doctors</a>
            <a><i class="fa-solid fa-calendar-check"></i> Appointments</a>
            <a><i class="fa-solid fa-stethoscope"></i> Services</a>
            <a><i class="fa-solid fa-credit-card"></i> Payments</a>
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <a href="{{ route('logout') }}" class="logout"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </form>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">Add New Doctor</h1>
            <div class="flex gap-3">
                <a href="{{ route('doctors.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600">
                    ← Back to Doctors
                </a>
            </div>
        </div>

        <!-- DOCTOR FORM -->
        <div class="doctor-form-container">
            <form method="POST" action="{{ route('doctors.store') }}" id="doctorForm" class="doctor-form">
                @csrf

                <!-- Doctor ID -->
                <div class="form-group">
                    <label>Doctor ID</label>
                    <div class="doctor-id">Auto-Generated</div>
                </div>

                <!-- Form Grid - 2 columns -->
                <div class="form-grid">
                    <!-- Column 1 -->
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" placeholder="Enter Full Name" required>
                    </div>

                    <div class="form-group">
                        <label>Specialty <span class="required">*</span></label>
                        <input type="text" name="speciality" placeholder="Enter Specialty" required>
                    </div>

                    <div class="form-group">
                        <label>Experience (Years)</label>
                        <input type="number" name="experience" placeholder="Enter Years of Experience" min="0">
                    </div>

                    <div class="form-group">
                        <label>Phone <span class="required">*</span></label>
                        <input type="tel" name="phone_number" placeholder="Enter Phone Number" required>
                    </div>

                    <!-- Column 2 -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter Email Address">
                    </div>

                    <div class="form-group">
                        <label>Consultation Fee</label>
                        <input type="number" name="consultation_fee" placeholder="Enter Consultation Fee" min="0">
                    </div>

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="">Select Status</option>
                            <option value="Active">Active</option>
                            <option value="On Leave">On Leave</option>
                        </select>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="form-footer">
                    <button type="submit" class="submit-btn">Save Doctor</button>
                    <a href="{{ route('doctors.index') }}" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
    /* Sidebar styles (same as index) */
    .sidebar {
        width: 250px;
        background: linear-gradient(to bottom, #1e40af, #3b82f6);
        color: white;
        padding: 20px;
        min-height: 100vh;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        text-align: center;
    }

    .sidebar nav a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        margin-bottom: 5px;
        border-radius: 8px;
        color: #dbeafe;
        text-decoration: none;
        transition: all 0.3s;
    }

    .sidebar nav a:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .sidebar nav i {
        width: 20px;
        text-align: center;
    }

    .logout-form {
        margin-top: auto;
    }

    .logout {
        color: #fca5a5 !important;
    }

    .logout:hover {
        background-color: rgba(239, 68, 68, 0.1) !important;
    }

    /* Form styles */
    .doctor-form-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .doctor-form {
        background-color: #f6fcff;
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #1f3b57;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .required {
        color: #ef4444;
    }

    /* Form Grid Layout - 2 equal columns */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    /* Input boxes */
    .form-group input,
    .form-group select,
    .doctor-id {
        width: 100%;
        padding: 12px 14px;
        border-radius: 8px;
        border: 2px solid #c8e1f3;
        font-size: 14px;
        outline: none;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }

    /* Doctor ID style */
    .doctor-id {
        background-color: #eaf4fb;
        font-weight: 600;
        color: #355f8c;
        grid-column: 1 / -1;
    }

    /* Select dropdown */
    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23355f8c'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px;
    }

    /* Footer Buttons */
    .form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        padding-top: 25px;
        border-top: 1px solid #e0f2fe;
    }

    .submit-btn {
        padding: 14px 30px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(to right, #3b82f6, #2563eb);
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 150px;
    }

    .submit-btn:hover {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        transform: translateY(-1px);
    }

    .cancel-btn {
        padding: 14px 30px;
        border: 1px solid #94a3b8;
        border-radius: 8px;
        background: transparent;
        color: #64748b;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        transition: all 0.2s ease;
        min-width: 150px;
    }

    .cancel-btn:hover {
        background-color: #f8fafc;
        border-color: #ef4444;
        color: #ef4444;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-footer {
            flex-direction: column;
        }

        .submit-btn,
        .cancel-btn {
            width: 100%;
            min-width: auto;
        }
    }
</style>
@endsection