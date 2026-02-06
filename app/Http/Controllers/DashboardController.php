<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get real statistics
        $doctors = Doctor::all();
        $appointments = Appointment::all();
        $totalPatients = Patient::count();
        $activeDoctors = Doctor::where('status', 'active')->count();
        $today = Carbon::today();
        
        // $appointmentsToday = Appointment::whereDate('appointment_date', $today)->count();
        
        // // Calculate monthly revenue (assuming appointments have fees)
        // $monthlyRevenue = Appointment::whereMonth('created_at', now()->month)
        //     ->whereYear('created_at', now()->year)
        //     ->sum('fee') ?? 0;

        // // Get today's appointments with patient and doctor info
        // $appointments = Appointment::with(['patient', 'doctor'])
        //     ->whereDate('appointment_date', $today)
        //     ->orderBy('appointment_time')
        //     ->limit(5)
        //     ->get()
        //     ->map(function ($appointment) {
        //         return [
        //             'patient' => $appointment->patient->full_name ?? 'Unknown',
        //             'doctor' => $appointment->doctor->full_name ?? 'Unknown',
        //             'status' => $appointment->status ?? 'scheduled'
        //         ];
        //     });

        // Get patient statistics for chart
        $patientStats = $this->getPatientStatistics();

        return view('dashboard', compact(
            'totalPatients',
            'activeDoctors',
            // 'appointmentsToday',
            // 'monthlyRevenue',
            // 'appointments',
            'patientStats',
            'doctors'
        ));
    }

    private function getPatientStatistics()
    {
        // Get data for last 8 days
        $startDate = Carbon::now()->subDays(7);
        $endDate = Carbon::now();

        $dates = [];
        $labels = [];
        $currentDate = $startDate->copy();
        
        for ($i = 0; $i < 8; $i++) {
            $dateStr = $currentDate->format('Y-m-d');
            $dates[] = $dateStr;
            $labels[] = $currentDate->format('j M');
            $currentDate->addDay();
        }

        // Query patient counts by age group
        $stats = Patient::selectRaw('
                DATE(registration_date) as date,
                SUM(CASE WHEN age <= 17 THEN 1 ELSE 0 END) as child_count,
                SUM(CASE WHEN age BETWEEN 18 AND 64 THEN 1 ELSE 0 END) as adult_count,
                SUM(CASE WHEN age >= 65 THEN 1 ELSE 0 END) as elderly_count
            ')
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(registration_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Initialize arrays
        $childData = array_fill(0, 8, 0);
        $adultData = array_fill(0, 8, 0);
        $elderlyData = array_fill(0, 8, 0);

        // Fill data
        foreach ($dates as $index => $date) {
            if (isset($stats[$date])) {
                $childData[$index] = (int)$stats[$date]->child_count;
                $adultData[$index] = (int)$stats[$date]->adult_count;
                $elderlyData[$index] = (int)$stats[$date]->elderly_count;
            }
        }

        return [
            'labels' => $labels,
            'childData' => $childData,
            'adultData' => $adultData,
            'elderlyData' => $elderlyData
        ];
    }
    
    // Add API endpoint for AJAX updates
    public function getPatientChartData(Request $request)
    {
        $days = $request->input('days', 8);
        $startDate = Carbon::now()->subDays($days - 1);
        $endDate = Carbon::now();

        $dates = [];
        $labels = [];
        $currentDate = $startDate->copy();
        
        for ($i = 0; $i < $days; $i++) {
            $dateStr = $currentDate->format('Y-m-d');
            $dates[] = $dateStr;
            $labels[] = $currentDate->format('j M');
            $currentDate->addDay();
        }

        $stats = Patient::selectRaw('
                DATE(registration_date) as date,
                SUM(CASE WHEN age <= 17 THEN 1 ELSE 0 END) as child_count,
                SUM(CASE WHEN age BETWEEN 18 AND 64 THEN 1 ELSE 0 END) as adult_count,
                SUM(CASE WHEN age >= 65 THEN 1 ELSE 0 END) as elderly_count
            ')
            ->whereBetween('registration_date', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(registration_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $childData = array_fill(0, $days, 0);
        $adultData = array_fill(0, $days, 0);
        $elderlyData = array_fill(0, $days, 0);

        foreach ($dates as $index => $date) {
            if (isset($stats[$date])) {
                $childData[$index] = (int)$stats[$date]->child_count;
                $adultData[$index] = (int)$stats[$date]->adult_count;
                $elderlyData[$index] = (int)$stats[$date]->elderly_count;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => 'Child (0-17)',
                        'data' => $childData,
                        'backgroundColor' => '#22d3ee'
                    ],
                    [
                        'label' => 'Adult (18-64)',
                        'data' => $adultData,
                        'backgroundColor' => '#3b82f6'
                    ],
                    [
                        'label' => 'Elderly (65+)',
                        'data' => $elderlyData,
                        'backgroundColor' => '#38bdf8'
                    ]
                ]
            ]
        ]);
    }
}