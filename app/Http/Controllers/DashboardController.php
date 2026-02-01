<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total patients count
        $totalPatients = Patient::count();
        
        // Get active doctors count
        $activeDoctors = Doctor::where('status', 'active')->count();
        
        // Calculate patient distribution by age group
        $childPatients = Patient::where('age', '<', 18)->count();
        $adultPatients = Patient::whereBetween('age', [18, 59])->count();
        $elderlyPatients = Patient::where('age', '>=', 60)->count();
        
        // Get recent patients by registration date (not created_at)
        $recentPatients = Patient::with(['doctor' => function($query) {
            $query->select('id', 'full_name');
        }])
        ->orderBy('registration_date', 'desc')
        ->orderBy('created_at', 'desc')
        ->take(5)
        ->get();
        
        // Calculate changes from last month
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        // Get patients registered in current month
        $currentMonthPatients = Patient::whereYear('registration_date', $currentYear)
            ->whereMonth('registration_date', $currentMonth)
            ->count();
        
        // Get patients registered in previous month
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthPatients = Patient::whereYear('registration_date', $lastMonth->year)
            ->whereMonth('registration_date', $lastMonth->month)
            ->count();
        
        // Calculate patient change percentage
        if ($lastMonthPatients > 0) {
            $patientChange = round((($currentMonthPatients - $lastMonthPatients) / $lastMonthPatients) * 100, 1);
        } else {
            $patientChange = $currentMonthPatients > 0 ? 100 : 0;
        }
        
        // Get doctors added in current month
        $currentMonthDoctors = Doctor::where('status', 'active')
            ->whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->count();
        
        // Get doctors added in previous month
        $lastMonthDoctors = Doctor::where('status', 'active')
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();
        
        // Calculate doctor change percentage
        if ($lastMonthDoctors > 0) {
            $doctorChange = round((($currentMonthDoctors - $lastMonthDoctors) / $lastMonthDoctors) * 100, 1);
        } else {
            $doctorChange = $currentMonthDoctors > 0 ? 100 : 0;
        }
        
        // Pass all data to view
        return view('dashboard', compact(
            'totalPatients',
            'activeDoctors',
            'childPatients',
            'adultPatients',
            'elderlyPatients',
            'recentPatients',
            'patientChange',
            'doctorChange'
        ));
    }
}