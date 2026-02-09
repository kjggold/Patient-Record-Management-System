<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Discharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AppointmentController extends Controller
{
    // Show appointments
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'service'])
            ->whereDoesntHave('discharge') // hide already discharged
            ->orderBy('appointment_date', 'asc')
            ->get();

        $patients = Patient::all();
        $doctors  = Doctor::all();
        $services = Service::all();

        return view('appointments', compact('appointments', 'patients', 'doctors', 'services'));
    }

    // Store new appointment
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date',
        ]);

        // Start from ID 3001 if table is empty
        $nextId = 3001;
        $last = DB::table('appointments')->max('id');
        if ($last && $last >= 3001) {
            $nextId = $last + 1;
        }

        Appointment::create([
            'id'               => $nextId,
            'patient_id'       => $request->patient_id,
            'doctor_id'        => $request->doctor_id,
            'service_id'       => $request->service_id,
            'appointment_date' => $request->appointment_date,
        ]);

        return redirect()->back()->with('success','Appointment added.');
    }

    // Complete discharge
    public function discharge(Request $request, Appointment $appointment)
    {
        DB::beginTransaction();

        try {
            // Create Discharge
            $discharge = Discharge::create([
                'appointment_id' => $appointment->id,
                'patient_name'   => $appointment->patient->full_name ?? '',
                'doctor_name'    => $appointment->doctor->full_name ?? '',
                'services'       => $request->services ?? [],
                'total'          => $request->total ?? 0,
                'discount'       => $request->discount ?? 0,
                'paid'           => $request->paid ?? 0,
                'balance'        => $request->balance ?? 0,
            ]);

            // Delete appointment after discharge
            $appointment->delete();

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
