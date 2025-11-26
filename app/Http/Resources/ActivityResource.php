<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
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
            'category' => $this->category,
            'date' => optional($this->date)->toIso8601String(),
            'location' => $this->location,
            'person_in_charge' => $this->person_in_charge,
            'description' => $this->description
        ];
    }
}
