<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
        $rules = [
            'family_id' => 'required|exists:families,id',
            'income_category_id' => 'required|exists:contribution_categories,id',
            'periode' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'string|in:unpaid,paid,overdue',
            'payment_proof' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'family_id.required' => 'Keluarga harus dipilih',
            'family_id.exists' => 'Keluarga tidak ditemukan',
            'income_category_id.required' => 'Kategori iuran harus dipilih',
            'income_category_id.exists' => 'Kategori iuran tidak ditemukan',
            'periode.required' => 'Periode harus diisi',
            'periode.date' => 'Format periode tidak valid',
            'amount.required' => 'Nominal tagihan harus diisi',
            'amount.numeric' => 'Nominal harus berupa angka',
            'amount.min' => 'Nominal tidak boleh negatif',
            'status.in' => 'Status harus salah satu dari: unpaid, paid, overdue',
        ];
    }
}
