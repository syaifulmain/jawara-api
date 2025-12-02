<?php

namespace App\Models;

use App\Enums\TransferChannelType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferChannel extends Model
{
    /** @use HasFactory<\Database\Factories\TransferChannelFactory> */
    use HasFactory;

    protected $table = 'transfer_channels';
    protected $guarded = [];

    protected $casts = [
        'type' => TransferChannelType::class,
    ];

}
