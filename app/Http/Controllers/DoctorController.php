<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        return view('auth.addDoctor');
    }

    public function addDoctor(Request $request)
    {
        // $request->validate([
        //     'full_name' => 'required|string|max:20',
        //     'speciality' => 'required|string|max:100',
        //     'experience_years' => 'required|string|max:3',
        //     'phone_number' => 'required|string|max:100',
        //     'email' => 'required|string|max:100',
        //     'consultation_fee' => 'required|string|max:10',
        //     'status' => 'required|string|max:20',
        // ]);

        Doctor::create([
            'full_name' => $request->full_name,
            'speciality' => $request->speciality,
            'experience_years' => $request->experience_years,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'consultation_fee' => $request->consultation_fee,
            'status' => $request->status,
            ]);

        // Auth::login($user);

        return redirect('/add_doctor');
    }
}
