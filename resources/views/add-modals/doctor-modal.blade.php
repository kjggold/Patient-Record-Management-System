<!-- ADD DOCTOR MODAL -->
<div id="addModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="doctor-card">
            <!-- Header with close icon -->
            <div class="header-section">
                <h2>Doctor Information</h2>
                <button type="button" onclick="closeAddModal()" class="close-icon" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form Content -->
            <form method="POST" action="{{ route('doctors.store') }}" id="dbDoctorForm">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="full_name" placeholder="Enter Full Name" value="{{ old('full_name') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Specialty <span class="required">*</span></label>
                        <input type="text" name="speciality" placeholder="Enter Specialty" value="{{ old('speciality') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Phone <span class="required">*</span></label>
                        <input type="tel" name="phone_number" placeholder="Enter Phone Number" value="{{ old('phone_number') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" placeholder="Enter Email Address" value="{{ old('email') }}" required>
                    </div>
                </div>

                <div class="footer-buttons">
                    <button type="button" onclick="closeAddModal()" class="cancel-btn">Cancel</button>
                    <button type="submit" class="submit-btn">Submit</button>

                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Add Doctor Modal Styles */
    #addModal .doctor-card {
        background-color: #f6fcff;
        width: 500px;
        max-width: 95vw;
        padding: 25px 30px;
        border-radius: 14px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        margin: 20px;
    }

    #addModal.hidden {
        display: none !important;
    }

    #addModal.flex {
        display: flex !important;
    }

    /* Header Section */
    #addModal .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e0f2fe;
    }

    #addModal .doctor-card h2 {
        color: #2b6de8;
        font-size: 24px;
        font-weight: 700;
        margin: 0;
    }

    #addModal .required {
        color: #ef4444;
    }

    #addModal .close-icon {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 6px;
        border-radius: 50%;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
    }

    #addModal .close-icon:hover {
        background-color: #f1f5f9;
        color: #ef4444;
    }

    /* Form Grid Layout - 2 equal columns */
    #addModal .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
        margin-bottom: 25px;
    }

    #addModal .form-group {
        margin-bottom: 0;
    }

    #addModal .form-group label {
        display: block;
        font-weight: 600;
        color: #1f3b57;
        margin-bottom: 8px;
        font-size: 14px;
    }

    #addModal .form-group input {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 2px solid #c8e1f3;
        font-size: 14px;
        outline: none;
        background-color: #ffffff;
        transition: all 0.2s ease;
    }

    #addModal .form-group input:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
    }

    /* Footer Buttons */
    #addModal .footer-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 20px;
        border-top: 1px solid #e0f2fe;
    }

    #addModal .submit-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(to right, #3b82f6, #2563eb);
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 100px;
    }

    #addModal .submit-btn:hover {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
        transform: translateY(-1px);
    }

    #addModal .cancel-btn {
        padding: 12px 24px;
        border: 1px solid #94a3b8;
        border-radius: 8px;
        background: transparent;
        color: #64748b;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 100px;
    }

    #addModal .cancel-btn:hover {
        background-color: #f8fafc;
        border-color: #ef4444;
        color: #ef4444;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        #addModal .doctor-card {
            width: 90vw;
            padding: 20px;
        }

        #addModal .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        #addModal .footer-buttons {
            flex-direction: column;
        }

        #addModal .submit-btn,
        #addModal .cancel-btn {
            width: 100%;
            min-width: auto;
        }
    }
</style>