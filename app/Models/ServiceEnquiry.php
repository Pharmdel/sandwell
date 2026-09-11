<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceEnquiry extends Model
{
    protected $fillable = [
        'service', 'name', 'phone', 'email', 'detail',
        'best_time', 'dob', 'region', 'travel_date', 'address', 'notes',
        'postcode', 'gp', 'medications', 'delivery', 'nhs_number', 'duration', 'symptoms',
    ];

    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'travel_date' => 'date',
        ];
    }
}
