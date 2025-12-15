<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\ActivityModel;
use App\Models\PengeluaranModel;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    use ApiResponse;

    /**
     * Display a listing of activities
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = ActivityModel::query();

            // Filter by category
            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            // Search by name, location, or person in charge
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('person_in_charge', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            //filter tanggal kegiatan
            if ($request->has('date')) {
                $query->whereDate('date', $request->date);
            }

            // Filter by date range
            if ($request->has('date_from')) {
                $query->whereDate('date', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('date', '<=', $request->date_to);
            }

            $activities = $query->orderBy('date', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                ActivityResource::collection($activities),
                'Activities retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve activities', 500, $e->getMessage());
        }
    }

    /**
     * Store a newly created activity
     */
    public function store(ActivityRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $pengeluaranId = null;

            // Buat pengeluaran jika is_pengeluaran bernilai true
            if ($request->input('is_pengeluaran')) {
                $pengeluaranData = [
                    'nama_pengeluaran' => $request->input('nama_pengeluaran'),
                    'tanggal' => $request->input('date'),
                    'kategori' => $request->input('kategori', 'Lain-lain'),
                    'nominal' => $request->input('nominal'),
                    'verifikator' => $request->input('verifikator', 'Admin Jawara'),
                ];

                // Upload bukti_pengeluaran jika ada
                if ($request->hasFile('bukti_pengeluaran')) {
                    $path = $request->file('bukti_pengeluaran')->store('pengeluaran', 'public');
                    $pengeluaranData['bukti_pengeluaran'] = basename($path);
                }

                $pengeluaran = PengeluaranModel::create($pengeluaranData);
                $pengeluaranId = $pengeluaran->id;
            }

            // Buat aktivitas dengan atau tanpa pengeluaran_id
            $activityData = [
                'name' => $request->input('name'),
                'category' => $request->input('category'),
                'date' => $request->input('date'),
                'location' => $request->input('location'),
                'person_in_charge' => $request->input('person_in_charge'),
                'description' => $request->input('description'),
                'pengeluaran_id' => $pengeluaranId,
            ];

            $activity = ActivityModel::create($activityData);

            DB::commit();

            return $this->createdResponse(
                new ActivityResource($activity),
                'Activity created successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create activity', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified activity
     */
    public function show(string $id): JsonResponse
    {
        try {
            $activity = ActivityModel::with('pengeluaran')->findOrFail($id);

            return $this->successResponse(
                new ActivityResource($activity),
                'Activity retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Activity not found');
        }
    }

    /**
     * Update the specified activity
     */
    public function update(ActivityRequest $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $activity = ActivityModel::findOrFail($id);
            $pengeluaranId = $activity->pengeluaran_id;

            // Handle pengeluaran logic
            if ($request->input('is_pengeluaran')) {
                $pengeluaranData = [
                    'nama_pengeluaran' => $request->input('nama_pengeluaran'),
                    'tanggal' => $request->input('date'),
                    'kategori' => $request->input('kategori', 'Lain-lain'),
                    'nominal' => $request->input('nominal'),
                    'verifikator' => $request->input('verifikator', 'Admin Jawara'),
                ];

                // Upload bukti_pengeluaran jika ada
                if ($request->hasFile('bukti_pengeluaran')) {
                    $path = $request->file('bukti_pengeluaran')->store('pengeluaran', 'public');
                    $pengeluaranData['bukti_pengeluaran'] = basename($path);
                }

                if ($pengeluaranId) {
                    // Update existing pengeluaran
                    $pengeluaran = PengeluaranModel::findOrFail($pengeluaranId);
                    $pengeluaran->update($pengeluaranData);
                } else {
                    // Create new pengeluaran
                    $pengeluaran = PengeluaranModel::create($pengeluaranData);
                    $pengeluaranId = $pengeluaran->id;
                }
            } else {
                // Jika is_pengeluaran false dan ada pengeluaran sebelumnya, hapus
                if ($pengeluaranId) {
                    PengeluaranModel::destroy($pengeluaranId);
                    $pengeluaranId = null;
                }
            }

            // Update aktivitas
            $activity->update([
                'name' => $request->input('name'),
                'category' => $request->input('category'),
                'date' => $request->input('date'),
                'location' => $request->input('location'),
                'person_in_charge' => $request->input('person_in_charge'),
                'description' => $request->input('description'),
                'pengeluaran_id' => $pengeluaranId,
            ]);

            DB::commit();

            return $this->successResponse(
                new ActivityResource($activity->fresh()),
                'Activity updated successfully'
            );
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to update activity', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified activity
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $activity = ActivityModel::findOrFail($id);

            // Hapus pengeluaran terkait jika ada
            if ($activity->pengeluaran_id) {
                PengeluaranModel::destroy($activity->pengeluaran_id);
            }

            $activity->delete();

            DB::commit();

            return $this->successResponse(null, 'Activity deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to delete activity', 500, $e->getMessage());
        }
    }

    public function getActivityInThisMonth(): JsonResponse
    {
        try {
            $activities = ActivityModel::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->orderBy('date', 'desc')
                ->get();

            return $this->successResponse(
                ActivityResource::collection($activities),
                'Activities for this month retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve activities for this month', 500, $e->getMessage());
        }
    }
}