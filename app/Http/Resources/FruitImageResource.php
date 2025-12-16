<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FruitImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'family_id' => $this->family_id,
            'family' => $this->when($this->relationLoaded('family'), [
                'id' => $this->family?->id,
                'name' => $this->family?->name,
            ]),
            'file' => $this->file,
            'file_url' => $this->file ? asset('storage/' . $this->file) : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}