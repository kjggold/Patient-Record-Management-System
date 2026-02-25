<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    // Show appointments page
    public function index(Request $request)
    {
        // Get search query from request
        $search = $request->input('search');

        // Check if it's an AJAX request for live search
        if ($request->ajax()) {
            return $this->searchAppointments($request);
        }

        // Get all data for dropdowns
        $patients = Patient::all();
        $doctors  = Doctor::all();
        $services = Service::all();

        // Start query for appointments
        $query = Appointment::with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc');

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('patient', function($patientQuery) use ($searchTerm) {
                    $patientQuery->where('full_name', 'LIKE', $searchTerm)
                                 ->orWhere('id', 'LIKE', $searchTerm);
                })
                ->orWhereHas('doctor', function($doctorQuery) use ($searchTerm) {
                    $doctorQuery->where('full_name', 'LIKE', $searchTerm);
                })
                ->orWhereHas('service', function($serviceQuery) use ($searchTerm) {
                    $serviceQuery->where('service_name', 'LIKE', $searchTerm);
                })
                ->orWhere('id', 'LIKE', $searchTerm);
            });
        }

        // Get paginated results
        $appointments = $query->paginate(10);

        // Append search parameter to pagination links if search exists
        if (!empty($search)) {
            $appointments->appends(['search' => $search]);
        }

        return view('appointments', compact('appointments', 'patients', 'doctors', 'services', 'search'));
    }

    /**
     * Search appointments via AJAX for live search
     */
    public function searchAppointments(Request $request)
    {
        $search = $request->input('search');

        $query = Appointment::with(['patient', 'doctor', 'service'])
            ->orderBy('appointment_date', 'asc');

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('patient', function($patientQuery) use ($searchTerm) {
                    $patientQuery->where('full_name', 'LIKE', $searchTerm)
                                 ->orWhere('id', 'LIKE', $searchTerm);
                })
                ->orWhereHas('doctor', function($doctorQuery) use ($searchTerm) {
                    $doctorQuery->where('full_name', 'LIKE', $searchTerm);
                })
                ->orWhereHas('service', function($serviceQuery) use ($searchTerm) {
                    $serviceQuery->where('service_name', 'LIKE', $searchTerm);
                })
                ->orWhere('appointments.id', 'LIKE', $searchTerm);
            });
        }

        $appointments = $query->paginate(10);

        // Return JSON response with the table HTML and updated info
        return response()->json([
            'html' => view('appointments.partials.appointment-table', [
                'appointments' => $appointments,
                'search' => $search
            ])->render(),
            'count' => $appointments->total(),
            'from' => $appointments->firstItem(),
            'to' => $appointments->lastItem(),
            'search_term' => $search
        ]);
    }

    // Store new appointment (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
        ]);

                $userId = auth()->id();
        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'created_by' => $userId,
            'updated_by' => null,
        ]);

        // Load relationships
        $appointment->load(['patient', 'doctor', 'service']);

        // Return JSON for live table update
        return response()->json([
            'success' => true,
            'appointment' => [
                'id' => $appointment->id,
                'patient_name' => $appointment->patient->full_name ?? '-',
                'doctor_name' => $appointment->doctor->full_name ?? '-',
                'service_name' => $appointment->service->service_name ?? '-',
                'service_fee' => $appointment->service->service_fee ?? 0,
                'consultation_fee' => $appointment->doctor->consultation_fee ?? 0,
                'appointment_date' => $appointment->appointment_date,
            ]
        ]);
    }

    public function edit($id)
    {
        return view('appointmentsEdit',[
            'appointment' => Appointment::findOrFail($id),
            'patients' => Patient::all(),
            'doctors' => Doctor::all(),
            'services' => Service::all(),
        ]);
    }
    // Update doctor
    public function update(Request $request, Appointment $appointment)
    {
        // Validation rules
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
        ]);

        // Add updated_by
        $validated['updated_by'] = auth()->id();

        // Update doctor
        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect('appointment')->with('success', 'Appointment deleted successfully.');
    }
}
