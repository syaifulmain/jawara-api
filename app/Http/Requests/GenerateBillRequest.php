<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateBillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'income_category_id' => 'required|exists:contribution_categories,id',
            'periode' => 'required|date',
            'income_category_ids' => 'sometimes|array',
            'income_category_ids.*' => 'exists:contribution_categories,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'income_category_id.required' => 'Kategori iuran harus dipilih',
            'income_category_id.exists' => 'Kategori iuran tidak ditemukan',
            'periode.required' => 'Periode harus diisi',
            'periode.date' => 'Format periode tidak valid',
            'income_category_ids.array' => 'Kategori iuran harus berupa array',
            'income_category_ids.*.exists' => 'Salah satu kategori iuran tidak ditemukan',
        ];
    }
}
