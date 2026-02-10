<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Discharge;
use Illuminate\Http\Request;

class DischargeController extends Controller
{
    // Show all discharges
    public function index()
    {
        $discharges = Discharge::latest()->get(); // no relation needed, patient/doctor name stored as text
        return view('discharge', compact('discharges'));
    }

    // Store discharge (AJAX)
    public function store(Request $request)
    {
        $data = $request->json()->all();

        // Validate required fields
        if (!isset($data['appointment_id'], $data['services'], $data['paid'], $data['balance'])) {
            return response()->json(['success' => false, 'message' => 'Missing required fields']);
        }

        DB::beginTransaction();
        try {
            $appointment = Appointment::with(['patient', 'doctor', 'service'])->find($data['appointment_id']);
            if (!$appointment) {
                return response()->json(['success' => false, 'message' => 'Appointment not found']);
            }

            // Calculate total if not provided
            $total = array_sum($data['price'] ?? []);

            $discharge = Discharge::create([
                'appointment_id' => $appointment->id,
                'patient_name'   => $appointment->patient->full_name ?? 'Unknown',
                'doctor_name'    => $appointment->doctor->full_name ?? 'Unknown',
                'services'       => json_encode($data['services']),
                'price'          => json_encode($data['price']),
                'total'          => $total,
                'discount'       => $data['discount'] ?? 0,
                'paid'           => $data['paid'],
                'balance'        => $data['balance'],
                'comment'        => $data['comment'] ?? null,
            ]);

            // Delete appointment after discharge
            $appointment->delete();

            DB::commit();
            return response()->json(['success' => true, 'discharge' => $discharge]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
