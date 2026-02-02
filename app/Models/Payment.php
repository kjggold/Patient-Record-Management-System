<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [

        'appointment_id',
        'patient_name',
        'service',

        'total',
        'discount',
        'paid',

        'payment_method',
        'remarks'
    ];
}
