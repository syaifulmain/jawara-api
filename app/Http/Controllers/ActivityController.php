<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\ActivityModel;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Exception;

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
            $activity = ActivityModel::create($request->validated());

            return $this->createdResponse(
                new ActivityResource($activity),
                'Activity created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create activity', 500, $e->getMessage());
        }
    }

    /**
     * Display the specified activity
     */
    public function show(string $id): JsonResponse
    {
        try {
            $activity = ActivityModel::findOrFail($id);

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
            $activity = ActivityModel::findOrFail($id);
            $activity->update($request->validated());

            return $this->successResponse(
                new ActivityResource($activity),
                'Activity updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update activity', 500, $e->getMessage());
        }
    }

    /**
     * Remove the specified activity
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $activity = ActivityModel::findOrFail($id);
            $activity->delete();

            return $this->successResponse(null, 'Activity deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete activity', 500, $e->getMessage());
        }
    }
}
