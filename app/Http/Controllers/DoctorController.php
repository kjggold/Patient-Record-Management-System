<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // Get search query from request
        $search = $request->input('search');

        // Check if it's an AJAX request for live search
        if ($request->ajax()) {
            return $this->searchDoctors($request);
        }

        // Start query
        $query = Doctor::query()->orderBy('id', 'desc');

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', $searchTerm)
                  ->orWhere('speciality', 'LIKE', $searchTerm)
                  ->orWhere('phone_number', 'LIKE', $searchTerm)
                  ->orWhere('email', 'LIKE', $searchTerm);
            });
        }

        // Get paginated results
        $doctors = $query->paginate(10);

        // Append search parameter to pagination links if search exists
        if (!empty($search)) {
            $doctors->appends(['search' => $search]);
        }

        return view('doctors', compact('doctors', 'search'));
    }

    /**
     * Search doctors via AJAX for live search
     */
    public function searchDoctors(Request $request)
    {
        $search = $request->input('search');

        $query = Doctor::query()->orderBy('id', 'desc');

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('full_name', 'LIKE', $searchTerm)
                  ->orWhere('speciality', 'LIKE', $searchTerm)
                  ->orWhere('phone_number', 'LIKE', $searchTerm)
                  ->orWhere('email', 'LIKE', $searchTerm);
            });
        }

        $doctors = $query->paginate(10);

        // Return JSON response with the table HTML and updated info
        return response()->json([
            'html' => view('doctors.partials.doctor-table', [
                'doctors' => $doctors,
                'search' => $search
            ])->render(),
            'count' => $doctors->total(),
            'from' => $doctors->firstItem(),
            'to' => $doctors->lastItem(),
            'search_term' => $search
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:doctors,phone_number',
            'email' => 'required|email|unique:doctors,email|max:255',
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
            'full_name' => 'required|string|max:255',
            'speciality' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:doctors,phone_number,' . $doctor->id,
            'email' => 'required|email|max:255|unique:doctors,email,' . $doctor->id,
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