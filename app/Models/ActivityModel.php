<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityModel extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'name',
        'category',
        'date',
        'location',
        'person_in_charge',
        'description',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Convenient accessor for category label
    public function getCategoryLabelAttribute()
    {
        return [
            'keagamaan' => 'Keagamaan',
            'pendidikan' => 'Pendidikan',
            'lainnya' => 'Lainnya',
        ][$this->category] ?? 'Lainnya';
    }
}
