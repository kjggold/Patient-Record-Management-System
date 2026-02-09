<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
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
    $data = $request->json()->all();

    if (!isset($data['appointment_id'], $data['services'], $data['total'], $data['paid'], $data['balance'])) {
        return response()->json(['success' => false, 'message' => 'Missing required fields']);
    }

    DB::beginTransaction();
    try {
        $appointment = Appointment::with(['patient', 'doctor', 'service'])->find($data['appointment_id']);
        if (!$appointment) {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }

        // Save discharge (without touching appointments table)
        $discharge = Discharge::create([
            'appointment_id' => $appointment->id,
            'patient_name'   => $appointment->patient->full_name ?? 'Unknown',
            'doctor_name'    => $appointment->doctor->full_name ?? 'Unknown',
            'services'       => json_encode($data['services']),
            'total'          => $data['total'],
            'discount'       => $data['discount'] ?? 0,
            'paid'           => $data['paid'],
            'balance'        => $data['balance'],
        ]);

        DB::commit();
        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
}



}
