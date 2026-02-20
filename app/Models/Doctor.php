<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'full_name',
        'speciality',
        'experience',
        'phone_number',
        'email',
        'consultation_fee',
        'status',
    ];

    // Add these relationships
    public function patients()
    {
        return $this->hasMany(Patient::class, 'assigned_doctor');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    // Accessor for formatted name with speciality
    public function getNameWithSpecialityAttribute()
    {
        if ($this->speciality) {
            return 'Dr. ' . $this->full_name . ' (' . $this->speciality . ')';
        }
        return 'Dr. ' . $this->full_name;
    }

    // Accessor for formatted consultation fee
    public function getFormattedFeeAttribute()
    {
        return '$' . number_format($this->consultation_fee, 2);
    }

    // Check if doctor is active
    public function getIsActiveAttribute()
    {
        return $this->status === 'active' || $this->status === 'available';
    }
}