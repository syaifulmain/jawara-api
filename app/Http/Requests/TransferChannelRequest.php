<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferChannelRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:BANK,E_WALLET,QRIS'],
            'owner_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:50'],
            // 'qr_code_image_path' => ['nullable', 'string', 'max:255'],
            // 'thumbnail_image_path' => ['nullable', 'string', 'max:255'],

            'qr_code_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],
            'thumbnail_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif'],

            'notes' => ['nullable', 'string', 'max:255'],

        ];
    }
}
