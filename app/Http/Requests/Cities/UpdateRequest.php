<?php

namespace App\Http\Requests\Cities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('cities', 'code')->ignore($this->route('id'))],
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'radius' => 'nullable|integer|min:0',
            'active' => 'sometimes|boolean',
        ];
    }
}
