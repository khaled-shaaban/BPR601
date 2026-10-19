<?php

namespace App\Http\Requests\Users;

use App\Enums\AppRoles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
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
