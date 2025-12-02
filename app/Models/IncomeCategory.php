<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeCategory extends Model
{
    protected $table = 'income_categories';

    protected $fillable = [
        'name',
        'type',
        'nominal',
        'description',
        'created_by',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
    ];

    /**
     * Get the user who created this income category
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the type label for display
     */
    public function getTypeLabelAttribute(): string
    {
        return [
            'bulanan' => 'Iuran Bulanan',
            'mingguan' => 'Iuran Mingguan',
            'tahunan' => 'Iuran Tahunan',
            'sekali_bayar' => 'Sekali Bayar',
        ][$this->type] ?? 'Tidak Diketahui';
    }

    /**
     * Get formatted nominal
     */
    public function getFormattedNominalAttribute(): string
    {
        return 'Rp. ' . number_format((float) $this->nominal, 0, ',', '.');
    }

    /**
     * Scope for specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
