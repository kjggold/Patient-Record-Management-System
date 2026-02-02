@extends('layouts.app')

@section('title', 'Edit Doctor')

@section('content')
    <div class="app">
        <!-- SIDEBAR -->
                @include('layouts.sidebar')


        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6">
            <!-- HEADER -->
            <div class="flex w-full sm:w-auto gap-2 mb-6">
                <h1 class="text-2xl font-semibold text-slate-700">Edit Doctor</h1>
            </div>

            <!-- EDIT DOCTOR FORM -->
            <div class="doctor-card mx-auto">
                <!-- Header -->
                <div class="header-section">
                    <h2>Edit Doctor Information</h2>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('doctors.update', $doctor->id) }}" id="editDoctorForm">
                    @csrf
                    @method('PUT')

                    <!-- Doctor ID -->
                    <div class="form-group">
                        <label>Doctor ID</label>
                        <div class="doctor-id">{{ $doctor->id }}</div>
                    </div>

                    <!-- Form Grid - 2 columns -->
                    <div class="form-grid">
                        <!-- Column 1 -->
                        <div class="form-group">
                            <label>Full Name <span class="required">*</span></label>
                            <input type="text" 
                                   name="full_name" 
                                   placeholder="Enter Full Name" 
                                   value="{{ old('full_name', $doctor->full_name) }}"
                                   required>
                            @error('full_name')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Specialty <span class="required">*</span></label>
                            <input type="text" 
                                   name="speciality" 
                                   placeholder="Enter Specialty" 
                                   value="{{ old('speciality', $doctor->speciality) }}"
                                   required>
                            @error('speciality')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Experience (Years) <span class="required">*</span></label>
                            <input type="number" 
                                   name="experience" 
                                   placeholder="Enter Years of Experience" 
                                   min="0" 
                                   value="{{ old('experience', $doctor->experience) }}"
                                   required>
                            @error('experience')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Phone <span class="required">*</span></label>
                            <input type="tel" 
                                   name="phone_number" 
                                   placeholder="Enter Phone Number" 
                                   value="{{ old('phone_number', $doctor->phone_number) }}"
                                   required>
                            @error('phone_number')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Column 2 -->
                        <div class="form-group">
                            <label>Email <span class="required">*</span></label>
                            <input type="email" 
                                   name="email" 
                                   placeholder="Enter Email Address" 
                                   value="{{ old('email', $doctor->email) }}"
                                   required>
                            @error('email')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Consultation Fee <span class="required">*</span></label>
                            <input type="text" 
                                   name="consultation_fee" 
                                   placeholder="Enter Consultation Fee" 
                                   value="{{ old('consultation_fee', $doctor->consultation_fee) }}"
                                   required>
                            @error('consultation_fee')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status <span class="required">*</span></label>
                            <select name="status" required>
                                <option value="" {{ !old('status', $doctor->status) ? 'selected' : '' }}>Select Status</option>
                                <option value="Active" {{ old('status', $doctor->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="On Leave" {{ old('status', $doctor->status) == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Footer Buttons -->
                    <div class="footer-buttons">
                        <a href="{{ route('doctors.index') }}" class="cancel-btn text-center">
                            Cancel
                        </a>
                        <button type="submit" class="submit-btn">Update Doctor</button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <style>

        /* MAIN CONTENT */
        main {
            flex: 1;
            padding: 1.5rem;
        }

        /* Edit Doctor Form Styles */
        .doctor-card {
            background-color: #f6fcff;
            width: 500px;
            max-width: 95vw;
            padding: 25px 30px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            margin: 0 auto;
        }

        /* Header Section */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0f2fe;
        }

        .doctor-card h2 {
            color: #2b6de8;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .required {
            color: #ef4444;
        }

        /* Form Grid Layout - 2 equal columns */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1f3b57;
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Input boxes */
        .form-group input,
        .form-group select,
        .doctor-id {
            width: 100%;
            padding: 10px 12px;
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
            margin-bottom: 10px;
        }

        /* Select dropdown */
        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23355f8c'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Footer Buttons */
        .footer-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding-top: 20px;
            border-top: 1px solid #e0f2fe;
        }

        .submit-btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(to right, #10b981, #059669);
            color: white;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .submit-btn:hover {
            background: linear-gradient(to right, #059669, #047857);
            transform: translateY(-1px);
        }

        .cancel-btn {
            padding: 12px 24px;
            border: 1px solid #94a3b8;
            border-radius: 8px;
            background: transparent;
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            min-width: 100px;
        }

        .cancel-btn:hover {
            background-color: #f8fafc;
            border-color: #ef4444;
            color: #ef4444;
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .app {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                min-height: auto;
                padding: 15px;
            }

            .sidebar nav {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
            }

            .sidebar nav a {
                flex: 1;
                min-width: 120px;
                justify-content: center;
                padding: 10px;
            }

            .logo {
                margin-bottom: 15px;
            }

            .doctor-card {
                width: 90vw;
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .footer-buttons {
                flex-direction: column;
            }

            .submit-btn,
            .cancel-btn {
                width: 100%;
                min-width: auto;
            }
        }

        /* Error message styling */
        .text-red-500 {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: block;
        }
    </style>
@endsection