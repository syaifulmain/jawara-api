<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeCategoryRequest;
use App\Http\Resources\IncomeCategoryResource;
use App\Models\IncomeCategory;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of income categories
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = IncomeCategory::with('creator');

            // Filter by type
            if ($request->has('type')) {
                $query->ofType($request->type);
            }

            // Search by name or description
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by created_by
            if ($request->has('created_by')) {
                $query->where('created_by', $request->created_by);
            }

            $categories = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                IncomeCategoryResource::collection($categories),
                'Income categories retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve income categories', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created income category
     */
    public function store(IncomeCategoryRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by'] = Auth::id();

            $category = IncomeCategory::create($data);
            $category->load('creator');

            return $this->createdResponse(
                new IncomeCategoryResource($category),
                'Income category created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create income category', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified income category
     */
    public function show(string $id): JsonResponse
    {
        try {
            $category = IncomeCategory::with('creator')->findOrFail($id);

            return $this->successResponse(
                new IncomeCategoryResource($category),
                'Income category retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Income category not found');
        }
    }

    /**
     * Update the specified income category
     */
    public function update(IncomeCategoryRequest $request, string $id): JsonResponse
    {
        try {
            $category = IncomeCategory::findOrFail($id);
            $category->update($request->validated());
            $category->load('creator');

            return $this->successResponse(
                new IncomeCategoryResource($category),
                'Income category updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update income category', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified income category
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $category = IncomeCategory::findOrFail($id);
            $category->delete();

            return $this->successResponse(null, 'Income category deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete income category', 500, $e->getMessage());
        }
    }

    /**
     * Get income category types
     */
    public function types(): JsonResponse
    {
        try {
            $types = [
                [
                    'value' => 'bulanan',
                    'label' => 'Iuran Bulanan'
                ],
                [
                    'value' => 'mingguan', 
                    'label' => 'Iuran Mingguan'
                ],
                [
                    'value' => 'tahunan',
                    'label' => 'Iuran Tahunan'
                ],
                [
                    'value' => 'sekali_bayar',
                    'label' => 'Sekali Bayar'
                ]
            ];

            return $this->successResponse($types, 'Income category types retrieved successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve income category types', 500, $e->getMessage());
        }
    }
}
