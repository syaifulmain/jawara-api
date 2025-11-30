<?php

namespace App\Http\Resources\address;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $occupiedCount = $this->currentFamilies()->count();

        return [
            'id' => $this->id,
            'alamat' => $this->address,
            'status' => $occupiedCount > 0 ? 'Ditempati' : 'Kosong'
        ];
    }
}
