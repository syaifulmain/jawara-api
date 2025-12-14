<?php

namespace App\Http\Controllers;

use App\Http\Resources\family_relocation\FamilyRelocationDetailResource;
use App\Http\Resources\family_relocation\FamilyRelocationListResource;
use App\Models\FamilyRelocation;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FamilyRelocationController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = FamilyRelocation::with([
                'family.headResident',
                'pastAddress',
                'newAddress'
            ]);

            // Search by family name or head resident name
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('family', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhereHas('headResident', function ($q) use ($search) {
                                $q->where('full_name', 'like', "%{$search}%");
                            });
                    })
                        ->orWhere('reason', 'like', "%{$search}%");
                });
            }

            // Filter by relocation type
            if ($request->has('relocation_type')) {
                $query->where('relocation_type', $request->relocation_type);
            }

            // Filter by family
            if ($request->has('family_id')) {
                $query->where('family_id', $request->family_id);
            }

            // Filter by past address
            // if ($request->has('past_address_id')) {
            //     $query->where('past_address_id', $request->past_address_id);
            // }

            // // Filter by new address
            // if ($request->has('new_address_id')) {
            //     $query->where('new_address_id', $request->new_address_id);
            // }

            // Filter by date range
            // if ($request->has('start_date')) {
            //     $query->whereDate('relocation_date', '>=', $request->start_date);
            // }

            // if ($request->has('end_date')) {
            //     $query->whereDate('relocation_date', '<=', $request->end_date);
            // }

            $relocations = $query->orderBy('relocation_date', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                FamilyRelocationListResource::collection($relocations),
                'Family relocations retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve family relocations', 500, $e->getMessage());
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $relocation = FamilyRelocation::with([
                'family.headResident',
                'family.residents',
                'pastAddress',
                'newAddress'
            ])->findOrFail($id);

            return $this->successResponse(
                new FamilyRelocationDetailResource($relocation),
                'Family relocation retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Family relocation not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FamilyRelocation $familyRelocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FamilyRelocation $familyRelocation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FamilyRelocation $familyRelocation)
    {
        //
    }
}
