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
            <form method="POST" action="{{ route('doctors.store') }}" id="dbDoctorForm" onsubmit="return validateDoctorForm()">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text"
                               name="full_name"
                               id="doctor_full_name"
                               placeholder="Enter Full Name"
                               value="{{ old('full_name') }}"
                               oninput="previewDoctorName(this)"
                               onblur="formatDoctorNameOnBlur(this)"
                               required>
                        <small class="text-gray-500 text-xs mt-1 block" id="name-hint">Name will be formatted as: Dr. Firstname Lastname</small>
                    </div>

                    <div class="form-group">
                        <label>Specialty <span class="required">*</span></label>
                        <input type="text"
                               name="speciality"
                               id="speciality"
                               placeholder="Enter Specialty"
                               value="{{ old('speciality') }}"
                               onblur="capitalizeWords(this)"
                               required>
                    </div>

                    <div class="form-group">
                        <label>Phone <span class="required">*</span></label>
                        <div class="phone-input-container">
                            <div class="country-code-selector">
                                <select name="country_code" id="doctor_country_code" class="country-code">
                                    <option value="+95" data-display="+95" data-full="+95 (Myanmar)" selected>+95</option>
                                    <option value="+1" data-display="+1" data-full="+1 (USA/Canada)">+1</option>
                                    <option value="+44" data-display="+44" data-full="+44 (UK)">+44</option>
                                    <option value="+61" data-display="+61" data-full="+61 (Australia)">+61</option>
                                    <option value="+65" data-display="+65" data-full="+65 (Singapore)">+65</option>
                                    <option value="+86" data-display="+86" data-full="+86 (China)">+86</option>
                                    <option value="+81" data-display="+81" data-full="+81 (Japan)">+81</option>
                                    <option value="+82" data-display="+82" data-full="+82 (South Korea)">+82</option>
                                    <option value="+66" data-display="+66" data-full="+66 (Thailand)">+66</option>
                                    <option value="+84" data-display="+84" data-full="+84 (Vietnam)">+84</option>
                                    <option value="+60" data-display="+60" data-full="+60 (Malaysia)">+60</option>
                                    <option value="+62" data-display="+62" data-full="+62 (Indonesia)">+62</option>
                                    <option value="+63" data-display="+63" data-full="+63 (Philippines)">+63</option>
                                    <option value="+91" data-display="+91" data-full="+91 (India)">+91</option>
                                    <option value="+94" data-display="+94" data-full="+94 (Sri Lanka)">+94</option>
                                    <option value="+977" data-display="+977" data-full="+977 (Nepal)">+977</option>
                                </select>
                            </div>
                            <div class="phone-number-field">
                                <input type="tel"
                                       name="phone_number"
                                       id="doctor_phone_number"
                                       placeholder="numbers only"
                                       oninput="validateDoctorPhoneNumber(this)"
                                       maxlength="15"
                                       required>
                            </div>
                        </div>
                        <small class="text-gray-500 text-xs mt-1 block" id="doctor-phone-hint"></small>
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email"
                               name="email"
                               id="doctor_email"
                               placeholder="Enter Email Address"
                               value="{{ old('email') }}"
                               oninput="validateDoctorEmail(this)"
                               onblur="formatDoctorEmail(this)"
                               required>
                        <small class="text-gray-500 text-xs mt-1 block" id="doctor-email-hint"></small>
                    </div>
                </div>

                <div class="footer-buttons">
                    <button type="submit" class="submit-btn">Submit</button>
                    <button type="button" onclick="closeAddModal()" class="cancel-btn">Cancel</button>
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

    #addModal .form-group input.error {
        border-color: #ef4444;
    }

    #addModal .form-group input.error:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.2);
    }

    /* Phone input styling */
    #addModal .phone-input-container {
        display: flex;
        gap: 8px;
        width: 100%;
    }

    #addModal .country-code-selector {
        flex: 0 0 58px;
    }

    #addModal .country-code {
        width: 100%;
        padding: 10px 8px;
        border-radius: 8px;
        border: 2px solid #c8e1f3;
        font-size: 13px;
        background-color: #fff;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 8px center;
        background-size: 12px;
        padding-right: 24px;
        text-align: center;
    }

    #addModal .country-code option {
        text-align: left;
        padding: 8px;
    }

    #addModal .country-code:focus {
        border-color: #4a90e2;
        box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
        outline: none;
    }

    #addModal .phone-number-field {
        flex: 1;
    }

    #addModal .phone-number-field input {
        width: 100%;
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

    /* Small text helper */
    #addModal .text-gray-500.text-xs {
        font-size: 11px;
        margin-top: 4px;
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

        #addModal .phone-input-container {
            flex-direction: column;
            gap: 4px;
        }

        #addModal .country-code-selector {
            flex: auto;
            width: 100px;
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

