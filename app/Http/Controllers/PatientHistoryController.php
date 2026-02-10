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
        // Default values
        $dateType = $request->get('date_type', 'all');
        $selectedDate = $request->get('selected_date', today()->format('Y-m-d'));

        // Simple query without any subqueries
        $query = Patient::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhere('national_id_passport', 'like', "%{$search}%");
            });
        }

        // Filter by doctor if selected
        if ($request->filled('doctor_id')) {
            $query->where('assigned_doctor', $request->doctor_id);
        }

        // DATE FILTERING LOGIC - Only show patients with appointments on selected date
        if ($dateType != 'all') {
            $query->whereExists(function($q) use ($dateType, $selectedDate) {
                $q->select(DB::raw(1))
                  ->from('appointments')
                  ->whereColumn('appointments.patient_id', 'patients.id')
                  ->orWhereColumn('appointments.patient_name', 'patients.full_name');

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

        // Get patients with pagination - FIXED to 12 per page
        $patients = $query->orderBy('full_name')->paginate(12);

        // Get doctors and services
        $doctors = Doctor::where('status', 'active')->orderBy('full_name')->get();
        $services = Service::orderBy('service_name')->get();

        // For each patient, calculate statistics
        foreach ($patients as $patient) {
            // Get appointments for this patient
            $appointments = $this->getPatientAppointments($patient);

            // Calculate statistics - only count visits
            $patient->total_visits = $appointments->count();

            // Remove amount calculations since columns don't exist
            $patient->total_amount = 0;
            $patient->total_paid = 0;
            $patient->outstanding_balance = 0;

            // Get last visit
            if ($appointments->count() > 0) {
                $lastAppointment = $appointments->sortByDesc('created_at')->first();
                $patient->last_visit = $lastAppointment->created_at
                    ? Carbon::parse($lastAppointment->created_at)->format('M d, Y')
                    : 'Recently';
            } else {
                $patient->last_visit = 'No visits yet';
            }

            // Get filtered appointments based on date type
            $patient->filteredAppointments = $this->getFilteredAppointments($patient, $dateType, $selectedDate);
        }

        return view('patient-history.index', compact(
            'patients',
            'doctors',
            'services',
            'selectedDate',
            'dateType'
        ));
    }

    public function show(Patient $patient)
{
    // Load patient with doctor relationship
    $patient->load('doctor');

    // Get appointment history
    $appointments = Appointment::where('patient_id', $patient->id)
        ->orWhere('patient_name', $patient->full_name)
        ->with(['doctor', 'service'])
        ->orderBy('appointment_date', 'desc')
        ->get();

    // Calculate total visits
    $patient->total_visits = $appointments->count();

    // Get last visit date
    if ($appointments->count() > 0) {
        $lastVisit = $appointments->sortByDesc('appointment_date')->first();
        $patient->last_visit = \Carbon\Carbon::parse($lastVisit->appointment_date)->format('M j, Y');
    }

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
            'patient_name' => $patient->full_name,
            'doctor_id' => $request->doctor_id,
            'service' => $request->service,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ];

        // Add date if provided
        if ($request->filled('date')) {
            $appointmentData['date'] = $request->date;
        }

        // Add time if provided
        if ($request->filled('time')) {
            $appointmentData['time'] = $request->time;
        }

        Appointment::create($appointmentData);

        return redirect()->route('patient-history.show', $patient)
            ->with('success', 'Appointment created successfully.');
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled,no-show,rescheduled',
        ]);

        $appointment->update([
            'status' => $request->status,
        ]);

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
            'full_name',
            'phone_number',
            'address',
            'known_medical_conditions',
            'allergies',
            'blood_type',
            'assigned_doctor',
        ]));

        return redirect()->back()
            ->with('success', 'Patient information updated successfully.');
    }

    public function downloadPatientReport(Patient $patient)
    {
        // Get appointments for this patient
        $appointments = Appointment::where(function($q) use ($patient) {
            $q->where('patient_id', $patient->id)
              ->orWhere('patient_name', $patient->full_name);
        })
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

    // Helper method to get appointments by patient
    private function getPatientAppointments($patient)
    {
        return Appointment::where(function($q) use ($patient) {
            $q->where('patient_id', $patient->id)
              ->orWhere('patient_name', $patient->full_name);
        })->get();
    }

    // Helper method to get filtered appointments
    private function getFilteredAppointments($patient, $dateType, $selectedDate)
    {
        $query = Appointment::where(function($q) use ($patient) {
            $q->where('patient_id', $patient->id)
              ->orWhere('patient_name', $patient->full_name);
        });

        // Apply date filtering based on created_at
        if ($dateType == 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateType == 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateType == 'specific') {
            $query->whereDate('created_at', $selectedDate);
        } elseif ($dateType == 'week') {
            $query->whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]);
        } elseif ($dateType == 'month') {
            $query->whereMonth('created_at', Carbon::now()->month);
        } elseif ($dateType == 'custom') {
            $query->whereDate('created_at', $selectedDate);
        }
        // For 'all', no date filter

        return $query->with('doctor')->orderBy('created_at', 'desc')->get();
    }
}