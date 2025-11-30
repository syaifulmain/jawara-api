<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddressModel extends Model
{
    protected $table = 'addresses';

    protected $fillable = [
        'address',
    ];

    public function familyHistory(): HasMany
    {
        return $this->hasMany(FamilyAddressHistoryModel::class, 'address_id');
    }

    public function currentFamilies()
    {
        return $this->familyHistory()->whereNull('moved_out_at');
    }
}
