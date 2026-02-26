<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $patient->full_name }} - Patient Medical History Report</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 25px;
            color: #333;
            line-height: 1.4;
        }

        /* Main header - centered with bigger MediCore */
        .main-header {
            border-bottom: 2px solid #0284c7;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
        .main-header h1 {
            color: #0284c7;
            margin: 0;
            font-size: 32px; /* Increased from 24px */
            font-weight: 600;
            line-height: 1.2;
        }

        .generated-date {
            text-align: left;
            color: #64748b;
            font-size: 11px;
            margin-bottom: 20px;
            font-weight: normal;
        }

        /* Sub header with patient name and total visits on right */
        .sub-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e2e8f0;
        }
        .sub-header h2 {
            color: #334155;
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }

        /* Total visits box - on the right corner */
        .total-visits-box {
            background-color: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 9999px;
            padding: 4px 16px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0;
            margin-left: auto; /* Pushes to the right */
        }
        .total-visits-box .number {
            font-size: 16px;
            font-weight: 700;
            color: #0284c7;
            line-height: 1.5;
        }
        .total-visits-box .label {
            font-size: 10px;
            font-weight: 600;
            color: #0369a1;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* Rest of your existing CSS remains exactly the same */
        /* Patient info table */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .info-table tr {
            border-bottom: 1px solid #e2e8f0;
        }
        .info-table tr:last-child {
            border-bottom: none;
        }
        .info-table td {
            padding: 8px 12px;
        }
        .info-table td:first-child {
            width: 30%;
            background: #f8fafc;
            font-weight: 600;
            color: #475569;
            border-right: 1px solid #e2e8f0;
        }
        .info-table td:last-child {
            width: 70%;
            color: #0f172a;
        }

        /* Section title */
        .section-title {
            color: #0284c7;
            font-size: 16px;
            font-weight: 600;
            margin: 20px 0 10px 0;
            border-bottom: 1px solid #0284c7;
            padding-bottom: 5px;
        }

        /* Medical info - side by side */
        .medical-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        .medical-box {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
        }
        .medical-box .label {
            font-weight: 600;
            color: #475569;
            font-size: 11px;
            margin-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 5px;
        }
        .medical-box .value {
            color: #0f172a;
            font-size: 11px;
            line-height: 1.5;
        }

        /* Appointments table */
        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .appointments-table th {
            background: #f1f5f9;
            color: #334155;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            font-size: 10px;
        }
        .appointments-table td {
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
        }

        /* Discharge table - updated for 4 columns */
        .discharge-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .discharge-table th {
            background: #f1f5f9;
            color: #334155;
            padding: 8px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            font-size: 10px;
        }
        .discharge-table td {
            padding: 8px 6px;
            border: 1px solid #e2e8f0;
        }

        /* Status text - no colors */
        .status-text {
            color: #334155;
            font-weight: normal;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #94a3b8;
            font-size: 9px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }

        /* Records info */
        .records-info {
            text-align: right;
            font-size: 10px;
            color: #64748b;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        /* Service column */
        .service-name {
            color: #0f172a;
        }

        /* Fix for duplicate Dr. */
        .doctor-name {
            color: #0f172a;
        }
    </style>
</head>
<body>
    <!-- Main header - centered with bigger MediCore -->
    <div class="main-header">
        <h1>MediCore - Patient Medical History Report</h1>
    </div>

    <!-- Generated date - left aligned -->
    <div class="generated-date">
        Generated on: {{ \Carbon\Carbon::parse($generatedDate)->format('F j, Y') }}
    </div>

    <!-- Sub header with patient name and total visits on right -->
    <div class="sub-header">
        <h2>{{ $patient->full_name }}'s Information</h2>
        <div class="total-visits-box">
            <span class="number">{{ $totalVisits }}</span>
            <span class="label">TOTAL VISITS</span>
        </div>
    </div>

    <!-- Rest of your HTML remains exactly the same -->
    <!-- Patient Information - TWO COLUMN TABLE -->
    <table class="info-table">
        <tr>
            <td>Patient ID:</td>
            <td>{{ $patient->id }}</td>
        </tr>
        <tr>
            <td>Full Name:</td>
            <td>{{ $patient->full_name }}</td>
        </tr>
        <tr>
            <td>Date of Birth:</td>
            <td>{{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td>Age / Gender:</td>
            <td>{{ $patient->age ?? 'N/A' }} | {{ $patient->sex_gender ? ucfirst($patient->sex_gender) : 'N/A' }}</td>
        </tr>
        <tr>
            <td>Phone Number:</td>
            <td>{{ $patient->phone_number ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Email:</td>
            <td>{{ $patient->email ?? 'Not provided' }}</td>
        </tr>
        <tr>
            <td>Address:</td>
            <td>{{ $patient->address ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Registration Date:</td>
            <td>{{ $patient->registration_date ? \Carbon\Carbon::parse($patient->registration_date)->format('F j, Y') : 'N/A' }}</td>
        </tr>
        <tr>
            <td>Blood Type:</td>
            <td>{{ $patient->blood_type ?? 'Unknown' }}</td>
        </tr>
        <tr>
            <td>Alcohol Consumption:</td>
            <td>{{ $patient->alcohol_consumption ? ucfirst($patient->alcohol_consumption) : 'None' }}</td>
        </tr>
        <tr>
            <td>Assigned Doctor:</td>
            <td>
                @if($patient->doctor)
                    Dr. {{ $patient->doctor->full_name }}
                    @if($patient->doctor->speciality)
                        ({{ $patient->doctor->speciality }})
                    @endif
                @else
                    Not Assigned
                @endif
            </td>
        </tr>
    </table>

    <!-- Medical History - Side by side -->
    <div class="medical-grid">
        <div class="medical-box">
            <div class="label">Medical Conditions</div>
            <div class="value">
                @if($patient->known_medical_conditions && trim($patient->known_medical_conditions) !== '')
                    {{ $patient->known_medical_conditions }}
                @else
                    None
                @endif
            </div>
        </div>
        <div class="medical-box">
            <div class="label">Allergies</div>
            <div class="value">
                @if($patient->allergies && trim($patient->allergies) !== '')
                    {{ $patient->allergies }}
                @else
                    None
                @endif
            </div>
        </div>
    </div>

    @if($appointments->isNotEmpty())
        <div class="section-title">Appointment History ({{ $appointments->count() }} records)</div>
        <table class="appointments-table">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="25%">Date</th>
                    <th width="30%">Doctor</th>
                    <th width="35%">Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $index => $appointment)
                    @php
                        $displayStatus = $appointment->status == 'scheduled' ? 'pending' : $appointment->status;

                        // Get service name from the service relationship
                        $serviceName = 'N/A';

                        // Check if service relationship exists and get the name
                        if($appointment->service) {
                            // Try different possible field names in the services table
                            $serviceName = $appointment->service->service_name ??
                                          $appointment->service->name ??
                                          'Service #' . $appointment->service_id;
                        } elseif($appointment->service_id) {
                            // If relationship isn't loaded but we have an ID, show the ID
                            $serviceName = 'Service ID: ' . $appointment->service_id;
                        }

                        // Get doctor name without duplicate "Dr."
                        $doctorDisplay = 'N/A';
                        if($appointment->doctor) {
                            $doctorName = $appointment->doctor->full_name ?? $appointment->doctor->name ?? '';
                            // Remove any existing "Dr." prefix to avoid duplication
                            $doctorName = preg_replace('/^Dr\.\s*/i', '', $doctorName);
                            $doctorDisplay = 'Dr. ' . $doctorName;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M j, Y') }}</td>
                        <td class="doctor-name">{{ $doctorDisplay }}</td>
                        <td class="service-name">{{ $serviceName }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($discharges->isNotEmpty())
        <div class="section-title">Discharge History ({{ $discharges->count() }} records)</div>
        <table class="discharge-table">
            <thead>
                <tr>
                    <th width="10%">No</th>
                    <th width="25%">Date</th>
                    <th width="30%">Doctor</th>
                    <th width="35%">Service</th>
                </tr>
            </thead>
            <tbody>
                @foreach($discharges as $index => $discharge)
                    @php
                        // Get service name for discharge
                        $serviceName = $discharge->service_name ?? 'Medical Service';

                        // Get doctor name without duplicate "Dr."
                        $doctorDisplay = 'N/A';
                        if(isset($discharge->doctor_name) && !empty($discharge->doctor_name)) {
                            $doctorName = preg_replace('/^Dr\.\s*/i', '', $discharge->doctor_name);
                            $doctorDisplay = 'Dr. ' . $doctorName;
                        }
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($discharge->created_at ?? $discharge->discharge_date ?? now())->format('M j, Y') }}</td>
                        <td class="doctor-name">{{ $doctorDisplay }}</td>
                        <td class="service-name">{{ $serviceName }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($appointments->isEmpty() && $discharges->isEmpty())
        <div style="text-align: center; padding: 20px; border: 1px dashed #cbd5e1; border-radius: 8px; color: #94a3b8;">
            No visit history found for this patient.
        </div>
    @endif

    <div class="records-info">
        Total Records: {{ $appointments->count() }} appointments + {{ $discharges->count() }} discharges
    </div>

    <div class="footer">
        <p>This report was generated from MediCore Patient Management System</p>
        <p>© {{ date('Y') }} MediCore. All rights reserved.</p>
    </div>
</body>
</html>