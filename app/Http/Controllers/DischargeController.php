<?php

namespace App\Http\Controllers;

use App\Models\Discharge;
use Illuminate\Http\Request;

class DischargeController extends Controller
{

    public function index()
    {
        $discharges = Discharge::with('creator')->latest()->get();
        return view('discharge', compact('discharges'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'required',
            'patient_name' => 'required',
            'doctor_name' => 'required',
            'services' => 'required|array',
            'total' => 'required|numeric',
            'paid' => 'required|numeric',
            'balance' => 'required|numeric',
            'payment_method' => 'required',
        ]);

        // Get authenticated user ID
        $userId = auth()->id();

        // Add created_by to data
        $data['created_by'] = $userId;
        $data['services'] = json_encode($data['services']); // Convert array to JSON

        Discharge::create($data);

        return response()->json(['success' => true]);
    }
}