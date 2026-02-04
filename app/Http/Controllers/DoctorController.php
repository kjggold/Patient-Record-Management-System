<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    // NO CONSTRUCTOR HERE - Remove it if you have one
    
    public function index(Request $request)
    {
        $query = Doctor::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('full_name', 'LIKE', "%{$search}%");
        }
        
        $doctors = $query->paginate(10);
        return view('doctors', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'speciality' => 'required|string|max:100',
            'phone_number' => 'required|string|max:100|unique:doctors,phone_number',
            'email' => 'required|email|unique:doctors,email|max:100',
            'status' => 'required|string|in:Active,Inactive,On Leave|max:20',
            'max_patients' => 'required|integer|min:1|max:100',
        ]);

        // Get authenticated user ID
        $userId = auth()->id();

        // Add created_by to validated data
        $validated['created_by'] = $userId;

        // Create doctor with validated data
        Doctor::create($validated);

        // Redirect back with success message
        return redirect('doctors')->with('success', 'Doctor added successfully!');
    }

    // Show edit form
    public function edit(Doctor $doctor)
    {
        return view('doctorsEdit', compact('doctor'));
    }

    // Update doctor
    public function update(Request $request, Doctor $doctor)
    {
        // Validation rules
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'speciality' => 'required|string|max:100',
            'phone_number' => 'required|string|max:100|unique:doctors,phone_number,' . $doctor->id,
            'email' => 'required|email|max:100|unique:doctors,email,' . $doctor->id,
            'status' => 'required|string|in:Active,Inactive,On Leave|max:20',
            'max_patients' => 'required|integer|min:1|max:100',
        ]);

        // Add updated_by
        $validated['updated_by'] = auth()->id();

        // Update doctor
        $doctor->update($validated);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return redirect('doctors')->with('success', 'Doctor deleted successfully.');
    }
}