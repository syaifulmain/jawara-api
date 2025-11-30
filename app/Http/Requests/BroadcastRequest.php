<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BroadcastRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'published_at' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:5120',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['photo'] = 'nullable|string';
            $rules['document'] = 'nullable|string';
        }

        return $rules;
    }
}
