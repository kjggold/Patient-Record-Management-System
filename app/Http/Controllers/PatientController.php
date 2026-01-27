<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    //
    public function index()
    {
        return view('auth.addPatient');
    }

    public function addPatient(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:30',
            'national_id_passport' => 'required|string|max:20',
            'age' => 'required|iteger|max:3',
            // 'sex_gender' => 'required|string|email|max:255|unique:users',
            // 'date_of_birth_day' => 'required|string|min:6|confirmed',
            // 'date_of_birth_month' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'address' => 'required|string|max:100',
            'known_medical_conditions' => 'required|string|max:100',
            // 'allergies' => 'required|string|email|max:255|unique:users',
            // 'blood_type' => 'required|string|min:6|confirmed',
            // 'alcohol_consumption' => 'required|string|max:255',
            //'assigned_doctor' => 'required|string|email|max:255|unique:users',
            //'registration_date' => 'required|string|min:6|confirmed',
        ]);

        $patient = Patient::create([
            'full_name' => $request->full_name,
            'national_id_passport' => $request->natinal_id_passport,
            'age' => $request->age,
            'sex_gender' => $request->sex_gender,
            'date_of_birth_day' => $request->date_of_birth_day,
            // 'date_of_birth_month' => $request->date_of_birth_month,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'known_medical_conditions' => $request->known_medical_conditions,
            'allergies' => $request->allergies,
            'blood_type' => $request->blood_type,
            'alcohol_consumption' => $request->alcohol_consumption,
            'assigned_doctor' => $request->assigned_doctor,
            'registration_date' => $request->registration_date,
        ]);

        // Auth::login($user);

        return redirect('/patient');
    }
}
