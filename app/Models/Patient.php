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
        'assigned_doctor',
        'registration_date',
        'created_by',
        'updated_by'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    // Relationship with doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'assigned_doctor');
    }

    // Relationship with creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with updater
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}