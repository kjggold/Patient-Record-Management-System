<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
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

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);

        return view('doctorsEdit', compact('doctor'));
    }

    /**
     * Update the specified doctor in storage.
     */
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'full_name' => 'required|string|max:100',
            'speciality' => 'required|string|max:100',
            'experience' => 'nullable|integer|min:0|max:999',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone_number' => 'required|string|max:100',
            'consultation_fee' => 'required|integer|min:0|max:9999999999',
            'status' => 'required|string|in:Active,Inactive,On Leave|max:20',
        ]);

        // Update doctor
        $doctor->update($validated);

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}