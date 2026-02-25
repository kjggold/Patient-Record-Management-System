<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
            'age',
            'sex_gender',
            'date_of_birth',
            'phone_number',
            'address',
            'known_medical_conditions',
            'allergies',
            'blood_type',
            'alcohol_consumption',
            'registration_date',
            'created_by',
            'updated_by',
    ];

    // In app/Models/Patient.php


    // Relationship with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'assigned_doctor', 'id');
    }

    // Add this relationship with appointments
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
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

    // Calculate age from date of birth parts
    public function getAgeAttribute()
    {
        if ($this->date_of_birth_day && $this->date_of_birth_month && $this->date_of_birth_year) {
            try {
                $birthDate = \Carbon\Carbon::createFromDate(
                    $this->date_of_birth_year,
                    $this->date_of_birth_month,
                    $this->date_of_birth_day
                );
                return $birthDate->age;
            } catch (\Exception $e) {
                return null;
            }
        }
        return $this->attributes['age'] ?? null;
    }

    // Get last visit from appointments
    public function getLastVisitAttribute()
    {
        $lastAppointment = $this->appointments()
            ->orderBy('appointment_date', 'desc')
            ->first();

        return $lastAppointment ? \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('M d, Y') : 'No visits yet';
    }

    // Calculate total visits
    public function getTotalVisitsAttribute()
    {
        return $this->appointments()->count();
    }

    // Get upcoming appointments
    public function upcomingAppointments()
    {
        return $this->appointments()
            ->where('appointment_date', '>=', now())
            ->where('status', 'scheduled')
            ->orderBy('appointment_date', 'asc');
    }
}
