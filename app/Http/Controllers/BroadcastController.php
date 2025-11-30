<?php

namespace App\Http\Controllers;

use App\Http\Requests\BroadcastRequest;
use App\Http\Resources\BroadcastResource;
use App\Models\BroadcastModel;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class BroadcastController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = BroadcastModel::with('creator');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('message', 'like', "%{$search}%");
                });
            }

            if ($request->has('date_from')) {
                $query->whereDate('published_at', '>=', $request->date_from);
            }

            if ($request->has('date_to')) {
                $query->whereDate('published_at', '<=', $request->date_to);
            }

            $broadcasts = $query->orderBy('published_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                BroadcastResource::collection($broadcasts),
                'Broadcasts retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve broadcasts', 500, $e->getMessage());
        }
    }

    public function store(BroadcastRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $data['created_by'] = auth()->id();
            $data['published_at'] = $data['published_at'] ?? now();

            if ($request->hasFile('photo')) {
                $data['photo'] = $request->file('photo')->store('broadcasts/photos', 'public');
            }

            if ($request->hasFile('document')) {
                $data['document'] = $request->file('document')->store('broadcasts/documents', 'public');
            }

            $broadcast = BroadcastModel::create($data);

            return $this->createdResponse(
                new BroadcastResource($broadcast->load('creator')),
                'BroadcastModel created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create broadcast', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $broadcast = BroadcastModel::with('creator')->findOrFail($id);

            return $this->successResponse(
                new BroadcastResource($broadcast),
                'BroadcastModel retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('BroadcastModel not found');
        }
    }

    public function update(BroadcastRequest $request, string $id): JsonResponse
    {
        try {
            $broadcast = BroadcastModel::findOrFail($id);
            $data = $request->validated();

//            if ($request->hasFile('photo')) {
//                if ($broadcast->photo) {
//                    Storage::disk('public')->delete($broadcast->photo);
//                }
//                $data['photo'] = $request->file('photo')->store('broadcasts/photos', 'public');
//            }
//
//            if ($request->hasFile('document')) {
//                if ($broadcast->document) {
//                    Storage::disk('public')->delete($broadcast->document);
//                }
//                $data['document'] = $request->file('document')->store('broadcasts/documents', 'public');
//            }

            $broadcast->update($data);

            return $this->successResponse(
                new BroadcastResource($broadcast->load('creator')),
                'BroadcastModel updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update broadcast', 500, $e->getMessage());
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $broadcast = BroadcastModel::findOrFail($id);

            if ($broadcast->photo) {
                Storage::disk('public')->delete($broadcast->photo);
            }

            if ($broadcast->document) {
                Storage::disk('public')->delete($broadcast->document);
            }

            $broadcast->delete();

            return $this->successResponse(null, 'BroadcastModel deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete broadcast', 500, $e->getMessage());
        }
    }

    public function downloadPhoto(string $id)
    {
        try {
            $broadcast = BroadcastModel::findOrFail($id);

            if (!$broadcast->photo || !Storage::disk('public')->exists($broadcast->photo)) {
                return $this->notFoundResponse('Photo not found');
            }

            return Storage::disk('public')->download($broadcast->photo);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to download photo', 500, $e->getMessage());
        }
    }

    public function downloadDocument(string $id)
    {
        try {
            $broadcast = BroadcastModel::findOrFail($id);

            if (!$broadcast->document || !Storage::disk('public')->exists($broadcast->document)) {
                return $this->notFoundResponse('Document not found');
            }

            return Storage::disk('public')->download($broadcast->document);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to download document', 500, $e->getMessage());
        }
    }
}
