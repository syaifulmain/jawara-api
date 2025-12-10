<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AspirasiUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'message'     => 'sometimes|string',
            'status'      => 'sometimes|in:pending,approved,rejected',
            'attachments' => 'nullable|array',
        ];
    }
}
