<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $doctors = Doctor::all();
        
        $query = Patient::with('doctor');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('full_name', 'LIKE', "%{$search}%");
        }
        
        $patients = $query->paginate(10);

        return view('patients', compact('doctors', 'patients'));
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
            'assigned_doctor' => 'required|exists:doctors,id',
            'registration_date' => 'required|date',
        ]);

        // Get authenticated user ID
        $userId = auth()->id();

        $patient = Patient::create([
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
            'assigned_doctor' => $validated['assigned_doctor'],
            'registration_date' => $validated['registration_date'],
            'created_by' => $userId,
        ]);

        return redirect()->back()->with('success', 'Patient registered successfully!');
    }

    public function edit(Patient $patient)
    {
        $doctors = Doctor::all();
        return view('patientsEdit', compact('patient', 'doctors'));
    }

    public function update(Request $request, Patient $patient)
    {
        // Fixed validation - remove non-existent fields
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'age' => 'required|integer|min:0|max:120',
            'sex_gender' => 'required|string|in:male,female',
            'date_of_birth' => 'required|date',
            'phone_number' => 'required|string|max:20|unique:patients,phone_number,' . $patient->id,
            'address' => 'required|string|max:255',
            'known_medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string|max:255',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-,unknown',
            'alcohol_consumption' => 'required|string|in:none,occasional,regular',
            'assigned_doctor' => 'required|exists:doctors,id',
            'registration_date' => 'required|date',
        ]);

        // Add updated_by
        $validated['updated_by'] = auth()->id();

        // Update patient
        $patient->update($validated);
        
        return redirect()->route('patients.index')
            ->with('success', 'Patient updated successfully.');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect('patients')->with('success', 'Patient deleted successfully.');
    }
}