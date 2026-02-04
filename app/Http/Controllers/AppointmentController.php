<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Payment; // Add this import

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $services = Service::all();

        // Load appointments with relationships - FIXED relationship names (singular)
        $query = Appointment::with(['doctor', 'patient', 'service']);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%");
            });
        }

        $appointments = $query->paginate(10);

        return view('appointments', compact('doctors', 'patients', 'services', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'nullable|date_format:H:i',
            'phone' => 'required|string',
            'notes_optional' => 'nullable|string',
        ]);

        // Get authenticated user ID
        $userId = auth()->id();

        // Create with created_by field
        $appointment = Appointment::create([
            'patient_id' => $validated['patient_id'],
            'doctor_id' => $validated['doctor_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'] ?? null,
            'phone' => $validated['phone'],
            'notes_optional' => $validated['notes_optional'] ?? null,
            'status' => 'Scheduled',
            'created_by' => $userId,
        ]);

        return redirect()->back()->with('success', 'Appointment added successfully!');
    }

    public function completeDischarge(Request $request)
    {
        $appointment = Appointment::where('code', $request->appointment_code)->firstOrFail();

        $appointment->payment_status = 'paid';
        $appointment->save();

        return response()->json(['success' => true]);
    }

    public function discharge(Request $request)
    {
        $appointment = Appointment::find($request->appointment_id);
        if(!$appointment) return response()->json(['error' => 'Appointment not found'], 404);

        $appointment->status = 'Discharged';
        $appointment->updated_by = auth()->id(); // Add updated_by
        $appointment->save();

        // Get authenticated user ID
        $userId = auth()->id();

        Payment::create([
            'appointment_id' => $appointment->id,
            'services' => $request->services_json,
            'paid_amount' => $request->paid_amount,
            'discount' => $request->discount_amount,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks,
            'created_by' => $userId, // Add created_by
        ]);

        return response()->json([
            'success' => true,
            'paid_amount' => number_format($request->paid_amount),
            'payment_method' => $request->payment_method,
            'time' => now()->format('H:i')
        ]);
    }
}