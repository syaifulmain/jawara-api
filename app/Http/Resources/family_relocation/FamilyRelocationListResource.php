<?php

namespace App\Http\Resources\family_relocation;

use App\Models\FamilyRelocation;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Request;

class FamilyRelocationListResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'relocation_type' => $this->relocation_type,
            'relocation_date' => $this->relocation_date,
            'family_id' => $this->family_id,
        ];
    }
}
