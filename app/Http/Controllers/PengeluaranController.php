<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PengeluaranRequest;
use App\Http\Resources\pengeluaran\PengeluaranListResource;
use App\Http\Resources\pengeluaran\PengeluaranDetailResource;
use App\Models\Pengeluaran;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PengeluaranController extends Controller
{
    use ApiResponse;

    /**
     * GET LIST + FILTER + SEARCH
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pengeluaran::query();

            // Search by nama_pengeluaran
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('nama_pengeluaran', 'like', "%{$search}%");
            }

            // Filter kategori
            if ($request->has('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter range tanggal
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('tanggal', [
                    $request->start_date,
                    $request->end_date
                ]);
            }

            // Pagination
            $pengeluaran = $query->orderBy('tanggal', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                PengeluaranListResource::collection($pengeluaran),
                'Pengeluaran retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve pengeluaran', 500, $e->getMessage());
        }
    }

    /**
     * GET DETAIL BY ID
     */
    public function show(string $id): JsonResponse
    {
        try {
            $pengeluaran = Pengeluaran::findOrFail($id);

            return $this->successResponse(
                new PengeluaranDetailResource($pengeluaran),
                'Pengeluaran retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Pengeluaran not found');
        }
    }

    /**
     * STORE (CREATE)
     */
    public function store(PengeluaranRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Upload file jika ada
            if ($request->hasFile('bukti_pengeluaran')) {
                $path = $request->file('bukti_pengeluaran')->store('pengeluaran', 'public');
                $data['bukti_pengeluaran'] = basename($path);
            }

            $pengeluaran = Pengeluaran::create($data);

            return $this->successResponse(
                new PengeluaranDetailResource($pengeluaran),
                'Pengeluaran created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create pengeluaran', 500, $e->getMessage());
        }
    }
}
