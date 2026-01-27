<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Information - Database Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
            padding: 30px;
            color: #333;
        }
        
        .db-form-container {
            max-width: 900px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .db-form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px 30px;
        }
        
        .db-form-header h1 {
            font-size: 26px;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .db-form-header .db-info {
            font-size: 14px;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 10px;
        }
        
        .db-info-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .db-table-info {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 30px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #495057;
        }
        
        .db-table-info span {
            color: #667eea;
            font-weight: 600;
        }
        
        .db-form-content {
            padding: 30px;
        }
        
        .db-form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .db-form-group {
            position: relative;
        }
        
        .db-form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .db-field-info {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .db-field-type {
            background-color: #e9ecef;
            padding: 2px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }
        
        .db-field-constraint {
            display: flex;
            gap: 8px;
        }
        
        .constraint {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .constraint.pk {
            background-color: #ffeaa7;
            color: #d35400;
        }
        
        .constraint.uq {
            background-color: #a29bfe;
            color: white;
        }
        
        .constraint.notnull {
            background-color: #fd79a8;
            color: white;
        }
        
        .db-form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .db-form-input:focus {
            outline: none;
            border-color: #667eea;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .db-form-input.readonly {
            background-color: #f1f3f5;
            color: #868e96;
            cursor: not-allowed;
            border-color: #dee2e6;
        }
        
        .db-form-input.error {
            border-color: #e74c3c;
            background-color: #fff5f5;
        }
        
        .db-form-input.success {
            border-color: #2ecc71;
            background-color: #f0fff4;
        }
        
        .db-error-message {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        
        .db-status-group {
            grid-column: span 2;
        }
        
        .db-status-options {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }
        
        .db-status-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .db-status-option input[type="radio"] {
            width: 18px;
            height: 18px;
        }
        
        .db-status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .status-on-leave {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .db-form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e9ecef;
        }
        
        .db-form-hint {
            font-size: 13px;
            color: #6c757d;
            max-width: 60%;
        }
        
        .db-form-hint code {
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
        }
        
        .db-submit-btn {
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .db-submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }
        
        .db-submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .db-validation-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }
        
        .db-validation-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #495057;
        }
        
        .db-validation-list {
            list-style: none;
            font-size: 13px;
            color: #6c757d;
        }
        
        .db-validation-list li {
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .db-validation-list li::before {
            content: "•";
            color: #667eea;
            font-weight: bold;
        }
        
        @media (max-width: 768px) {
            .db-form-grid {
                grid-template-columns: 1fr;
            }
            
            .db-form-actions {
                flex-direction: column;
                gap: 20px;
            }
            
            .db-form-hint {
                max-width: 100%;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <form method="POST" action="{{ route('submit_doctor') }}" class="space-y-6">
                @csrf
    <div class="db-form-container">
        <div class="db-form-header">
            <h1>Doctor Information Form</h1>
            <p>Complete the form according to database schema constraints</p>
            <div class="db-info">
                <div class="db-info-item">Table: tbl_doctor</div>
                <div class="db-info-item">8 Fields</div>
                <div class="db-info-item">1 Primary Key</div>
            </div>
        </div>
        
        <div class="db-table-info">
            Database Schema: <span>tbl_doctor</span> | PK: Doctor ID | UQ: Email | NOT NULL: All fields
        </div>
        
        <div class="db-form-content">
            <form id="dbDoctorForm">
                <div class="db-form-grid">
                    <!-- Doctor ID (PK, Integer, Auto-generated) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Doctor ID</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Integer(30)</span>
                            <div class="db-field-constraint">
                                <span class="constraint pk">PK</span>
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="text" class="db-form-input readonly" value="Auto-generated" readonly data-field="doctor_id" data-type="integer" data-maxlength="30">
                        <div class="db-error-message" id="error-doctor-id"></div>
                    </div>
                    
                    <!-- Full Name (Varchar 20) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Full Name</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Varchar(20)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="text" class="db-form-input" placeholder="Enter full name (max 20 chars)" maxlength="20" data-field="full_name" data-type="varchar" data-maxlength="20" required>
                        <div class="db-error-message" id="error-full-name"></div>
                    </div>
                    
                    <!-- Speciality (Varchar 100) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Speciality</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Varchar(100)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="text" class="db-form-input" placeholder="Medical speciality" maxlength="100" data-field="speciality" data-type="varchar" data-maxlength="100" required>
                        <div class="db-error-message" id="error-speciality"></div>
                    </div>
                    
                    <!-- Experience (Integer 3) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Experience (Years)</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Integer(3)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="number" class="db-form-input" placeholder="Years of experience" min="0" max="999" data-field="experience" data-type="integer" data-maxlength="3" required>
                        <div class="db-error-message" id="error-experience"></div>
                    </div>
                    
                    <!-- Phone Number (Varchar 100) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Phone Number</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Varchar(100)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="tel" class="db-form-input" placeholder="Phone number" maxlength="100" data-field="phone" data-type="varchar" data-maxlength="100" required>
                        <div class="db-error-message" id="error-phone"></div>
                    </div>
                    
                    <!-- Email (Varchar 100, Unique) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Email</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Varchar(100)</span>
                            <div class="db-field-constraint">
                                <span class="constraint uq">UQ</span>
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="email" class="db-form-input" placeholder="Email address" maxlength="100" data-field="email" data-type="varchar" data-maxlength="100" required>
                        <div class="db-error-message" id="error-email"></div>
                    </div>
                    
                    <!-- Consultation Fee (Integer 10) -->
                    <div class="db-form-group">
                        <label class="db-form-label">Consultation Fee</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Integer(10)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <input type="number" class="db-form-input" placeholder="Fee amount" min="0" max="9999999999" data-field="fee" data-type="integer" data-maxlength="10" required>
                        <div class="db-error-message" id="error-fee"></div>
                    </div>
                    
                    <!-- Status (Varchar 20) -->
                    <div class="db-form-group db-status-group">
                        <label class="db-form-label">Status</label>
                        <div class="db-field-info">
                            <span class="db-field-type">Varchar(20)</span>
                            <div class="db-field-constraint">
                                <span class="constraint notnull">NOT NULL</span>
                            </div>
                        </div>
                        <div class="db-status-options">
                            <label class="db-status-option">
                                <input type="radio" name="status" value="Active" checked data-field="status" data-type="varchar" data-maxlength="20" required>
                                <span class="db-status-badge status-active">Active</span>
                            </label>
                            <label class="db-status-option">
                                <input type="radio" name="status" value="Inactive" data-field="status" data-type="varchar" data-maxlength="20">
                                <span class="db-status-badge status-inactive">Inactive</span>
                            </label>
                            <label class="db-status-option">
                                <input type="radio" name="status" value="On Leave" data-field="status" data-type="varchar" data-maxlength="20">
                                <span class="db-status-badge status-on-leave">On Leave</span>
                            </label>
                        </div>
                        <div class="db-error-message" id="error-status"></div>
                    </div>
                </div>
                
                <div class="db-validation-summary">
                    <div class="db-validation-title">Database Constraints Validation:</div>
                    <ul class="db-validation-list">
                        <li>All fields are NOT NULL (required)</li>
                        <li>Email must be unique (UQ constraint)</li>
                        <li>Doctor ID is Primary Key (auto-generated)</li>
                        <li>Field lengths must match database schema</li>
                        <li>Integer fields accept only numbers</li>
                    </ul>
                </div>
                
                <div class="db-form-actions">
                    <div class="db-form-hint">
                        <p><i class="fas fa-info-circle"></i> This form validates against database schema <code>tbl_doctor</code>. All validations match the specified column types and constraints.</p>
                    </div>
                    <button type="submit" class="db-submit-btn" id="dbSubmitBtn">
                        <i class="fas fa-database"></i> Submit to Database
                    </button>
                </div>
            </form>
        </div>
    </div>
    <form>
</body>
</html>