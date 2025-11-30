<?php

namespace App\Http\Resources\resident;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResidentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentAddress = $this->family?->currentAddress();

        return [
            'id' => $this->id,
            'nama' => $this->full_name,
            'nik' => $this->nik,
            'keluarga' => $this->family?->name,
            'jenis_kelamin' => $this->gender === 'M' ? 'Laki-laki' : 'Perempuan',
            'status_domisili' => $currentAddress ? $currentAddress->status : '-',
            'status_hidup' => $this->is_alive ? 'Hidup' : 'Wafat',
            'is_active' => $this->is_active,
        ];
    }
}
