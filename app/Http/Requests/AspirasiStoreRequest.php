<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AspirasiStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'created_at' => 'nullable|date_format:Y-m-d H:i:s',
            'attachments' => 'nullable|array',
        ];
    }
}
