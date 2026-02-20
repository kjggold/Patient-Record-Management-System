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
        // Get search query from request
        $search = $request->input('search');
        $dateType = $request->input('date_type', 'all');
        $selectedDate = $request->input('selected_date', date('Y-m-d'));

        // Check if it's an AJAX request for live search
        if ($request->ajax()) {
            return $this->searchPatients($request);
        }

        // Start query
        $query = Patient::query()->orderBy('id', 'desc');

        // Apply search filter - ONLY by name and ID
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';

            $query->where(function($q) use ($searchTerm) {
                // Search only by ID or name
                $q->where('id', 'LIKE', $searchTerm)
                  ->orWhere('full_name', 'LIKE', $searchTerm);
            });
        }

        // Apply date filter
        if ($dateType != 'all') {
            $query->whereExists(function($query) use ($dateType, $selectedDate) {
                $query->select(DB::raw(1))
                      ->from('appointments')
                      ->whereColumn('appointments.patient_id', 'patients.id');

                if ($dateType == 'today') {
                    $query->whereDate('appointments.appointment_date', today());
                } elseif ($dateType == 'yesterday') {
                    $query->whereDate('appointments.appointment_date', today()->subDay());
                } elseif ($dateType == 'week') {
                    $query->whereBetween('appointments.appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($dateType == 'month') {
                    $query->whereMonth('appointments.appointment_date', now()->month);
                } elseif ($dateType == 'custom') {
                    $query->whereDate('appointments.appointment_date', $selectedDate);
                }
            });
        }

        // Get paginated results
        $patients = $query->paginate(10);

        // Append search parameter to pagination links if search exists
        if (!empty($search)) {
            $patients->appends(['search' => $search]);
        }

        // Append date filters to pagination links
        if ($dateType != 'all') {
            $patients->appends(['date_type' => $dateType]);
            if ($dateType == 'custom') {
                $patients->appends(['selected_date' => $selectedDate]);
            }
        }

        return view('patient-history.index', compact('patients', 'search', 'dateType', 'selectedDate'));
    }

    /**
     * Search patients via AJAX for live search
     */

    public function searchPatients(Request $request)
{
    $search = $request->input('search');
    $dateType = $request->input('date_type', 'all');
    $selectedDate = $request->input('selected_date', date('Y-m-d'));

    $query = Patient::query()->orderBy('id', 'desc');

    // Apply search filter - ONLY by name and ID
    if (!empty($search)) {
        $searchTerm = '%' . trim($search) . '%';
        $query->where(function($q) use ($searchTerm) {
            $q->where('id', 'LIKE', $searchTerm)
              ->orWhere('full_name', 'LIKE', $searchTerm);
        });
    }

    // Apply date filter (only show patients with appointments on selected date)
    if ($dateType != 'all') {
        $query->whereExists(function($query) use ($dateType, $selectedDate) {
            $query->select(DB::raw(1))
                  ->from('appointments')
                  ->whereColumn('appointments.patient_id', 'patients.id');

            if ($dateType == 'today') {
                $query->whereDate('appointments.appointment_date', today());
            } elseif ($dateType == 'yesterday') {
                $query->whereDate('appointments.appointment_date', today()->subDay());
            } elseif ($dateType == 'week') {
                $query->whereBetween('appointments.appointment_date', [now()->startOfWeek(), now()->endOfWeek()]);
            } elseif ($dateType == 'month') {
                $query->whereMonth('appointments.appointment_date', now()->month);
            } elseif ($dateType == 'custom') {
                $query->whereDate('appointments.appointment_date', $selectedDate);
            }
        });
    }

    $patients = $query->paginate(10);

    // Return JSON response with the table HTML and updated info
    return response()->json([
        'html' => view('patient-history.patient-table', [
            'patients' => $patients,
            'dateType' => $dateType,
            'selectedDate' => $selectedDate,
            'search' => $search
        ])->render(),
        'count' => $patients->total(),
        'from' => $patients->firstItem(),
        'to' => $patients->lastItem(),
        'search_term' => $search
    ]);
}

    /**
     * Helper method to handle gender variations in search
     */
    private function getGenderMappings($searchTerm)
    {
        $searchLower = strtolower(trim($searchTerm, '%'));
        $mappings = [];

        $genderMap = [
            'male' => ['male', 'm', 'man', 'boy', 'gentleman', 'mr'],
            'female' => ['female', 'f', 'woman', 'girl', 'lady', 'mrs', 'ms', 'miss'],
        ];

        foreach ($genderMap as $gender => $variations) {
            foreach ($variations as $variation) {
                if (strpos($searchLower, $variation) !== false || $variation === $searchLower) {
                    $mappings[] = '%' . $gender . '%';
                    break;
                }
            }
        }

        return $mappings;
    }

    public function show($id)
    {
        $patient = Patient::findOrFail($id);

        // Get regular appointments with relationships
        $appointments = Appointment::where('patient_id', $id)
            ->with(['doctor', 'service'])
            ->orderBy('appointment_date', 'desc')
            ->get()
            ->map(function($appointment) {
                // Get the main service name from the appointment's service relationship
                $mainServiceName = 'N/A';
                if ($appointment->service) {
                    $mainServiceName = $appointment->service->service_name ??
                                      $appointment->service->name ??
                                      'Service #' . $appointment->service_id;
                }

                return [
                    'date' => $appointment->appointment_date,
                    'doctor_name' => $appointment->doctor->full_name ?? 'N/A',
                    'service_name' => $mainServiceName,
                    'status' => $appointment->status,
                    'type' => 'appointment'
                ];
            });

        // Get discharge records
        $discharges = DB::table('discharges')
            ->where('patient_name', $patient->full_name)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($discharge) {
                // Extract the main service name from the services JSON
                $mainServiceName = 'Discharge';

                // Try to get service_name field first
                if (isset($discharge->service_name) && !empty($discharge->service_name) && $discharge->service_name !== 'Unknown') {
                    $mainServiceName = $discharge->service_name;
                }
                // Otherwise try to decode the services JSON
                else if (isset($discharge->services)) {
                    $services = json_decode($discharge->services, true);

                    if (is_array($services) && !empty($services)) {
                        // Get the first service as the main service
                        $firstService = $services[0];

                        if (is_array($firstService)) {
                            $mainServiceName = $firstService['name'] ?? 'Service';
                        } elseif (is_string($firstService)) {
                            $mainServiceName = $firstService;
                        }
                    }
                }

                // If we still don't have a name, use a default
                if ($mainServiceName === 'Discharge' || empty($mainServiceName)) {
                    $mainServiceName = 'Medical Service';
                }

                return [
                    'date' => $discharge->created_at,
                    'doctor_name' => $discharge->doctor_name ?? 'N/A',
                    'service_name' => $mainServiceName,
                    'status' => 'discharged',
                    'type' => 'discharge'
                ];
            });

        // Merge and sort by date
        $allHistory = $appointments->concat($discharges)
            ->sortByDesc('date')
            ->values();

        $totalVisits = $allHistory->count();

        return view('patient-history.show', compact('patient', 'allHistory', 'totalVisits'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patient-history.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'sex_gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20|unique:patients,phone_number,' . $patient->id,
            'address' => 'required|string|max:255',
            'known_medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-,unknown',
            'alcohol_consumption' => 'required|string|in:none,occasional,regular',
        ]);

        // Add updated_by
        $validated['updated_by'] = auth()->id();

        // Update patient
        $patient->update($validated);

        return redirect()->route('patient-history.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:120',
            'sex_gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|unique:patients,phone_number',
            'address' => 'required|string',
            'known_medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',
            'blood_type' => 'nullable|string',
            'alcohol_consumption' => 'required|string|in:none,occasional,regular',
            'registration_date' => 'required|date',
        ]);

        // Get authenticated user ID
        $userId = auth()->id();

        Patient::create([
            'full_name' => $validated['full_name'],
            'age' => $validated['age'],
            'sex_gender' => $validated['sex_gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'known_medical_conditions' => $validated['known_medical_conditions'] ?? 'None',
            'allergies' => $validated['allergies'] ?? 'None',
            'blood_type' => $validated['blood_type'] ?? 'Unknown',
            'alcohol_consumption' => $validated['alcohol_consumption'],
            'registration_date' => $validated['registration_date'],
            'created_by' => $userId,
            'updated_by' => null,
        ]);

        return redirect()->route('patient-history.index')
            ->with('success', 'Patient registered successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect('patients')->with('success', 'Patient deleted successfully.');
    }
}