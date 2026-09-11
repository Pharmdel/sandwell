<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightLossEnquiry extends Model
{
    protected $fillable = ['kind', 'name', 'phone', 'email', 'treatment', 'payload'];

    protected function casts(): array
    {
        return ['payload' => 'array'];
    }
}
