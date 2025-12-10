<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|size:16|unique:residents,nik',
            'phone_number' => 'required|string|max:15',
            'gender' => 'required|in:M,F',
            'address_id' => 'nullable|exists:addresses,id',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:owner,tenant',
            'identity_photo' => 'nullable|image|max:2048',
        ];
    }
}
