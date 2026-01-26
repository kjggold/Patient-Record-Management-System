<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_name',
        'doctor_name',
        'service',
        'appointment_date',
        'appointment_time',
        'phone',
        'notes_optional',
        'status',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_name', 'full_name');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_name', 'full_name');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service', 'service_name');
    }
}