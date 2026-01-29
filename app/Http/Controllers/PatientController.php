<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Show list of patients
    public function index()
    {
        $patients = []; // replace with actual query
        $doctors = []; // replace with actual query
        return view('patients', compact('patients', 'doctors'));
    }

    // Store new patient
    public function store(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'national_id_passport' => 'required|string|max:50',
        'age' => 'required|integer|min:0|max:120',
        'date_of_birth_day' => 'required|integer|min:1|max:31',
        'date_of_birth_month' => 'required|integer|min:1|max:12',
        'date_of_birth_year' => 'required|integer|min:1900|max:2026',
        'sex_gender' => 'required|string',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'known_medical_conditions' => 'nullable|string',
        'allergies' => 'nullable|string',
        'blood_type' => 'nullable|string',
        'alcohol_consumption' => 'nullable|string',
        'assigned_doctor' => 'required|exists:doctors,id',
        'registration_date' => 'required|date',
    ]);

    // Save patient
    Patient::create([
        'full_name' => $validated['full_name'],
        'national_id_passport' => $validated['national_id_passport'],
        'age' => $validated['age'],
        'date_of_birth' => $validated['date_of_birth_year'].'-'.$validated['date_of_birth_month'].'-'.$validated['date_of_birth_day'],
        'sex_gender' => $validated['sex_gender'],
        'phone_number' => $validated['phone_number'],
        'address' => $validated['address'],
        'known_medical_conditions' => $validated['known_medical_conditions'],
        'allergies' => $validated['allergies'],
        'blood_type' => $validated['blood_type'],
        'alcohol_consumption' => $validated['alcohol_consumption'],
        'assigned_doctor' => $validated['assigned_doctor'],
        'registration_date' => $validated['registration_date'],
    ]);

    return redirect()->route('patients.index')->with('success', 'Patient registered successfully.');
}
}
