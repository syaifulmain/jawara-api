<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomeCategoryRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:bulanan,mingguan,tahunan,sekali_bayar',
            'nominal' => 'required|numeric|min:0',
            'description' => 'nullable|string',            
        ];

        // For update, make name unique except for current record
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['name'] = 'required|string|max:255|unique:income_categories,name,' . $this->route('income_category');
        } else {
            $rules['name'] = 'required|string|max:255|unique:income_categories,name';
        }

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
            'name.required' => 'Nama iuran harus diisi',
            'name.unique' => 'Nama iuran sudah ada',
            'type.required' => 'Jenis iuran harus dipilih',
            'type.in' => 'Jenis iuran harus salah satu dari: bulanan, mingguan, tahunan, sekali_bayar',
            'nominal.required' => 'Nominal iuran harus diisi',
            'nominal.numeric' => 'Nominal harus berupa angka',
            'nominal.min' => 'Nominal tidak boleh negatif',
        ];
    }
}
