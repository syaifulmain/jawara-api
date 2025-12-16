<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FruitImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'family_id' => 'required|exists:families,id',
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['file'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }
}