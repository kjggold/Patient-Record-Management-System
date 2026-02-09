<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Discharge;
use Illuminate\Http\Request;

class DischargeController extends Controller
{
    public function index()
    {
        $discharges = Discharge::latest()->get();
        return view('discharge', compact('discharges'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_id' => 'required|exists:appointments,id',
                'services' => 'required|array',
                'services.*.name' => 'required|string',
                'services.*.price' => 'required|numeric',
                'total' => 'required|numeric',
                'discount' => 'nullable|numeric',
                'paid' => 'nullable|numeric',
            ]);

            // Get appointment info
            $appointment = Appointment::findOrFail($validated['appointment_id']);

            $discharge = Discharge::create([
                'appointment_id' => $appointment->id,
                'patient_name' => $appointment->patient->full_name ?? '',
                'doctor_name' => $appointment->doctor->full_name ?? '',
                'services' => json_encode($validated['services']), // store as JSON
                'total' => $validated['total'],
                'discount' => $validated['discount'] ?? 0,
                'paid' => $validated['paid'] ?? 0,
                'balance' => ($validated['paid'] ?? 0) - ($validated['total'] - ($validated['discount'] ?? 0)),
            ]);

            return response()->json(['success' => true, 'discharge' => $discharge]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
