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
        'services',   // stored as JSON
        'total',
        'discount',
        'paid',
        'balance',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'services' => 'array', // Automatically cast JSON to array
        'total' => 'integer',
        'discount' => 'integer',
        'paid' => 'integer',
        'balance' => 'integer',
    ];
}
