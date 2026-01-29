<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {    
        $doctors = Doctor::all();
        $patients = Patient::all();
        $services = Service::all();

        return view('auth.addAppointment', compact('doctors', 'patients', 'services'));
    }

    // Change method name to 'store' to match route
    public function store(Request $request)
    {
        $validated = $request->validate([
        'patient_name' => 'required|exists:patients,id',
        'doctor_name' => 'required|exists:doctors,id',
        'service' => 'required|exists:services,id',
        'appointment_date' => 'required|date',
        'appointment_time' => 'required',
        'phone' => 'required|string',
        'notes_optional' => 'nullable|string|max:500',
    ]);

        // Create doctor with validated data
        Appointment::create($validated);

        // Redirect back with success message
        return redirect('/add_appointment')->with('success', 'Appointment added successfully!');
    }
}
