<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillModel extends Model
{
    protected $table = 'bills';

    protected $fillable = [
        'code',
        'family_id',
        'income_category_id',
        'periode',
        'amount',
        'status',
        'payment_proof',
        'paid_at',
        'verified_by',
        'verified_at',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        'periode' => 'date',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    protected $hidden = [
        // No sensitive data to hide for now
    ];

    /**
     * Get the family this bill belongs to
     */
    public function family(): BelongsTo
    {
        return $this->belongsTo(FamilyModel::class, 'family_id');
    }

    /**
     * Get the income category (contribution category) for this bill
     */
    public function incomeCategory(): BelongsTo
    {
        return $this->belongsTo(\App\Models\IncomeCategoryModel::class, 'income_category_id');
    }

    /**
     * Get the user who created this bill
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the admin who verified this bill
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp. ' . number_format((float) $this->amount, 0, ',', '.');
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'unpaid' => 'Belum Dibayar',
            'pending' => 'Menunggu Verifikasi',
            'paid' => 'Sudah Dibayar',
            'rejected' => 'Ditolak',
            'overdue' => 'Terlambat',
        ][$this->status] ?? 'Unknown';
    }

    /**
     * Get periode label (formatted date)
     */
    public function getPeriodeLabelAttribute(): string
    {
        return $this->periode ? \Carbon\Carbon::parse($this->periode)->format('d F Y') : '';
    }

    /**
     * Scope for unpaid bills
     */
    public function scopeUnpaid($query)
    {
        return $query->where('status', 'unpaid');
    }

    /**
     * Scope for paid bills
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope for overdue bills
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue');
    }

    /**
     * Scope for pending bills (waiting verification)
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected bills
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for specific family
     */
    public function scopeForFamily($query, $familyId)
    {
        return $query->where('family_id', $familyId);
    }

    /**
     * Scope for specific income category
     */
    public function scopeForIncomeCategory($query, $categoryId)
    {
        return $query->where('income_category_id', $categoryId);
    }

    /**
     * Scope for specific periode
     */
    public function scopeForPeriode($query, $periode)
    {
        return $query->whereDate('periode', $periode);
    }
}
