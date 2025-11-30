<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResidentEditRequest;
use App\Http\Resources\resident\ResidentDetailResource;
use App\Http\Resources\resident\ResidentListResource;
use App\Models\ResidentModel;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = ResidentModel::with(['family', 'family.addressHistory.address']);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                        ->orWhere('nik', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            }

            // Filter by gender
            if ($request->has('gender')) {
                $query->where('gender', $request->gender);
            }

            // Filter by status hidup
            if ($request->has('is_alive')) {
                $query->where('is_alive', $request->boolean('is_alive'));
            }

            // Filter by status aktif
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Filter by family
            if ($request->has('family_id')) {
                $query->where('family_id', $request->family_id);
            }

            // Filter by is_family_head
            if ($request->has('is_family_head')) {
                $query->where('is_family_head', $request->boolean('is_family_head'));
            }

            $residents = $query->orderBy('full_name')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                ResidentListResource::collection($residents),
                'Residents retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve residents', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $resident = ResidentModel::findOrFail($id);

            return $this->successResponse(
                new ResidentDetailResource($resident),
                'Resident retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Resident not found');
        }
    }

    public function store(ResidentEditRequest $request): JsonResponse
    {
        try {
            $resident = ResidentModel::create($request->validated());

            return $this->successResponse(
                new ResidentDetailResource($resident),
                'Resident created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create resident', 500, $e->getMessage());
        }
    }

    public function update(ResidentEditRequest $request, int $id): JsonResponse
    {
        try {
            $resident = ResidentModel::findOrFail($id);
            $resident->update($request->validated());

            return $this->successResponse(
                new ResidentDetailResource($resident),
                'Resident updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update resident', 500, $e->getMessage());
        }

    }
}
