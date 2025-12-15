<?php

namespace App\Models;

use App\Enums\RelocationType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyRelocation extends Model
{
    protected $table = 'family_relocations';

    protected $fillable = [
        'relocation_type',
        'relocation_date',
        'reason',
        'family_id',
        'past_address_id',
        'new_address_id',
        'created_by',
    ];

    protected $casts = [
        'relocation_type' => RelocationType::class,
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(FamilyModel::class, 'family_id');
    }

    public function pastAddress(): BelongsTo
    {
        return $this->belongsTo(AddressModel::class, 'past_address_id');
    }

    public function newAddress(): BelongsTo
    {
        return $this->belongsTo(AddressModel::class, 'new_address_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
