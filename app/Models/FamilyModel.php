<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FamilyModel extends Model
{
    protected $table = 'families';

    protected $fillable = [
        'name',
        'head_resident_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function headResident(): BelongsTo
    {
        return $this->belongsTo(ResidentModel::class, 'head_resident_id');
    }

    public function residents(): HasMany
    {
        return $this->hasMany(ResidentModel::class, 'family_id');
    }

    public function addressHistory(): HasMany
    {
        return $this->hasMany(FamilyAddressHistoryModel::class, 'family_id');
    }

    public function currentAddress()
    {
        return $this->addressHistory()
            ->whereNull('moved_out_at')
            ->with('address')
            ->first();
    }
}
