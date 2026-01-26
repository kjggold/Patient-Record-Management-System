<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'success',
        'meta',
    ];

    protected $casts = [
        'success' => 'boolean',
        'meta' => 'array',
    ];
}

