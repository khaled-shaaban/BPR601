<?php

namespace App\Http\Requests\Users;

use App\Enums\AppRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(array_map(fn (AppRoles $r) => $r->value, AppRoles::cases()))],
            'authority_id' => [
                'nullable',
                'integer',
                'exists:authorities,id',
                Rule::requiredIf(fn () => $this->input('role') === AppRoles::REPRESENTATIVE->value),
            ],
        ];
    }
}
