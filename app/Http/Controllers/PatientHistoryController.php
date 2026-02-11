<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PatientHistoryController extends Controller
{
    public function index(Request $request)
    {
        $dateType = $request->get('date_type', 'all');
        $selectedDate = $request->get('selected_date', today()->format('Y-m-d'));

        $query = Patient::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('national_id_passport', 'like', "%{$search}%");
            });
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('assigned_doctor', $request->doctor_id);
        }

        // Date filtering (appointments table)
        if ($dateType != 'all') {
            $query->whereExists(function($q) use ($dateType, $selectedDate) {
                $q->select(DB::raw(1))
                  ->from('appointments')
                  ->whereColumn('appointments.patient_id', 'patients.id');

                if ($dateType == 'today') {
                    $q->whereDate('appointments.created_at', today());
                } elseif ($dateType == 'yesterday') {
                    $q->whereDate('appointments.created_at', today()->subDay());
                } elseif ($dateType == 'week') {
                    $q->whereBetween('appointments.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType == 'month') {
                    $q->whereMonth('appointments.created_at', now()->month);
                } elseif ($dateType == 'custom') {
                    $q->whereDate('appointments.created_at', $selectedDate);
                }
            });
        }

        $patients = $query->orderBy('full_name')->paginate(12);
        $doctors = Doctor::where('status', 'active')->orderBy('full_name')->get();
        $services = Service::orderBy('service_name')->get();

        foreach ($patients as $patient) {
            $appointments = $this->getPatientAppointments($patient);

            $patient->total_visits = $appointments->count();
            $patient->total_amount = 0;
            $patient->total_paid = 0;
            $patient->outstanding_balance = 0;

            $patient->last_visit = $appointments->count()
                ? Carbon::parse($appointments->sortByDesc('created_at')->first()->created_at)->format('M d, Y')
                : 'No visits yet';

            $patient->filteredAppointments = $this->getFilteredAppointments($patient, $dateType, $selectedDate);
        }

        return view('patient-history.index', compact(
            'patients', 'doctors', 'services', 'selectedDate', 'dateType'
        ));
    }

    public function show(Patient $patient)
    {
        $patient->load('doctor');

        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get();

        $patient->total_visits = $appointments->count();
        $patient->last_visit = $appointments->count()
            ? Carbon::parse($appointments->sortByDesc('appointment_date')->first()->appointment_date)->format('M j, Y')
            : 'No visits yet';

        return view('patient-history.show', compact('patient', 'appointments'));
    }

    public function createAppointment(Request $request, Patient $patient)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'service' => 'required|string|max:255',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $appointmentData = [
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'service' => $request->service,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];

        if ($request->filled('date')) $appointmentData['date'] = $request->date;
        if ($request->filled('time')) $appointmentData['time'] = $request->time;

        Appointment::create($appointmentData);

        return redirect()->route('patient-history.show', $patient)
            ->with('success', 'Appointment created successfully.');
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no-show,rescheduled',
        ]);

        $appointment->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated.',
            'status' => $appointment->status,
        ]);
    }

    public function updatePatientInfo(Request $request, Patient $patient)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string',
            'known_medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'assigned_doctor' => 'nullable|exists:doctors,id',
        ]);

        $patient->update($request->only([
            'full_name','phone_number','address','known_medical_conditions','allergies','blood_type','assigned_doctor'
        ]));

        return redirect()->back()->with('success', 'Patient information updated successfully.');
    }

    public function downloadPatientReport(Patient $patient)
    {
        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'patient' => $patient,
            'appointments' => $appointments,
            'generated_at' => now()->format('F j, Y H:i:s'),
        ];

        return view('patient-history.report', $data);
    }

    // Private helpers
    private function getPatientAppointments($patient)
    {
        return Appointment::with(['doctor', 'service'])
            ->where('patient_id', $patient->id)
            ->get();
    }

    private function getFilteredAppointments($patient, $dateType, $selectedDate)
    {
        $query = Appointment::with('doctor')->where('patient_id', $patient->id);

        if ($dateType == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateType == 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateType == 'specific' || $dateType == 'custom') {
            $query->whereDate('created_at', $selectedDate);
        } elseif ($dateType == 'week') {
            $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($dateType == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }
}
