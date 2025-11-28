<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BroadcastModel extends Model
{
    protected $table = 'broadcasts';
    protected $fillable = [
        'title',
        'message',
        'published_at',
        'created_by',
        'photo',
        'document',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? url("storage/{$this->photo}") : null;
    }

    public function getDocumentUrlAttribute(): ?string
    {
        return $this->document ? url("storage/{$this->document}") : null;
    }
}
