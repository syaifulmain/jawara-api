<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', 'string', Rule::in(User::availableRoles())],
            'identity_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Additional rules for role 'user'
        if ($this->role === User::ROLE_USER) {
            $rules = array_merge($rules, [
                'address_id' => 'required|exists:addresses,id',
                'status' => 'required|string|in:owner,tenant',
                'nik' => 'required|string|size:16|unique:residents,nik',
                'gender' => 'required|string|in:M,F',
                'birth_place' => 'nullable|string|max:255',
                'birth_date' => 'nullable|date|before:today',
                'religion' => 'nullable|string|max:50',
                'blood_type' => 'nullable|string|in:A,B,AB,O',
                'last_education' => 'nullable|string|max:50',
                'occupation' => 'nullable|string|max:100',
            ]);
        }

        return $rules;
    }
}
