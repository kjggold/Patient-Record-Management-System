<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // Updated fillable array - removed total, discount, paid, payment_method
    protected $fillable = [
        'patient_id',
        'patient_name',
        'doctor_id',
        'service',
        'date',
        'time',
        'status',
        'remarks',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationship with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Relationship with patient
    public function patient()
    {
        $patient = Patient::find($this->patient_id);
        if (!$patient && $this->patient_name) {
            $patient = Patient::where('full_name', $this->patient_name)->first();
        }
        return $patient;
    }

    // Accessor for appointment status badge color
    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'scheduled' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'no-show' => 'bg-gray-100 text-gray-800',
            'rescheduled' => 'bg-yellow-100 text-yellow-800',
        ];

        return $statusColors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    // Accessor for formatted date
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('D, M j, Y') :
               ($this->created_at ? $this->created_at->format('D, M j, Y') : 'No date');
    }
}