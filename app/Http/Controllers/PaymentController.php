<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // Display payments page
    public function index()
    {
        // Example payments data, replace with DB later
        $payments = [
            ['patient'=>'John Smith','doctor'=>'Dr. Sarah Johnson','service'=>'General Consultation','amount'=>15000,'date'=>'2026-01-19'],
            ['patient'=>'Emma Wilson','doctor'=>'Dr. Michael Brown','service'=>'Child Health','amount'=>20000,'date'=>'2026-01-18'],
            ['patient'=>'Robert Davis','doctor'=>'Dr. Sarah Johnson','service'=>'Heart & Blood Pressure','amount'=>30000,'date'=>'2026-01-17'],
            ['patient'=>'Lily Tan','doctor'=>'Dr. May Lin','service'=>'Diabetes Care','amount'=>25000,'date'=>'2026-01-16'],
            ['patient'=>'David Lee','doctor'=>'Dr. Ei Phyo','service'=>"Women's Health",'amount'=>30000,'date'=>'2026-01-15'],
            ['patient'=>'Sarah Tan','doctor'=>'Dr. Kyaw Myint','service'=>'Heart & Blood Pressure','amount'=>32000,'date'=>'2026-01-14'],
            ['patient'=>'Michael Wong','doctor'=>'Dr. Hnin Ei','service'=>'Diabetes Care','amount'=>27000,'date'=>'2026-01-13'],
        ];

        return view('payments.index', compact('payments'));
    }
}
