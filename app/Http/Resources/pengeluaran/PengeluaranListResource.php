<?php

namespace App\Http\Resources\pengeluaran;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengeluaranListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'nama_pengeluaran'  => $this->nama_pengeluaran,
            'kategori'          => $this->kategori,
            'tanggal'           => $this->tanggal?->format('Y-m-d'),
            'nominal'           => (float) $this->nominal,
            'verifikator'       => $this->verifikator,
            'bukti_pengeluaran' => $this->bukti_pengeluaran
                ? url('storage/pengeluaran/' . $this->bukti_pengeluaran)
                : null,
        ];
    }
}
