<?php

namespace App\Http\Requests;

use App\Enums\RelocationType;
use Illuminate\Foundation\Http\FormRequest;

class FamilyRelocationRequest extends FormRequest
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
            'relocation_type' => 'required|in:' . implode(',', array_keys(RelocationType::options())),
            'relocation_date' => 'required|date',
            'reason' => 'required|string|max:255',
            'family_id' => 'required|exists:families,id',
            'past_address_id' => 'required|exists:addresses,id',
            'new_address_id' => 'nullable|exists:addresses,id',
            'created_by' => 'required|exists:users,id',
        ];
    }
}
