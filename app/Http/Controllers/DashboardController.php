<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Ensure only authenticated users can access
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
         // Debug: Check if user is authenticated
    if (!auth()->check()) {
        abort(403, 'Not authenticated');
    }

    // Get authenticated user
    $user = auth()->user();

    // Debug: Check user data
    \Log::info('Dashboard accessed by user: ' . $user->id . ' - ' . $user->name);

        // Total patients
        $totalPatients = Patient::count();

        // Active doctors
        $activeDoctors = Doctor::where('status', 'active')->count();

        // Patients by age
        $childPatients = Patient::where('age', '<', 18)->count();
        $adultPatients = Patient::whereBetween('age', [18, 59])->count();
        $elderlyPatients = Patient::where('age', '>=', 60)->count();

        // Recent patients
        $recentPatients = Patient::with(['doctor:id,full_name'])
            ->orderBy('registration_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Current and last month stats
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth();

        $currentMonthPatients = Patient::whereYear('registration_date', $currentYear)->whereMonth('registration_date', $currentMonth)->count();

        $lastMonthPatients = Patient::whereYear('registration_date', $lastMonth->year)->whereMonth('registration_date', $lastMonth->month)->count();

        $patientChange = $lastMonthPatients > 0 ? round((($currentMonthPatients - $lastMonthPatients) / $lastMonthPatients) * 100, 1) : ($currentMonthPatients > 0 ? 100 : 0);

        $currentMonthDoctors = Doctor::where('status', 'active')->whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();

        $lastMonthDoctors = Doctor::where('status', 'active')->whereYear('created_at', $lastMonth->year)->whereMonth('created_at', $lastMonth->month)->count();

        $doctorChange = $lastMonthDoctors > 0 ? round((($currentMonthDoctors - $lastMonthDoctors) / $lastMonthDoctors) * 100, 1) : ($currentMonthDoctors > 0 ? 100 : 0);

        return view('dashboard', compact(
            'user',           // ← This must be included
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
