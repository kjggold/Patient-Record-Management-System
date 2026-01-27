<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor; // ADD THIS IMPORT

class DoctorController extends Controller
{
    public function index()
    {
        return view('auth.addDoctor');
    }

    // Change method name to 'store' to match route
    public function store(Request $request)
    {
        // Uncomment and use validation
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'speciality' => 'required|string|max:100',
            'experience' => 'nullable|integer|min:0|max:999',
            'phone_number' => 'required|string|max:100',
            'email' => 'required|email|unique:doctors,email|max:100',
            'consultation_fee' => 'required|integer|min:0|max:9999999999',
            'status' => 'required|string|in:Active,Inactive,On Leave|max:20',
        ]);

        // Create doctor with validated data
        Doctor::create($validated);

        // Redirect back with success message
        return redirect('/add_doctor')->with('success', 'Doctor added successfully!');
    }
}