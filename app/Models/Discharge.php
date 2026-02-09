<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'total',
        'discount',
        'paid',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items()
    {
        return $this->hasMany(DischargeService::class);
    }
}
