<?php

namespace App\Http\Resources\transfer_channel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferChannelListResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $transferChannel = [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'owner_name' => $this->owner_name,
        ];

        return $transferChannel;
    }
}
