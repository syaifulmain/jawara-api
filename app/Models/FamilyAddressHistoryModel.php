<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyAddressHistoryModel extends Model
{
    protected $table = 'family_address_history';

    protected $fillable = [
        'family_id',
        'address_id',
        'status',
        'moved_in_at',
        'moved_out_at',
    ];

    protected $casts = [
        'moved_in_at' => 'date',
        'moved_out_at' => 'date',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(FamilyModel::class, 'family_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(AddressModel::class, 'address_id');
    }
}
