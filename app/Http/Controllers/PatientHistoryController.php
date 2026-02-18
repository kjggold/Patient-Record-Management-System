<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientHistoryController extends Controller
{
    // List patients with search & date filters
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateType = $request->input('date_type', 'all');
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        $query = Patient::query()->orderBy('id', 'desc');

        // Apply date filter - Only show patients who have appointments on selected date
        if ($dateType != 'all') {
            $query->whereExists(function ($subQuery) use ($dateType, $selectedDate) {
                $subQuery->select(DB::raw(1))
                    ->from('appointments')
                    ->whereColumn('appointments.patient_id', 'patients.id');

                if ($dateType == 'today') {
                    $subQuery->whereDate('appointments.appointment_date', today());
                } elseif ($dateType == 'yesterday') {
                    $subQuery->whereDate('appointments.appointment_date', today()->subDay());
                } elseif ($dateType == 'week') {
                    $subQuery->whereBetween('appointments.appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType == 'month') {
                    $subQuery->whereMonth('appointments.appointment_date', now()->month);
                } elseif ($dateType == 'custom') {
                    $subQuery->whereDate('appointments.appointment_date', $selectedDate);
                }
            });
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%')
                  ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        $patients = $query->paginate(12);

        return view('patient-history.index', compact('patients', 'search', 'dateType', 'selectedDate'));
    }

    // Show patient details with full history (appointments + discharges)
    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        // -------------------------------
        // Get appointments
        // -------------------------------
        $appointments = Appointment::with(['doctor', 'service'])
            ->where('patient_id', $id)
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'date' => $appointment->appointment_date,
                    'doctor_name' => $appointment->doctor->full_name ?? 'N/A',
                    'service_name' => $appointment->service->service_name ?? 'N/A',
                    'status' => 'Pending', // default pending for normal appointments
                    'type' => 'appointment',
                ];
            });

        // -------------------------------
        // Get discharge records
        // -------------------------------
        $discharges = DB::table('discharges')
            ->where('patient_name', $patient->full_name)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($discharge) {
                return [
                    'date' => $discharge->created_at,
                    'doctor_name' => $discharge->doctor_name ?? 'N/A',
                    'service_name' => $discharge->services ?? 'Discharge Services',
                    'status' => 'Discharged', // mark as discharged
                    'type' => 'discharge',
                ];
            });

        // -------------------------------
        // Merge and sort history by date descending
        // -------------------------------
        $allHistory = $appointments->concat($discharges)
            ->sortByDesc('date')
            ->values();

        $totalVisits = $allHistory->count();

        return view('patient-history.show', compact('patient', 'allHistory', 'totalVisits'));
    }
}
