<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
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

    // Relationship with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'assigned_doctor', 'id');
    }

    // Accessor for doctor name
    public function getDoctorNameAttribute()
    {
        return $this->doctor ? $this->doctor->full_name : 'Not Assigned';
    }

    // Accessor for formatted date of birth
    public function getDateOfBirthFormattedAttribute()
    {
        if ($this->date_of_birth_day && $this->date_of_birth_month && $this->date_of_birth_year) {
            try {
                return \Carbon\Carbon::createFromDate(
                    $this->date_of_birth_year,
                    $this->date_of_birth_month,
                    $this->date_of_birth_day
                )->format('F j, Y');
            } catch (\Exception $e) {
                return "Invalid date";
            }
        }
        return 'Not specified';
    }
}