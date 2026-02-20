<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_name',
        'service_fee',
        'description',
    ];

    // Add this relationship
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Accessor for formatted service fee
    public function getFormattedFeeAttribute()
    {
        return '$' . number_format($this->service_fee, 2);
    }

    // Accessor for service name with fee
    public function getNameWithFeeAttribute()
    {
        return $this->service_name . ' ($' . number_format($this->service_fee, 2) . ')';
    }

    // Check if service has description
    public function getHasDescriptionAttribute()
    {
        return !empty(trim($this->description));
    }
}