<?php

namespace App\Http\Controllers;

use App\Http\Requests\BillRequest;
use App\Http\Requests\GenerateBillRequest;
use App\Http\Resources\BillResource;
use App\Models\BillModel;
use App\Services\BillService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    use ApiResponse;

    protected BillService $billService;

    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

    /**
     * Display a listing of bills
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = BillModel::with(['family', 'incomeCategory', 'creator', 'verifier']);

            // Filter by family
            if ($request->has('family_id')) {
                $query->where('family_id', $request->family_id);
            }

            // Filter by income category
            if ($request->has('income_category_id')) {
                $query->where('income_category_id', $request->income_category_id);
            }

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            // Filter by periode
            if ($request->has('periode')) {
                $query->whereDate('periode', $request->periode);
            }

            // Filter by periode range
            if ($request->has('periode_from')) {
                $query->whereDate('periode', '>=', $request->periode_from);
            }
            if ($request->has('periode_to')) {
                $query->whereDate('periode', '<=', $request->periode_to);
            }

            // Search by code or family name
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('code', 'like', "%{$search}%")
                        ->orWhereHas('family', function ($fq) use ($search) {
                            $fq->where('name', 'like', "%{$search}%");
                        })
                        ->orWhereHas('incomeCategory', function ($iq) use ($search) {
                            $iq->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $bills = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                BillResource::collection($bills),
                'Bills retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve bills', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created bill
     */
    public function store(BillRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by'] = Auth::id();
            
            // Generate unique code if not provided
            if (!isset($data['code'])) {
                $data['code'] = $this->generateUniqueCode();
            }

            $bill = BillModel::create($data);
            $bill->load(['family', 'incomeCategory', 'creator']);

            return $this->createdResponse(
                new BillResource($bill),
                'Bill created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create bill', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified bill
     */
    public function show(string $id): JsonResponse
    {
        try {
            $bill = BillModel::with(['family', 'incomeCategory', 'creator', 'verifier'])
                ->findOrFail($id);

            return $this->successResponse(
                new BillResource($bill),
                'Bill retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Bill not found');
        }
    }

    /**
     * Update the specified bill
     */
    public function update(BillRequest $request, string $id): JsonResponse
    {
        try {
            $bill = BillModel::findOrFail($id);
            $bill->update($request->validated());
            $bill->load(['family', 'incomeCategory', 'creator', 'verifier']);

            return $this->successResponse(
                new BillResource($bill),
                'Bill updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update bill', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified bill
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $bill = BillModel::findOrFail($id);
            $bill->delete();

            return $this->successResponse(null, 'Bill deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete bill', 500, $e->getMessage());
        }
    }

    /**
     * Generate bills for all active families
     */
    public function generateBills(GenerateBillRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            
            if (isset($data['income_category_ids'])) {
                // Generate for multiple categories
                $results = $this->billService->generateBillsForCategories(
                    $data['income_category_ids'],
                    $data['periode']
                );
                
                // Count total generated
                $totalGenerated = 0;
                $allErrors = [];
                foreach ($results as $result) {
                    if ($result['success']) {
                        $totalGenerated += $result['generated_count'];
                        if (!empty($result['skipped_families'])) {
                            $allErrors = array_merge($allErrors, $result['skipped_families']);
                        }
                    }
                }
                
                return $this->successResponse([
                    'total_generated' => $totalGenerated,
                    'categories_processed' => count($data['income_category_ids']),
                    'results' => $results,
                    'warnings' => $allErrors
                ], 'Bills generated successfully');
            } else {
                // Generate for single category
                $result = $this->billService->generateBillsForCategory(
                    $data['income_category_id'],
                    $data['periode']
                );
                
                if ($result['success']) {
                    return $this->successResponse($result, 'Bills generated successfully');
                } else {
                    return $this->errorResponse('Failed to generate bills', 500, $result['error']);
                }
            }
        } catch (Exception $e) {
            return $this->errorResponse('Failed to generate bills', 500, $e->getMessage());
        }
    }

    /**
     * Upload payment proof by user (unpaid/rejected -> pending)
     */
    public function uploadPaymentProof(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'payment_proof' => 'required|string|max:255',
            ]);

            $result = $this->billService->uploadPaymentProof($id, $request->payment_proof);
            
            if ($result['success']) {
                return $this->successResponse(
                    new BillResource($result['bill']),
                    $result['message']
                );
            } else {
                return $this->errorResponse($result['message'], 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Failed to upload payment proof', 500, $e->getMessage());
        }
    }

    /**
     * Approve payment by admin (pending -> paid)
     */
    public function approvePayment(string $id): JsonResponse
    {
        try {
            $result = $this->billService->approvePayment($id);
            
            if ($result['success']) {
                return $this->successResponse(
                    new BillResource($result['bill']),
                    $result['message']
                );
            } else {
                return $this->errorResponse($result['message'], 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Failed to approve payment', 500, $e->getMessage());
        }
    }
    
    /**
     * Reject payment by admin (pending -> rejected)
     */
    public function rejectPayment(Request $request, string $id): JsonResponse
    {
        try {
            $request->validate([
                'rejection_reason' => 'required|string|max:500',
            ]);
            
            $result = $this->billService->rejectPayment($id, $request->rejection_reason);
            
            if ($result['success']) {
                return $this->successResponse(
                    new BillResource($result['bill']),
                    $result['message']
                );
            } else {
                return $this->errorResponse($result['message'], 400);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Failed to reject payment', 500, $e->getMessage());
        }
    }

    /**
     * Get payment statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $periode = $request->get('periode');
            $stats = $this->billService->getPaymentStatistics($periode);
            
            return $this->successResponse($stats, 'Payment statistics retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve statistics', 500, $e->getMessage());
        }
    }

    /**
     * Mark overdue bills
     */
    public function markOverdue(): JsonResponse
    {
        try {
            $result = $this->billService->markOverdueBills();
            
            return $this->successResponse($result, 'Overdue bills updated successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update overdue bills', 500, $e->getMessage());
        }
    }

    /**
     * Generate unique bill code
     */
    private function generateUniqueCode(): string
    {
        do {
            $code = 'IR' . now()->format('Y') . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (BillModel::where('code', $code)->exists());
        
        return $code;
    }
}
