<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class PatientHistoryController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', today()->format('Y-m-d'));
        $dateType = $request->get('date_type', 'today');

        // Get base query for patients
        $query = Patient::with(['appointments' => function($q) use ($selectedDate, $dateType) {
            if ($dateType == 'today') {
                $q->whereDate('appointment_date', Carbon::today());
            } elseif ($dateType == 'yesterday') {
                $q->whereDate('appointment_date', Carbon::yesterday());
            } elseif ($dateType == 'tomorrow') {
                $q->whereDate('appointment_date', Carbon::tomorrow());
            } elseif ($dateType == 'specific') {
                $q->whereDate('appointment_date', $selectedDate);
            } elseif ($dateType == 'week') {
                $q->whereBetween('appointment_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            } elseif ($dateType == 'month') {
                $q->whereMonth('appointment_date', Carbon::now()->month);
            } else {
                // All appointments
            }
        }, 'appointments.doctor', 'appointments.service'])
        ->whereHas('appointments', function($q) use ($selectedDate, $dateType) {
            if ($dateType == 'today') {
                $q->whereDate('appointment_date', Carbon::today());
            } elseif ($dateType == 'yesterday') {
                $q->whereDate('appointment_date', Carbon::yesterday());
            } elseif ($dateType == 'tomorrow') {
                $q->whereDate('appointment_date', Carbon::tomorrow());
            } elseif ($dateType == 'specific') {
                $q->whereDate('appointment_date', $selectedDate);
            } elseif ($dateType == 'week') {
                $q->whereBetween('appointment_date', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            } elseif ($dateType == 'month') {
                $q->whereMonth('appointment_date', Carbon::now()->month);
            }
            // For 'all', no date filter
        });

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('appointments', function($q2) use ($search) {
                      $q2->where('type', 'like', "%{$search}%")
                         ->orWhere('notes', 'like', "%{$search}%")
                         ->orWhereHas('service', function($q3) use ($search) {
                             $q3->where('name', 'like', "%{$search}%");
                         })
                         ->orWhereHas('doctor', function($q4) use ($search) {
                             $q4->where('name', 'like', "%{$search}%");
                         });
                  });
            });
        }

        $patients = $query->paginate(10);

        $doctors = User::where('role', 'doctor')->get();
        $services = Service::all();

        return view('patient-history.index', compact(
            'patients',
            'doctors',
            'services',
            'selectedDate',
            'dateType'
        ));
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments' => function($query) {
            $query->latest('appointment_date')
                  ->with(['doctor', 'service']);
        }]);

        $totalAmount = $patient->appointments->sum('amount');
        $visitCount = $patient->appointments->count();
        $lastVisit = $patient->appointments->first()
            ? $patient->appointments->first()->appointment_date->format('M d, Y')
            : 'No visits yet';

        return view('patient-history.show', compact(
            'patient',
            'totalAmount',
            'visitCount',
            'lastVisit'
        ));
    }

    public function schedule(Request $request, Patient $patient)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'type' => 'required|string',
            'notes' => 'nullable|string',
            'amount' => 'required|numeric'
        ]);

        Appointment::create([
            'patient_id' => $patient->id,
            'doctor_id' => $request->doctor_id,
            'service_id' => $request->service_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'type' => $request->type,
            'notes' => $request->notes,
            'amount' => $request->amount,
            'status' => 'scheduled'
        ]);

        return redirect()->back()->with('success', 'Appointment scheduled successfully.');
    }

    public function downloadReport(Patient $patient)
    {
        $appointments = $patient->appointments()
            ->with(['doctor', 'service'])
            ->latest('appointment_date')
            ->get();

        $totalAmount = $appointments->sum('amount');

        // You can implement PDF generation here using DomPDF or similar
        // For now, return a view
        return view('patient-history.report', compact('patient', 'appointments', 'totalAmount'));
    }

    public function filterByDate(Request $request)
    {
        $date = $request->get('date', today()->format('Y-m-d'));
        $dateType = $request->get('date_type', 'specific');

        return redirect()->route('patient-history.index', [
            'date' => $date,
            'date_type' => $dateType
        ]);
    }
}