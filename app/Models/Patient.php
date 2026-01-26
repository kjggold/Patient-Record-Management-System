<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'full_name',
        'national_id_passport',
        'age',
        'sex_gender',
        'date_of_birth_day',
        'date_of_birth_month',
        'date_of_birth_year',
        'phone_number',
        'address',
        'known_medical_conditions',
        'allergies',
        'blood_type',
        'alcohol_consumption',
        'assigned_doctor',
        'registration_date'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'assigned_doctor', 'doctor_name');
    }
}