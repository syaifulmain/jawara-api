<?php

namespace App\Http\Controllers;

use App\Http\Resources\income\IncomeListResource;
use App\Http\Resources\income\IncomeDetailResource;
use App\Http\Requests\IncomeRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\IncomeModel;
use Exception;

class IncomeController extends Controller
{
     use ApiResponse;

    /**
     * Display a listing of incomes
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = IncomeModel::query();

            $incomes = $query->orderBy('date', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                IncomeListResource::collection($incomes),
                'Incomes retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve incomes', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $income = IncomeModel::findOrFail($id);

            return $this->successResponse(
                new IncomeDetailResource($income),
                'Income retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Income not found');
        }
    }

    /**
     * Store a newly created income in storage.
     */
    public function store(IncomeRequest $request): JsonResponse
    {
        try {
            $income = IncomeModel::create($request->validated());

            return $this->successResponse(
                new IncomeDetailResource($income),
                'Income created successfully',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create income', 500, $e->getMessage());
        }
    }

    /**
     * Update the specified income in storage.
     */
    public function update(IncomeRequest $request, int $id): JsonResponse
    {
        try {
            $income = IncomeModel::findOrFail($id);
            $income->update($request->validated());

            return $this->successResponse(
                new IncomeDetailResource($income),
                'Income updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update income', 500, $e->getMessage());
        }
    }
}
