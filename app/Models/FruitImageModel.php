<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FruitImageModel extends Model
{
        protected $table = 'fruit_images';

    protected $fillable = [
        'name',
        'family_id',
        'file',
    ];

    public function family(): BelongsTo
    {
        return $this->belongsTo(FamilyModel::class, 'family_id');
    }
}
