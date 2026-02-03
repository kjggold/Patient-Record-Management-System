<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'patient_name',
        'doctor_name',
        'services',
        'total',
        'paid',
        'balance',
        'payment_method',
    ];

    protected $casts = [
        'services' => 'array', // JSON -> Array
    ];
}
