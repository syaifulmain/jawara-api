<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PengeluaranRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'nama_pengeluaran' => 'required|string|max:255',
            'tanggal'          => 'required|date',
            'kategori'         => 'required|string|max:100',
            'nominal'          => 'required|numeric|min:0',
            'verifikator'      => 'nullable|string|max:255',
            'bukti_pengeluaran' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
