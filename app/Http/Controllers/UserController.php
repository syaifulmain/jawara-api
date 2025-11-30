<?php

namespace App\Http\Controllers;

use App\Http\Resources\user\UserDetailResource;
use App\Http\Resources\user\UserListResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        try {
            $query = User::query();

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Filter by is_active
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            $users = $query->orderBy('name')
                ->paginate($request->get('per_page', 15));

            return $this->successResponse(
                UserListResource::collection($users),
                'Users retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve users', 500, $e->getMessage());
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            return $this->successResponse(
                new UserDetailResource($user),
                'User retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->notFoundResponse('User not found');
        }
    }
}
