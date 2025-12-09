<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\user\UserDetailResource;
use App\Http\Resources\user\UserListResource;
use App\Http\Resources\UserResource;
use App\Models\FamilyAddressHistoryModel;
use App\Models\FamilyModel;
use App\Models\ResidentModel;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function store(StoreUserRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $identityPhotoPath = null;
            if ($request->hasFile('identity_photo')) {
                $identityPhotoPath = $request->file('identity_photo')
                    ->store('identity_photos', 'public');
            }
            // Create user
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'identity_photo' => $identityPhotoPath,
                'is_active' => true,
            ];

            $user = User::create($userData);

            // If role is 'user', create family and resident
            if ($request->role === User::ROLE_USER) {
                // Handle identity photo upload

                // Create family
                $family = FamilyModel::create([
                    'name' => 'Keluarga ' . $request->name,
                    'is_active' => true,
                ]);

                // Create resident as family head
                $resident = ResidentModel::create([
                    'user_id' => $user->id,
                    'family_id' => $family->id,
                    'full_name' => $request->name,
                    'nik' => $request->nik,
                    'phone_number' => $request->phone,
                    'birth_place' => $request->birth_place,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'religion' => $request->religion,
                    'blood_type' => $request->blood_type,
                    'family_role' => 'Kepala Keluarga',
                    'last_education' => $request->last_education,
                    'occupation' => $request->occupation,
                    'is_family_head' => true,
                    'is_alive' => true,
                    'is_active' => true,
                ]);

                // Update family head
                $family->update(['head_resident_id' => $resident->id]);

                // Create family address history
                FamilyAddressHistoryModel::create([
                    'family_id' => $family->id,
                    'address_id' => $request->address_id,
                    'status' => $request->status,
                    'moved_in_at' => now(),
                    'moved_out_at' => null,
                ]);

                // Load relationships
                $user->load(['resident.family.currentAddress.address']);
            }

            DB::commit();

            return $this->successResponse(
                new UserResource($user),
                'User created successfully',
                201
            );
        } catch (Exception $e) {
            DB::rollBack();

            // Delete uploaded photo if exists
            if (isset($identityPhotoPath) && $identityPhotoPath) {
                Storage::disk('public')->delete($identityPhotoPath);
            }

            return $this->errorResponse(
                'Failed to create user',
                500,
                $e->getMessage()
            );
        }
    }
}
