<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    // Show appointments page
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        $patients = Patient::all();
        $doctors  = Doctor::all();
        $services = Service::all();

        return view('appointments', compact('appointments', 'patients', 'doctors', 'services'));
    }

    // Store new appointment (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
        ]);

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
        ]);

        // Return JSON for live table update
        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->full_name ?? '-',
                'doctor_name' => $appointment->doctor->full_name ?? '-',
                'service_name' => $appointment->service->service_name ?? '-',
                'appointment_date' => $appointment->appointment_date,
            ]
        ]);
    }
}
