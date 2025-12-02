<?php

namespace App\Http\Resources\income;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomeListResource extends JsonResource
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
            'date' => $this->date,
            'amount' => $this->amount,
            'date_verification' => $this->date_verification,
            'verification' => $this->verification,
        ];

    }

}
