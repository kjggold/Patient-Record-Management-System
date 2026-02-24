@extends('layouts.app')

@section('title', 'Edit Doctor')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    @include('layouts.sidebar')

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-8 ml-60">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-700">Edit Doctor</h1>
            <a href="{{ route('doctors.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600">
                ← Back to Doctors
            </a>
        </div>

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- DOCTOR FORM -->
        <div class="doctor-form-container">
            <form method="POST" action="{{ route('doctors.update', $doctor->id) }}" class="doctor-form">
                @csrf
                @method('PUT')

                <!-- Doctor ID -->
                <div class="form-group">
                    <label>Doctor ID</label>
                    <div class="doctor-id">{{ $doctor->id }}</div>
                </div>

                <!-- Form Grid - 2 columns -->
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $doctor->full_name) }}" placeholder="Enter Full Name" required>
                    </div>

                    <div class="form-group">
                        <label>Specialty <span class="required">*</span></label>
                        <input type="text" name="speciality" value="{{ old('speciality', $doctor->speciality) }}" placeholder="Enter Specialty" required>
                    </div>

                    <div class="form-group">
                        <label>Phone <span class="required">*</span></label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number', $doctor->phone_number) }}" placeholder="Enter Phone Number" required>
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $doctor->email) }}" placeholder="Enter Email Address" required>
                    </div>

                    <div class="form-group">
                        <label>Status <span class="required">*</span></label>
                        <select name="status" required>
                            <option value="Active" {{ old('status', $doctor->status) == 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="On Leave" {{ old('status', $doctor->status) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                            <option value="Inactive" {{ old('status', $doctor->status) == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="form-footer">
                    <button type="submit" class="submit-btn">Update Doctor</button>
                    <a href="{{ route('doctors.index') }}" class="cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>

<style>
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

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

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

    .doctor-id {
        background-color: #eaf4fb;
        font-weight: 600;
        color: #355f8c;
    }

    .form-group select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23355f8c'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 14px center;
        background-size: 16px;
        padding-right: 40px;
    }

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
        display: inline-block;
    }

    .cancel-btn:hover {
        background-color: #f8fafc;
        border-color: #ef4444;
        color: #ef4444;
        text-decoration: none;
    }

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