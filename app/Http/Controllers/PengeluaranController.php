<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengeluaranRequest;
use App\Http\Resources\pengeluaran\PengeluaranListResource;
use App\Http\Resources\pengeluaran\PengeluaranDetailResource;
use App\Models\PengeluaranModel;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

class PengeluaranController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of pengeluaran (list + search + filter + pagination + sort)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = PengeluaranModel::query();

            // Search by nama_pengeluaran
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('nama_pengeluaran', 'like', "%{$search}%");
            }

            // Filter by kategori
            if ($request->has('kategori')) {
                $query->where('kategori', $request->kategori);
            }

            // Filter by date range
            if ($request->has('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }
            if ($request->has('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'tanggal');
            $sortOrder = $request->get('sort_order', 'desc');

            $pengeluaran = $query->orderBy($sortBy, $sortOrder)
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
     * Store a newly created pengeluaran
     */
    public function store(PengeluaranRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            // Upload bukti_pengeluaran jika ada
            if ($request->hasFile('bukti_pengeluaran')) {
                $path = $request->file('bukti_pengeluaran')->store('pengeluaran', 'public');
                $data['bukti_pengeluaran'] = basename($path);
            }

            $pengeluaran = PengeluaranModel::create($data);

            return $this->createdResponse(
                new PengeluaranDetailResource($pengeluaran),
                'Pengeluaran created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create pengeluaran', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified pengeluaran
     */
    public function show(string $id): JsonResponse
    {
        try {
            $pengeluaran = PengeluaranModel::findOrFail($id);

            return $this->successResponse(
                new PengeluaranDetailResource($pengeluaran),
                'Pengeluaran retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Pengeluaran not found');
        }
    }
}
