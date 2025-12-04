<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeModel extends Model
{
    protected $table = 'income';

    protected $fillable = [
        'name',
        'income_type',
        'date',
        'amount',
        'date_verification',
        'verification',
    ];

    protected $casts = [
        'date' => 'date',
        'date_verification' => 'date',
        'amount' => 'decimal:2',
    ];

    
}
