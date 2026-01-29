<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;    // ✅ Make sure Patient model exists
use App\Models\Doctor;     // ✅ Make sure Doctor model exists

class AppointmentController extends Controller
{
    // Show appointments page
    public function index()
    {
        // Load appointments with patient and doctor relations
        $appointments = Appointment::with(['patient', 'doctor'])->get();

        // Get all patients and doctors for dropdowns / modals
        $patients = Patient::all();
        $doctors = Doctor::all();

        return view('appointments', compact('appointments', 'patients', 'doctors'));
    }

    // Store new appointment
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|string',
        ]);

        Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $request->status,
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
    }
}
