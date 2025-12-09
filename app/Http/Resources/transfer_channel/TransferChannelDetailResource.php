<?php

namespace App\Http\Resources\transfer_channel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferChannelDetailResource extends JsonResource
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
            'type' => $this->type,
            'owner_name' => $this->owner_name,
            'account_number' => $this->account_number,
            'qr_code_image_path' => $this->qr_code_image_url,
            'thumbnail_image_path' => $this->thumbnail_image_url,
            'notes' => $this->notes,
        ];
    }
}
