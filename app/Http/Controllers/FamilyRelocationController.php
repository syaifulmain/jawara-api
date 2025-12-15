<?php

namespace App\Http\Controllers;

use App\Http\Requests\FamilyRelocationRequest;
use App\Http\Resources\family_relocation\FamilyRelocationDetailResource;
use App\Http\Resources\family_relocation\FamilyRelocationListResource;
use App\Models\FamilyAddressHistoryModel;
use App\Models\FamilyModel;
use App\Models\FamilyRelocation;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use DomainException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function store(FamilyRelocationRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            if (!Auth::check()) {
                throw new AuthenticationException();
            }

            $data = $request->validated();
            $data['created_by'] = Auth::id();


            // $relocationDate = Carbon::parse($data['relocation_date']);


            $currentHistory =  FamilyModel::find($data['family_id'])->currentAddress();

            $data['past_address_id'] = $currentHistory?->id;

            if ($currentHistory) {
                $currentHistory->update([
                    'moved_out_at' => $data['relocation_date'],
                ]);
            } else {
                throw new DomainException('Family has no current address');
            }

            if (!empty($data['new_address_id'])) {
                FamilyAddressHistoryModel::create([
                    'family_id' => $data['family_id'],
                    'address_id' => $data['new_address_id'],
                    'status' => 'owner',
                    'moved_in_at' => $data['relocation_date'],
                ]);
            }

            $relocation = FamilyRelocation::create($data);

            DB::commit();

            $relocation->load([
                'family.headResident',
                'pastAddress',
                'newAddress'
            ]);

            return $this->successResponse(
                new FamilyRelocationDetailResource($relocation),
                'Family relocation created successfully',
                201
            );
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e); // ⬅️ WAJIB saat debug

            return $this->errorResponse(
                'Failed to create family relocation',
                500,
                $e->getMessage()
            );
        }
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
