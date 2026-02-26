<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discharge extends Model
{
    protected $table = 'discharges';

    protected $fillable = [
        'appointment_id',
        'patient_name',
        'doctor_name',
        'service_id',        // Add this
        'service_name',      // Add this
        'service_price',     // Add this
        'services',          // Keep this if you want to store all services
        'total',
        'discount',
        'paid',
        'balance',
    ];

    protected $casts = [
            'services' => 'array', // Automatically cast JSON to array
            'total' => 'integer',
            'discount' => 'integer',
            'paid' => 'integer',
            'balance' => 'integer',
    ];
}