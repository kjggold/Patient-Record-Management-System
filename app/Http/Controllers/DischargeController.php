<?php

namespace App\Http\Controllers; // <- very important

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

        Discharge::create($data);

        return response()->json(['success' => true]);
    }
}
