<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResidentModel extends Model
{
    protected $table = 'residents';

    protected $fillable = [
        'user_id',
        'family_id',
        'full_name',
        'nik',
        'phone_number',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'blood_type',
        'family_role',
        'last_education',
        'occupation',
        'is_family_head',
        'is_alive',
        'is_active',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_family_head' => 'boolean',
        'is_alive' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function family(): BelongsTo
    {
        return $this->belongsTo(FamilyModel::class, 'family_id');
    }
}
