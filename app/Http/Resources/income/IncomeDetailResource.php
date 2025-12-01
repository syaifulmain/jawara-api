<?php

namespace App\Http\Resources\income;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeDetailResource extends JsonResource
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
            'income_type' => $this->income_type,
            'date' => optional($this->date)->toIso8601String(),
            'amount' => $this->amount,
            'date_verification' => optional($this->date_verification)->toIso8601String(),
            'verification' => $this->verification,
            'created_at' => optional($this->created_at)->toIso8601String(),
            'updated_at' => optional($this->updated_at)->toIso8601String(),
        ];
    }
}
