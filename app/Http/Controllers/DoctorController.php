<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor; // Make sure this model exists

class DoctorController extends Controller
{
    // Show all doctors and the form modal
    public function index()
    {
        $doctors = Doctor::all(); // fetch all doctors
        return view('doctors.index', compact('doctors'));
    }
    public function create()
    {
        return view('doctors.create');
    }

    // Store new doctor
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'full_name' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'consultation_fee' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:Active,On Leave',
        ]);

        // Create new doctor
        Doctor::create([
            'full_name' => $request->full_name,
            'speciality' => $request->speciality,
            'department' => $request->department,
            'experience' => $request->experience,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'consultation_fee' => $request->consultation_fee,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Doctor added successfully!');
    }
}
