<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\Discharge;
use Illuminate\Http\Request;

class DischargeController extends Controller
{
    // Show all discharges
    public function index()
    {
        $discharges = Discharge::latest()->get();
        return view('discharge', compact('discharges'));
    }

    // Store discharge (AJAX)
    public function store(Request $request)
    {
        $data = $request->json()->all();

        // Log the incoming data for debugging
        \Log::info('Discharge data received:', $data);

        // Validate required fields
        if (!isset($data['appointment_id'], $data['paid'])) {
            return response()->json(['success' => false, 'message' => 'Missing required fields']);
        }

        DB::beginTransaction();
        try {
            // Eager load the service relationship
            $appointment = Appointment::with(['patient', 'doctor', 'service'])->find($data['appointment_id']);

            if (!$appointment) {
                return response()->json(['success' => false, 'message' => 'Appointment not found']);
            }

            // Debug: Check what service data we have
            \Log::info('Appointment service:', ['service' => $appointment->service]);

            // Get service data from the appointment
            $serviceData = null;
            if ($appointment->service) {
                $serviceData = [
                    'id' => $appointment->service->id,
                    'name' => $appointment->service->service_name ?? $appointment->service->name ?? 'Unknown Service',
                    'price' => $appointment->service->price ?? 0
                ];
            }
            // If no service relationship, try to get from the request
            elseif (isset($data['services']) && !empty($data['services'])) {
                $services = $data['services'];
                if (is_array($services) && count($services) > 0) {
                    $firstService = $services[0];
                    $serviceData = [
                        'id' => $firstService['id'] ?? null,
                        'name' => $firstService['name'] ?? 'Unknown Service',
                        'price' => $firstService['price'] ?? 0
                    ];
                }
            }

            // If still no service data, create a default based on service_id
            if (!$serviceData && $appointment->service_id) {
                $serviceData = [
                    'id' => $appointment->service_id,
                    'name' => 'Service #' . $appointment->service_id,
                    'price' => 0
                ];
            }

            // Process services from request
            $allServices = $data['services'] ?? [];
            $prices = $data['price'] ?? [];

            // Format services for JSON storage
            $formattedServices = [];
            if (is_array($allServices)) {
                foreach ($allServices as $index => $service) {
                    $formattedServices[] = [
                        'name' => is_array($service) ? ($service['name'] ?? $service) : $service,
                        'price' => $prices[$index] ?? 0
                    ];
                }
            }

            // Calculate total - use the specific service price
            $total = $serviceData['price'] ?? 0;

            // Create discharge record
            $discharge = Discharge::create([
                'appointment_id' => $appointment->id,
                'patient_name'   => $appointment->patient->full_name ?? $appointment->patient->name ?? 'Unknown Patient',
                'doctor_name'    => $appointment->doctor->full_name ?? $appointment->doctor->name ?? 'Unknown Doctor',
                'service_id'     => $serviceData['id'] ?? null,
                'service_name'   => $serviceData['name'] ?? 'Unknown Service',
                'service_price'  => $serviceData['price'] ?? 0,
                'services'       => json_encode($formattedServices),
                'total'          => $total,
                'discount'       => $data['discount'] ?? 0,
                'paid'           => $data['paid'],
                'balance'        => $data['balance'] ?? ($data['paid'] - $total),
                'comment'        => $data['comment'] ?? null,
            ]);

            // Delete appointment after discharge
            $appointment->delete();

            DB::commit();

            \Log::info('Discharge created successfully:', ['id' => $discharge->id]);

            return response()->json([
                'success' => true,
                'discharge' => $discharge,
                'message' => 'Discharge recorded successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Discharge error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}