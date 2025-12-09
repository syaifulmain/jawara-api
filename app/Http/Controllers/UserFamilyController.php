<?php

namespace App\Http\Controllers;

use App\Http\Resources\family\FamilyDetailResource;
use App\Http\Requests\StoreFamilyMemberRequest;
use App\Models\ResidentModel as Resident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;
use Exception;

class UserFamilyController extends Controller
{
    use ApiResponse;

    /**
     * GET - Menampilkan data keluarga user
     */
    public function myFamily(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->errorResponse('Unauthorized', 401);
            }

            $resident = $user->resident;

            if (!$resident) {
                return $this->notFoundResponse('User tidak memiliki data resident');
            }

            $family = $resident->family()
                ->with(['headResident', 'addressHistory.address', 'residents'])
                ->first();

            // Kalau belum punya keluarga, tampilkan user sebagai kepala
            if (!$family) {
                return $this->successResponse([
                    'family_id' => null,
                    'family_name' => $resident->full_name . ' Family',
                    'family_head' => $resident->full_name,
                    'members' => [
                        [
                            'id' => $resident->id,
                            'name' => $resident->full_name,
                            'role' => 'Kepala Keluarga',
                        ]
                    ]
                ], 'User belum memiliki keluarga');
            }

            return $this->successResponse(
                new FamilyDetailResource($family),
                'Family retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Failed to retrieve family data',
                500,
                $e->getMessage()
            );
        }
    }

    /**
     * POST - Tambah anggota keluarga
     */
    public function store(StoreFamilyMemberRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $resident = $user->resident;

            if (!$resident) {
                return $this->notFoundResponse('User tidak memiliki keluarga');
            }

            $familyId = $resident->family_id;

            $data = $request->validated();
            $data['family_id'] = $familyId; // Set family_id dari user yang sedang login

            $member = Resident::create($data);

            return $this->successResponse(
                $member,
                'Anggota keluarga berhasil ditambahkan'
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                'Gagal menambah anggota keluarga',
                500,
                $e->getMessage()
            );
        }
    }
}
