<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'patient_name',
        'doctor_id',
        'service_id',
        'appointment_date',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at'
    ];

    // Add this for proper date casting
    protected $casts = [
        'appointment_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }

    public function discharge()
    {
        return $this->hasOne(Discharge::class);
    }

    // Add these accessors/methods
    public function getAppointmentDateFormattedAttribute()
    {
        return $this->appointment_date ? $this->appointment_date->format('M d, Y') : 'Not scheduled';
    }

    // Since you don't have status column, we'll assume scheduled
    public function getStatusAttribute()
    {
        return 'scheduled';
    }

    public function getFormattedStatusAttribute()
    {
        return 'Scheduled';
    }

    public function getStatusColorAttribute()
    {
        return 'bg-blue-100 text-blue-800';
    }

    // Check if appointment is completed (you might want to check discharge table)
    public function getIsCompletedAttribute()
    {
        return $this->discharge ? true : false;
    }

    // Check if appointment can be discharged
    public function getCanBeDischargedAttribute()
    {
        return !$this->discharge;
    }
    // app/Models/Appointment.php

}