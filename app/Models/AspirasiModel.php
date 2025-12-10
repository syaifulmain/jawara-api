<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AspirasiModel extends Model
{
    use HasFactory;

    protected $table = 'aspirations';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'status',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
         'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
