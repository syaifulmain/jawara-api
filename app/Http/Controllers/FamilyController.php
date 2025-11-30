<?php

namespace App\Http\Controllers;

use App\Http\Resources\family\FamilyDetailResource;
use App\Http\Resources\family\FamilyListResource;
use App\Models\FamilyModel;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = FamilyModel::with(['headResident', 'addressHistory.address']);

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('headResident', function ($q) use ($search) {
                            $q->where('full_name', 'like', "%{$search}%");
                        });
                });
            }

            // Filter by is_active
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Filter by address
            if ($request->has('address_id')) {
                $query->whereHas('addressHistory', function ($q) use ($request) {
                    $q->where('address_id', $request->address_id)
                        ->whereNull('moved_out_at');
                });
            }

            // Filter by ownership status
            if ($request->has('status')) {
                $query->whereHas('addressHistory', function ($q) use ($request) {
                    $q->where('status', $request->status)
                        ->whereNull('moved_out_at');
                });
            }

            $families = $query->orderBy('name')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                FamilyListResource::collection($families),
                'Families retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve families', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $family = FamilyModel::with(['headResident', 'addressHistory.address', 'residents'])
                ->findOrFail($id);

            return $this->successResponse(
                new FamilyDetailResource($family),
                'Family retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Family not found');
        }
    }

}
