<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    // Display all payments
    public function index()
    {
        $payments = Payment::latest()->get();
        return view('payments', compact('payments')); // resources/views/payments.blade.php
    }

    // Store new payment
    public function store(Request $request)
    {
        $request->validate([
            'patient' => 'required|string|max:255',
            'doctor'  => 'required|string|max:255',
            'service' => 'required|string|max:255',
            'amount'  => 'required|numeric',
            'date'    => 'required|date',
        ]);

        Payment::create($request->all());

        return redirect()->route('payments.index')->with('success', 'Payment added successfully!');
    }

    // Optional: show, edit, update, destroy methods for resource controller
}
