<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $doctors=Doctor::all();

        $query = Doctor::query();
    
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('full_name', 'LIKE', "%{$search}%");
        }
        
        $doctors = $query->paginate(10);
        return view('doctors',compact('doctors'));
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
        return redirect('doctors')->with('success', 'Doctor added successfully!');
    }
}