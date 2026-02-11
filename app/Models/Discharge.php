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
        'services',
        'total',
        'discount',
        'paid',
        'balance',
    ];

    protected $casts = [
    'services' => 'array', // automatically cast JSON to array
];

}