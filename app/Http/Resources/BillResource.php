<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
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
            'code' => $this->code,
            'periode' => $this->periode?->toDateString(),
            'periode_label' => $this->periode_label,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'status' => $this->status,
            'status_label' => $this->status_label,
            'payment_proof' => $this->payment_proof,
            'paid_at' => $this->paid_at?->toIso8601String(),
            'verified_at' => $this->verified_at?->toIso8601String(),
            'notes' => $this->notes,
            
            // Family information
            'family' => $this->when($this->relationLoaded('family'), [
                'id' => $this->family?->id,
                'name' => $this->family?->name,
                'is_active' => $this->family?->is_active,
            ]),
            
            // Income Category information
            'income_category' => $this->when($this->relationLoaded('incomeCategory'), [
                'id' => $this->incomeCategory?->id,
                'name' => $this->incomeCategory?->name,
                'type' => $this->incomeCategory?->type,
                'type_label' => $this->incomeCategory?->type_label,
            ]),
            
            // Creator information
            'created_by' => $this->when($this->relationLoaded('creator'), [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
            ]),
            
            // Verifier information
            'verified_by' => $this->when($this->relationLoaded('verifier'), [
                'id' => $this->verifier?->id,
                'name' => $this->verifier?->name,
            ]),
            
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
