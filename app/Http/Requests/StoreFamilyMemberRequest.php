<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFamilyMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'nik' => 'required|string|max:16',
            'phone' => 'nullable|string',
            'birth_place' => 'required|string',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'religion' => 'required',
            'blood_type' => 'nullable',
            'family_role' => 'required',
            'education' => 'nullable',
            'occupation' => 'nullable',
            'status' => 'required',
        ];
    }
}
