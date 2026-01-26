<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'appointment_id',
        'service',
        'consultation_fee',
        'service_fee',
        'payment method',
        'remarks',
    ];


    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'consultation_fee', 'consultation_fee');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_name', 'full_name');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service', 'service_name');
    }

    public function serviceById()
    {
        return $this->belongsTo(Service::class, 'id');
    }
}