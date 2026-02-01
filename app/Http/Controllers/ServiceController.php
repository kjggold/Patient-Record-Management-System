<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display the list of services.
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();

        return view('services', compact('services'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255|unique:services,service_name',
            'service_fee' => 'required|string|max:255|unique:services,service_fee',
            'description' => 'required|string|max:1000',
        ]);

        Service::create($validated);

        return redirect()
            ->route('services')
            ->with('success', 'Service added successfully.');
    }
}
