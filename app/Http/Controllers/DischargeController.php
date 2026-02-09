<?php

namespace App\Http\Controllers;

use App\Models\Discharge;
use Illuminate\Http\Request;

class DischargeController extends Controller
{
    public function index()
    {
        $discharges = Discharge::with('appointment.patient', 'appointment.doctor')->get();
        return view('discharge', compact('discharges'));
    }

    // Optional: fetch discharged appointments IDs for JS usage
    public function completedIds()
    {
        return Discharge::pluck('appointment_id');
    }

    // Optional: for AJAX fetch
    public function fetchAll()
    {
        $discharges = Discharge::with('appointment.patient', 'appointment.doctor')->get();
        return response()->json($discharges);
    }
}
