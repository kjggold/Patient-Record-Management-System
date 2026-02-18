<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Patient Report - {{ $patient->full_name }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1,
        h2 {
            color: #a10000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h1>Patient Report</h1>
    <p><strong>Generated at:</strong> {{ $generated_at }}</p>

    <div class="section">
        <h2>Patient Information</h2>
        <p><strong>Full Name:</strong> {{ $patient->full_name }}</p>
        <p><strong>Patient ID:</strong> {{ $patient->id }}</p>
        <p><strong>Age:</strong> {{ $patient->age ?? 'N/A' }}</p>
        <p><strong>Gender:</strong> {{ ucfirst($patient->sex_gender ?? 'N/A') }}</p>
        <p><strong>Phone:</strong> {{ $patient->phone_number }}</p>
        <p><strong>Blood Type:</strong> {{ $patient->blood_type ?? 'N/A' }}</p>
        <p><strong>Assigned Doctor:</strong> {{ $assignedDoctor->full_name ?? 'Not Assigned' }}</p>
        <p><strong>Total Visits:</strong> {{ $patient->total_visits }}</p>
        <p><strong>Outstanding Balance:</strong> {{ $patient->outstanding_balance }}</p>
    </div>

    <div class="section">
        <h2>Appointment History</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($appointments as $a)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($a->appointment_date)->format('M j, Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->appointment_time)->format('h:i A') }}</td>
                        <td>{{ $a->doctor->full_name ?? 'N/A' }}</td>
                        <td>{{ $a->service->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($a->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
