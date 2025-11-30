<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ResidentEditRequest;
use App\Http\Resources\address\AddressDetailResource;
use App\Http\Resources\address\AddressListResource;
use App\Models\AddressModel;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = AddressModel::with('familyHistory');

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('address', 'like', "%{$search}%");
            }

            // Filter by occupied/empty status
            if ($request->has('is_occupied')) {
                $isOccupied = $request->boolean('is_occupied');
                if ($isOccupied) {
                    $query->whereHas('familyHistory', function ($q) {
                        $q->whereNull('moved_out_at');
                    });
                } else {
                    $query->whereDoesntHave('familyHistory', function ($q) {
                        $q->whereNull('moved_out_at');
                    });
                }
            }

            $addresses = $query->orderBy('address')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                AddressListResource::collection($addresses),
                'Addresses retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve addresses', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $address = AddressModel::with('familyHistory')->findOrFail($id);

            return $this->successResponse(
                new AddressDetailResource($address),
                'Address retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Address not found');
        }
    }

    public function store(AddressRequest $request): JsonResponse
    {
        try {
            $address = AddressModel::create($request->validated());

            return $this->successResponse(
                new AddressDetailResource($address),
                'Address created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create address', 500, $e->getMessage());
        }

    }
}
