<?php
namespace App\Http\Controllers;

use App\Http\Requests\FruitImageRequest;
use App\Http\Resources\FruitImageResource;
use App\Models\FruitImageModel;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class FruitImageController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = FruitImageModel::with('family');

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('family', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->has('family_id')) {
                $query->where('family_id', $request->family_id);
            }

            $fruitImages = $query->orderBy('created_at', 'desc')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                FruitImageResource::collection($fruitImages),
                'Fruit images retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve fruit images', 500, $e->getMessage());
        }
    }

    public function store(FruitImageRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if ($request->hasFile('file')) {
                $data['file'] = $request->file('file')->store('fruit-images', 'public');
            }

            $fruitImage = FruitImageModel::create($data);

            return $this->createdResponse(
                new FruitImageResource($fruitImage->load('family')),
                'Fruit image created successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create fruit image', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $fruitImage = FruitImageModel::with('family')->findOrFail($id);

            return $this->successResponse(
                new FruitImageResource($fruitImage),
                'Fruit image retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('Fruit image not found');
        }
    }

    public function update(FruitImageRequest $request, string $id): JsonResponse
    {
        try {
            $fruitImage = FruitImageModel::findOrFail($id);
            $data = $request->validated();

            if ($request->hasFile('file')) {
                if ($fruitImage->file) {
                    Storage::disk('public')->delete($fruitImage->file);
                }
                $data['file'] = $request->file('file')->store('fruit-images', 'public');
            }

            $fruitImage->update($data);

            return $this->successResponse(
                new FruitImageResource($fruitImage->load('family')),
                'Fruit image updated successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update fruit image', 500, $e->getMessage());
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $fruitImage = FruitImageModel::findOrFail($id);

            if ($fruitImage->file) {
                Storage::disk('public')->delete($fruitImage->file);
            }

            $fruitImage->delete();

            return $this->successResponse(null, 'Fruit image deleted successfully');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete fruit image', 500, $e->getMessage());
        }
    }

    public function download(string $id)
    {
        try {
            $fruitImage = FruitImageModel::findOrFail($id);

            if (!$fruitImage->file || !Storage::disk('public')->exists($fruitImage->file)) {
                return $this->notFoundResponse('File not found');
            }

            return Storage::disk('public')->download($fruitImage->file);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to download file', 500, $e->getMessage());
        }
    }
}