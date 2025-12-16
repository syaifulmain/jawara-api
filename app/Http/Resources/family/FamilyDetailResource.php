<?php

namespace App\Http\Resources\family;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FamilyDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentAddress = $this->currentAddress();

        return [
            'id' => $this->id,
            'family_name' => $this->name,
            'family_head' => $this->headResident?->full_name,
            'current_address' => $currentAddress?->address?->address,
            'ownership_status' => $currentAddress ? ucfirst($currentAddress->status) : null,
            'family_status' => $this->is_active ? 'Aktif' : 'Tidak Aktif',
            'family_members' => $this->residents->map(function ($resident) {
                return [
                    'id' => $resident->id,
                    'name' => $resident->full_name,
                    'nik' => $resident->nik,
                    'role' => $resident->family_role,
                    'gender' => $resident->gender === 'M' ? 'Laki-laki' : 'Perempuan',
                    'birth_date' => $resident->birth_date?->format('Y-m-d'),
                    'status' => $resident->is_alive ? 'Hidup' : 'Meninggal',
                ];
            }),
        ];
    }
}
