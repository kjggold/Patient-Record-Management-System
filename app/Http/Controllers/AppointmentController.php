<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Discharge;
use App\Models\DischargeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the appointments.
     */
    public function index()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc')
            ->get();

        $patients = Patient::all();
        $doctors  = Doctor::all();
        $services = Service::all();

        return view('appointments', compact(
            'appointments',
            'patients',
            'doctors',
            'services'
        ));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id'       => 'required|exists:patients,id',
            'doctor_id'        => 'required|exists:doctors,id',
            'service_id'       => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'phone'            => 'required'
        ]);

        Appointment::create([
            'patient_id'       => $request->patient_id,
            'doctor_id'        => $request->doctor_id,
            'service_id'       => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'phone'            => $request->phone,
        ]);

        return redirect()->back();
    }

    /**
     * ===============================
     * DISCHARGE (your missing logic)
     * ===============================
     */
    public function completeDischarge(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'services'       => 'required|array|min:1',
            'discount'       => 'nullable|numeric',
            'paid'           => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {

            $total = collect($request->services)->sum(function ($s) {
                return (float) ($s['price'] ?? 0);
            });

            $discharge = Discharge::create([
                'appointment_id' => $request->appointment_id,
                'total'          => $total,
                'discount'       => $request->discount ?? 0,
                'paid'           => $request->paid ?? 0,
            ]);

            foreach ($request->services as $row) {

                $service = Service::where(
                    'service_name',
                    $row['name'] ?? ''
                )->first();

                DischargeService::create([
                    'discharge_id' => $discharge->id,
                    'service_id'   => $service?->id,
                    'service_name' => $row['name'] ?? null,
                    'price'        => $row['price'] ?? 0,
                ]);
            }

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
