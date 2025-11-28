<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BroadcastResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'published_at' => optional($this->published_at)->toIso8601String(),
            'created_by' => $this->created_by,
            'creator_name' => optional($this->creator)->name,
            'photo' => $this->photo,
            'photo_url' => $this->photo_url,
            'document' => $this->document,
            'document_url' => $this->document_url,
        ];
    }
}
