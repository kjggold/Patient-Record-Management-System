<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Service;

class PatientController extends Controller
{
    //
    public function index(Request $request)
    {
        $doctors = Doctor::all();
        $patients = Patient::all();
        $services = Service::all();

        $query = Patient::query('doctor');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('full_name', 'LIKE', "%{$search}%");
        }

        $patients = $query->paginate(10);

        return view('patients', compact('doctors','patients','services'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'national_id_passport' => 'required|string|max:30',
            'age' => 'required|integer|min:0|max:120',
            'sex_gender' => 'required|string|in:male,female,other,prefer_not_to_say',
            'date_of_birth_day' => 'required|integer|min:1|max:31',
            'date_of_birth_month' => 'required|integer|min:1|max:12',
            'date_of_birth_year' => 'required|integer|min:1900|max:' . date('Y'),
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'known_medical_conditions' => 'nullable|string|max:255',
            'allergies' => 'nullable|string|max:255',
            'blood_type' => 'required|string|in:A+,A-,B+,B-,O+,O-,AB+,AB-,unknown',
            'alcohol_consumption' => 'nullable|string|in:none,occasional,regular',
            'assigned_doctor' => 'required|exists:doctors,id', // Ensure doctor exists
            'registration_date' => 'required|date',
        ]);

        Patient::create($validated);

        // Auth::login($user);

        return redirect('patients')->with('success', 'Patient added successfully!');
    }
}
