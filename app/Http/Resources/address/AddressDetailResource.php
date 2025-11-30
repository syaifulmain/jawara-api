<?php

namespace App\Http\Resources\address;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $currentFamilies = $this->familyHistory()->whereNull('moved_out_at')->count();

        return [
            'address' => $this->address,
            'status' => $currentFamilies > 0 ? 'Ditempati' : 'Kosong',
            'history' => $this->familyHistory->map(function ($history) {
                return [
                    'family' => $history->family?->name,
                    'head_resident' => $history->family?->headResident?->full_name,
                    'moved_in_at' => $history->moved_in_at?->format('Y-m-d'),
                    'moved_out_at' => $history->moved_out_at ? $history->moved_out_at->format('Y-m-d') : 'Masih tinggal',
                ];
            }),
        ];
    }
}
