<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'date_of_birth',
        'sex_gender',
        'phone_number',
        'email',
        'address',
        'blood_type',
        'alcohol_consumption',
        'known_medical_conditions',
        'allergies',
        'registration_date',
    ];

    protected $dates = [
        'date_of_birth',
        'registration_date',
    ];

    // Relationships
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class);
    }

    // Accessors
    public function getLatestDoctorAttribute()
    {
        return $this->appointments()->latest('appointment_date')->first()?->doctor;
    }

    public function getAgeAttribute()
    {
        if ($this->date_of_birth) {
            return Carbon::parse($this->date_of_birth)->age;
        }
        return null;
    }

    public function getDateOfBirthFormattedAttribute()
    {
        return $this->date_of_birth
            ? Carbon::parse($this->date_of_birth)->format('F j, Y')
            : null;
    }

    public function getLastVisitAttribute()
    {
        $latestAppointment = $this->appointments()->latest('appointment_date')->first();
        return $latestAppointment
            ? Carbon::parse($latestAppointment->appointment_date)->format('F j, Y')
            : null;
    }

    public function getTotalVisitsAttribute()
    {
        return $this->appointments()->count();
    }
}
