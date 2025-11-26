<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            // Role information
            'role' => $this->role,
//            'role_label' => $this->role_label,  // "Administrator", "Ketua RT", etc

            // Status information
            'is_active' => $this->is_active,
//            'status_label' => $this->status_label,  // "Active" or "Inactive"
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'available_roles' => \App\Models\User::availableRoles(),
            ],
        ];
    }
}
