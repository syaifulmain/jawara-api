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
            'relocation_type' => $this->relocation_type,
            'relocation_date' => $this->relocation_date,
            'reason' => $this->reason,
            'family_id' => $this->family_id,
            'past_address' => $this->past_address,
            'new_address' => $this->new_address,
            'created_by' => $this->created_by
        ];
    }
}
