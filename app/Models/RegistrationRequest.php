<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'encrypted_password',
        'ip_address',
        'user_agent',
        'approval_token', // Add this
        'status',         // Add this
    ];

    // Optional: cast status to string
    protected $casts = [
        'status' => 'string',
    ];
}