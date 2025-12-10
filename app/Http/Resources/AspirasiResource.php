<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AspirasiResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'user'        => $this->user ? $this->user->name : null,
            'user_id'     => $this->user_id,
            'title'       => $this->title,
            'message'     => $this->message,
            'status'      => $this->status,
            'attachments' => $this->attachments,
            'created_at'  => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at'  => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
