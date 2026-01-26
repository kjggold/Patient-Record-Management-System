<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;
     protected $fillable = [
        'doctor_id',
        'full_name',
        'speciality',
        'experience',
        'phone_number',
        'email',
        'consultation_fee',
        'status',
     ];
 }