<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_name',
        'service',
        'total',
        'discount',
        'paid',
        'payment_method',
        'remarks'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid' => 'decimal:2',
    ];

    // Relationship with appointment
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    // Accessor for payment status
    public function getPaymentStatusAttribute()
    {
        if ($this->paid == 0) {
            return 'Unpaid';
        } elseif ($this->paid < $this->total) {
            return 'Partially Paid';
        } elseif ($this->paid == $this->total) {
            return 'Fully Paid';
        } else {
            return 'Overpaid';
        }
    }

    // Accessor for payment status color
    public function getPaymentStatusColorAttribute()
    {
        $status = $this->payment_status;

        $colors = [
            'Unpaid' => 'text-red-600 bg-red-50',
            'Partially Paid' => 'text-yellow-600 bg-yellow-50',
            'Fully Paid' => 'text-green-600 bg-green-50',
            'Overpaid' => 'text-blue-600 bg-blue-50',
        ];

        return $colors[$status] ?? 'text-gray-600 bg-gray-50';
    }
}