<script>
    // Show full country names when dropdown is opened
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('doctor_country_code');

        if (countrySelect) {
            // Store original texts
            Array.from(countrySelect.options).forEach(option => {
                option.setAttribute('data-display', option.value);
                option.setAttribute('data-full', option.text);
                option.text = option.value; // Set initial display to just the code
            });

            // On mousedown (before opening), show full names
            countrySelect.addEventListener('mousedown', function() {
                Array.from(this.options).forEach(option => {
                    option.text = option.getAttribute('data-full');
                });
            });

            // On blur/change, revert to just the code
            countrySelect.addEventListener('change', function() {
                Array.from(this.options).forEach(option => {
                    option.text = option.getAttribute('data-display');
                });
            });

            countrySelect.addEventListener('blur', function() {
                Array.from(this.options).forEach(option => {
                    option.text = option.getAttribute('data-display');
                });
            });
        }
    });

    // Preview doctor name formatting without modifying input
    function previewDoctorName(input) {
        let value = input.value;
        const hint = document.getElementById('name-hint');

        if (hint && value.trim()) {
            let previewValue = value.trim();

            // Remove any existing "Dr." or "Dr " prefixes for preview
            previewValue = previewValue.replace(/^Dr\.?\s*/i, '');

            // Capitalize first letter of each word for preview
            previewValue = previewValue.toLowerCase().replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });

            // Add Dr. prefix for preview
            if (previewValue) {
                hint.className = 'text-xs mt-1 block text-green-600';
                hint.textContent = '✓ Name will be saved as: Dr. ' + previewValue;
            }
        } else {
            hint.className = 'text-xs mt-1 block text-gray-500';
            hint.textContent = 'Name will be formatted as: Dr. Firstname Lastname';
        }
    }

    // Format doctor name on blur (when user leaves the field)
    function formatDoctorNameOnBlur(input) {
        let value = input.value.trim();

        if (value) {
            // Remove any existing "Dr." or "Dr " prefixes to avoid duplication
            value = value.replace(/^Dr\.?\s*/i, '');

            // Capitalize first letter of each word
            value = value.toLowerCase().replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });

            // Add Dr. prefix
            input.value = 'Dr. ' + value;

            // Update hint
            const hint = document.getElementById('name-hint');
            if (hint) {
                hint.className = 'text-xs mt-1 block text-green-600';
                hint.textContent = '✓ Name will be saved as: ' + input.value;
            }
        }
    }

    // Capitalize words function (for specialty field)
    function capitalizeWords(input) {
        if (input.value.trim()) {
            let value = input.value.toLowerCase().replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
            input.value = value;
        }
    }

    // Phone Number Validation
    function validateDoctorPhoneNumber(input) {
        // Remove any non-digit characters
        let phone = input.value.replace(/\D/g, '');

        // Get the selected country code
        const countryCode = document.getElementById('doctor_country_code').value;

        // Store the original cursor position
        const cursorPos = input.selectionStart;
        const originalLength = input.value.length;

        // Update the input value with only digits
        input.value = phone;

        // Validate based on country
        const country = countryCode.replace('+', '');
        let isValid = true;
        let hintMessage = '';

        switch(country) {
            case '95': // Myanmar
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length < 7) {
                    isValid = false;
                    hintMessage = 'Myanmar phone numbers should be at least 7 digits';
                } else if (phone.length > 10) {
                    isValid = false;
                    hintMessage = 'Myanmar phone numbers should not exceed 10 digits';
                } else {
                    hintMessage = `✓ Valid Myanmar phone number (${phone.length} digits)`;
                }
                break;
            case '1': // USA/Canada
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length !== 10) {
                    isValid = false;
                    hintMessage = 'USA/Canada phone numbers should be exactly 10 digits';
                } else {
                    hintMessage = '✓ Valid USA/Canada phone number';
                }
                break;
            case '44': // UK
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length < 10 || phone.length > 11) {
                    isValid = false;
                    hintMessage = 'UK phone numbers should be 10-11 digits';
                } else {
                    hintMessage = '✓ Valid UK phone number';
                }
                break;
            case '61': // Australia
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length !== 9) {
                    isValid = false;
                    hintMessage = 'Australian phone numbers should be exactly 9 digits';
                } else {
                    hintMessage = '✓ Valid Australian phone number';
                }
                break;
            case '65': // Singapore
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length !== 8) {
                    isValid = false;
                    hintMessage = 'Singapore phone numbers should be exactly 8 digits';
                } else {
                    hintMessage = '✓ Valid Singapore phone number';
                }
                break;
            case '86': // China
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length < 11 || phone.length > 12) {
                    isValid = false;
                    hintMessage = 'Chinese phone numbers should be 11-12 digits';
                } else {
                    hintMessage = '✓ Valid Chinese phone number';
                }
                break;
            default:
                // Generic validation for other countries (7-15 digits)
                if (phone.length === 0) {
                    isValid = false;
                    hintMessage = 'Phone number is required';
                } else if (phone.length < 7) {
                    isValid = false;
                    hintMessage = 'Phone number should be at least 7 digits';
                } else if (phone.length > 15) {
                    isValid = false;
                    hintMessage = 'Phone number should not exceed 15 digits';
                } else {
                    hintMessage = `✓ Valid phone number (${phone.length} digits)`;
                }
        }

        // Update the hint text
        const hintElement = document.getElementById('doctor-phone-hint');
        if (hintElement) {
            hintElement.textContent = hintMessage;
            hintElement.className = `text-xs mt-1 block ${isValid ? 'text-green-600' : 'text-red-500'}`;
        }

        // Add/remove error class
        const phoneField = document.querySelector('.phone-number-field input');
        if (phoneField) {
            if (isValid) {
                phoneField.classList.remove('error');
            } else {
                phoneField.classList.add('error');
            }
        }

        // Adjust cursor position if needed
        if (input.value.length !== originalLength) {
            input.setSelectionRange(cursorPos, cursorPos);
        }

        return isValid;
    }

    // Email Validation
    function validateDoctorEmail(input) {
        const email = input.value.trim();
        const hintElement = document.getElementById('doctor-email-hint');

        if (!email) {
            hintElement.textContent = 'Email is required';
            hintElement.className = 'text-xs mt-1 block text-red-500';
            input.classList.add('error');
            return false;
        }

        // Basic email validation regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const isValid = emailRegex.test(email);

        if (isValid) {
            hintElement.textContent = '✓ Valid email format';
            hintElement.className = 'text-xs mt-1 block text-green-600';
            input.classList.remove('error');
        } else {
            hintElement.textContent = 'Please enter a valid email address (e.g., name@example.com)';
            hintElement.className = 'text-xs mt-1 block text-red-500';
            input.classList.add('error');
        }

        return isValid;
    }

    // Format email to lowercase
    function formatDoctorEmail(input) {
        if (input.value.trim()) {
            input.value = input.value.trim().toLowerCase();
            validateDoctorEmail(input);
        }
    }

    // Form validation before submission
    function validateDoctorForm() {
        const nameInput = document.getElementById('doctor_full_name');
        const specialtyInput = document.getElementById('speciality');
        const phoneInput = document.getElementById('doctor_phone_number');
        const emailInput = document.getElementById('doctor_email');

        let isValid = true;
        let errorMessage = '';

        // Validate Name
        if (!nameInput.value.trim()) {
            errorMessage += '• Full name is required\n';
            isValid = false;
        }

        // Validate Specialty
        if (!specialtyInput.value.trim()) {
            errorMessage += '• Specialty is required\n';
            isValid = false;
        }

        // Validate Phone
        if (!phoneInput.value.trim()) {
            errorMessage += '• Phone number is required\n';
            isValid = false;
        } else if (!validateDoctorPhoneNumber(phoneInput)) {
            errorMessage += '• Please enter a valid phone number for the selected country\n';
            isValid = false;
        }

        // Validate Email
        if (!emailInput.value.trim()) {
            errorMessage += '• Email is required\n';
            isValid = false;
        } else if (!validateDoctorEmail(emailInput)) {
            errorMessage += '• Please enter a valid email address\n';
            isValid = false;
        }

        // Format name before submission if not already formatted
        if (nameInput.value.trim() && !nameInput.value.trim().startsWith('Dr.')) {
            formatDoctorNameOnBlur(nameInput);
        }

        if (!isValid && errorMessage) {
            alert('Please fix the following errors:\n\n' + errorMessage);
            return false;
        }

        return true;
    }

    // Initialize event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const countryCodeSelect = document.getElementById('doctor_country_code');
        const phoneInput = document.getElementById('doctor_phone_number');
        const emailInput = document.getElementById('doctor_email');
        const nameInput = document.getElementById('doctor_full_name');
        const specialtyInput = document.getElementById('speciality');

        if (countryCodeSelect && phoneInput) {
            countryCodeSelect.addEventListener('change', function() {
                validateDoctorPhoneNumber(phoneInput);
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                formatDoctorEmail(this);
            });
        }

        if (specialtyInput) {
            specialtyInput.addEventListener('blur', function() {
                capitalizeWords(this);
            });
        }

        if (nameInput) {
            nameInput.addEventListener('blur', function() {
                formatDoctorNameOnBlur(this);
            });
        }
    });

    // Function to open modal
    function openAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        // Reset form and hints when opening
        const form = document.getElementById('dbDoctorForm');
        if (form) form.reset();

        const phoneHint = document.getElementById('doctor-phone-hint');
        if (phoneHint) {
            phoneHint.textContent = '';
            phoneHint.className = 'text-xs mt-1 block text-gray-500';
        }

        const emailHint = document.getElementById('doctor-email-hint');
        if (emailHint) {
            emailHint.textContent = '';
            emailHint.className = 'text-xs mt-1 block text-gray-500';
        }

        const nameHint = document.getElementById('name-hint');
        if (nameHint) {
            nameHint.textContent = 'Name will be formatted as: Dr. Firstname Lastname';
            nameHint.className = 'text-xs mt-1 block text-gray-500';
        }

        // Reset country code select display
        const countrySelect = document.getElementById('doctor_country_code');
        if (countrySelect) {
            Array.from(countrySelect.options).forEach(option => {
                option.text = option.getAttribute('data-display');
            });
        }
    }

    // Function to close modal
    function closeAddModal() {
        const modal = document.getElementById('addModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
</script>