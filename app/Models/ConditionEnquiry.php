<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConditionEnquiry extends Model
{
    protected $fillable = ['condition', 'name', 'dob', 'phone', 'email', 'symptoms', 'answers'];

    protected function casts(): array
    {
        return ['answers' => 'array', 'dob' => 'date'];
    }
}
