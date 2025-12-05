<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFamilyMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Harus true supaya request bisa dijalankan
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $residentId = $this->route('resident');

        return [
            'full_name' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'string', 'size:16', Rule::unique('residents', 'nik')->ignore($residentId)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:M,F'],
            'religion' => ['nullable', 'in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu,Lainnya'],
            'blood_type' => ['nullable', 'in:A,B,AB,O'],
            'family_role' => ['required', 'string', 'max:50'],
            'last_education' => ['nullable', 'string', 'max:100'],
            'occupation' => ['nullable', 'string', 'max:100'],
        ];
    }
}
