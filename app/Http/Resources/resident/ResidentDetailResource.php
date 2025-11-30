<?php

namespace App\Http\Resources\resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentDetailResource extends JsonResource
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
            'family_id' => $this->family_id,
            'name' => $this->full_name,
            'nik' => $this->nik,
            'phone_number' => $this->phone_number,
            'birth_place' => $this->birth_place,
            'birth_date' => optional($this->birth_date)->toIso8601String(),
            'gender' => $this->gender,
            'religion' => $this->religion,
            'blood_type' => $this->blood_type,
            'family_role' => $this->family_role,
            'last_education' => $this->last_education,
            'occupation' => $this->occupation,
            'is_family_head' => $this->is_family_head,
            'is_alive' => $this->is_alive,
            'is_active' => $this->is_active,
        ];
    }
}
