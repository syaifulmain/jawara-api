<?php

namespace App\Services;

use App\Models\BillModel;
use App\Models\IncomeCategoryModel;
use App\Models\FamilyModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BillService
{
    /**
     * Generate bills for all active families for a specific income category
     */
    public function generateBillsForCategory(int $incomeCategoryId, string $periode): array
    {
        DB::beginTransaction();
        
        try {
            // Get the income category
            $incomeCategory = IncomeCategoryModel::findOrFail($incomeCategoryId);
            
            // Get all active families
            $activeFamilies = FamilyModel::where('is_active', true)->get();
            
            $generatedBills = [];
            $skippedFamilies = [];
            
            foreach ($activeFamilies as $family) {
                // Check if bill already exists for this family, category, and periode
                $existingBill = BillModel::where('family_id', $family->id)
                    ->where('income_category_id', $incomeCategoryId)
                    ->whereDate('periode', $periode)
                    ->first();
                
                if ($existingBill) {
                    $skippedFamilies[] = [
                        'family_id' => $family->id,
                        'family_name' => $family->name,
                        'reason' => 'Bill already exists',
                        'existing_code' => $existingBill->code
                    ];
                    continue;
                }
                
                // Generate unique bill code
                $billCode = $this->generateBillCode($incomeCategory->type, $periode);
                
                // Create the bill
                $bill = BillModel::create([
                    'code' => $billCode,
                    'family_id' => $family->id,
                    'income_category_id' => $incomeCategoryId,
                    'periode' => $periode,
                    'amount' => $incomeCategory->nominal,
                    'status' => 'unpaid',
                    'created_by' => Auth::id(),
                ]);
                
                $generatedBills[] = $bill;
            }
            
            DB::commit();
            
            return [
                'success' => true,
                'generated_count' => count($generatedBills),
                'skipped_count' => count($skippedFamilies),
                'generated_bills' => $generatedBills,
                'skipped_families' => $skippedFamilies,
                'total_families' => $activeFamilies->count()
            ];
            
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Generate bills for multiple categories
     */
    public function generateBillsForCategories(array $categoryIds, string $periode): array
    {
        $results = [];
        
        foreach ($categoryIds as $categoryId) {
            $results[] = $this->generateBillsForCategory($categoryId, $periode);
        }
        
        return $results;
    }
    
    /**
     * Generate unique bill code
     */
    private function generateBillCode(string $categoryType, string $periode): string
    {
        $date = Carbon::parse($periode);
        $year = $date->format('Y');
        $month = $date->format('m');
        
        // Generate prefix based on category type
        $prefix = match($categoryType) {
            'bulanan' => 'IB',    // Iuran Bulanan
            'mingguan' => 'IM',   // Iuran Mingguan  
            'tahunan' => 'IT',    // Iuran Tahunan
            'sekali_bayar' => 'IS', // Iuran Sekali
            default => 'IR'       // Iuran Regular
        };
        
        // Generate random string
        $randomString = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        
        return $prefix . $year . $month . $randomString;
    }
    
    /**
     * Upload payment proof by user (change status to pending)
     */
    public function uploadPaymentProof(int $billId, string $paymentProof): array
    {
        try {
            $bill = BillModel::findOrFail($billId);
            
            // Only unpaid or rejected bills can upload proof
            if (!in_array($bill->status, ['unpaid', 'rejected', 'overdue'])) {
                return [
                    'success' => false,
                    'message' => 'Bill status must be unpaid, rejected, or overdue to upload payment proof'
                ];
            }
            
            $bill->update([
                'status' => 'pending',
                'payment_proof' => $paymentProof,
                'paid_at' => now(),
                'rejection_reason' => null, // Clear previous rejection
            ]);
            
            return [
                'success' => true,
                'message' => 'Payment proof uploaded successfully, waiting for admin verification',
                'bill' => $bill
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Approve payment by admin (pending -> paid)
     */
    public function approvePayment(int $billId): array
    {
        try {
            $bill = BillModel::findOrFail($billId);
            
            // Only pending bills can be approved
            if ($bill->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Only pending bills can be approved'
                ];
            }
            
            $bill->update([
                'status' => 'paid',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
            ]);
            
            return [
                'success' => true,
                'message' => 'Payment approved successfully',
                'bill' => $bill
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Reject payment by admin (pending -> rejected)
     */
    public function rejectPayment(int $billId, string $reason): array
    {
        try {
            $bill = BillModel::findOrFail($billId);
            
            // Only pending bills can be rejected
            if ($bill->status !== 'pending') {
                return [
                    'success' => false,
                    'message' => 'Only pending bills can be rejected'
                ];
            }
            
            $bill->update([
                'status' => 'rejected',
                'verified_by' => Auth::id(),
                'verified_at' => now(),
                'rejection_reason' => $reason,
            ]);
            
            return [
                'success' => true,
                'message' => 'Payment rejected',
                'bill' => $bill
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Mark bills as overdue
     */
    public function markOverdueBills(): array
    {
        $overdueBills = BillModel::where('status', 'unpaid')
            ->where('periode', '<', now()->startOfMonth())
            ->get();
            
        $updatedCount = 0;
        
        foreach ($overdueBills as $bill) {
            $bill->update(['status' => 'overdue']);
            $updatedCount++;
        }
        
        return [
            'updated_count' => $updatedCount,
            'overdue_bills' => $overdueBills
        ];
    }
    
    /**
     * Get payment statistics
     */
    public function getPaymentStatistics(string $periode = null): array
    {
        $query = BillModel::query();
        
        if ($periode) {
            $query->whereDate('periode', $periode);
        }
        
        return [
            'total_bills' => $query->count(),
            'unpaid_bills' => $query->clone()->unpaid()->count(),
            'pending_bills' => $query->clone()->pending()->count(),
            'paid_bills' => $query->clone()->paid()->count(),
            'rejected_bills' => $query->clone()->rejected()->count(),
            'overdue_bills' => $query->clone()->overdue()->count(),
            'total_amount' => $query->sum('amount'),
            'paid_amount' => $query->clone()->paid()->sum('amount'),
            'pending_amount' => $query->clone()->pending()->sum('amount'),
            'unpaid_amount' => $query->clone()->unpaid()->sum('amount'),
        ];
    }
}