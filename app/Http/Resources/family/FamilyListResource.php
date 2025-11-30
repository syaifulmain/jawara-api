<?php

namespace App\Http\Resources\family;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentAddress = $this->currentAddress();

        return [
            'id' => $this->id,
            'nama_keluarga' => $this->name,
            'kepala_keluarga' => $this->headResident?->full_name ?? '-',
            'alamat_rumah' => $currentAddress?->address?->address ?? '-',
            'status_kepemilikan' => $currentAddress ? ucfirst($currentAddress->status) : '-',
            'status' => $this->is_active ? 'Aktif' : 'Tidak Aktif',
        ];
    }
}
