<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Discharge;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get real statistics
        $doctors = Doctor::all();
        $services = Service::all();
        $patients = Patient::all();
        $appointments = Appointment::all();
        $totalPatients = Patient::count();
        $activeDoctors = Doctor::count();
        $today = Carbon::today();

        $appointmentsToday = Appointment::count();

        // Calculate monthly revenue from discharged patients (total paid amount)
        $monthlyRevenue = Discharge::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('paid') ?? 0;  // Use sum of 'paid' instead of total-discount

        // Alternative: If you want to show total paid (revenue) in dollars
        $monthlyRevenueUSD = number_format($monthlyRevenue / 1000, 2); // Convert to thousands with 2 decimals

        // Get patient statistics for chart
        $patientStats = $this->getPatientStatistics();

        return view('dashboard', compact(
            'totalPatients',
            'activeDoctors',
            'appointmentsToday',
            'monthlyRevenue',
            'patientStats',
            'doctors',
            'patients',
            'services',
            'monthlyRevenueUSD'  // Pass the formatted USD value if needed
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

        // Create 8 days of data
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
            ->whereDate('registration_date', '>=', $startDate)
            ->whereDate('registration_date', '<=', $endDate)
            ->groupBy(DB::raw('DATE(registration_date)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Initialize arrays with 8 elements
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