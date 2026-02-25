<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        // Get search query from request
        $search = $request->input('search');

        // Check if it's an AJAX request for live search
        if ($request->ajax()) {
            return $this->searchServices($request);
        }

        // Start query builder - Order by ID ascending
        $query = Service::query()->orderBy('id', 'desc');

        // Apply search filter if search term exists
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where('service_name', 'like', $searchTerm);
        }

        // Get paginated results
        $services = $query->paginate(10);

        // Append search parameter to pagination links if search exists
        if (!empty($search)) {
            $services->appends(['search' => $search]);
        }

        return view('services', compact('services', 'search'));
    }

    /**
     * Search services via AJAX for live search
     */
    public function searchServices(Request $request)
    {
        $search = $request->input('search');

        $query = Service::query()->orderBy('id', 'asc');

        // Apply search filter
        if (!empty($search)) {
            $searchTerm = '%' . trim($search) . '%';
            $query->where('service_name', 'like', $searchTerm);
        }

        $services = $query->paginate(10);

        // Return JSON response with the table HTML and updated info
        return response()->json([
            'html' => view('services.partials.service-table', [
                'services' => $services,
                'search' => $search
            ])->render(),
            'count' => $services->total(),
            'from' => $services->firstItem(),
            'to' => $services->lastItem(),
            'search_term' => $search
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_fee' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service added successfully!');
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_fee' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'Service updated successfully.');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Service deleted successfully.');
    }
}