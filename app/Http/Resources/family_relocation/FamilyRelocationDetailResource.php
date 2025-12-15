<?php

namespace App\Http\Resources\family_relocation;

use App\Models\FamilyRelocation;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Request;

class FamilyRelocationDetailResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'relocation_type' => $this->relocation_type,
            'relocation_date' => $this->relocation_date,
            'reason' => $this->reason,
            'family_name' => $this->family->name,
            'past_address' => $this->pastAddress->address,
            'new_address' => $this->newAddress->address,
            'created_by' => $this->createdBy->name
        ];
    }
}
