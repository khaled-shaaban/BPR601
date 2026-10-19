<?php

namespace App\Http\Requests\Areas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'code' => ['sometimes', 'string', 'max:255', Rule::unique('areas', 'code')->ignore($this->route('id'))],
            'city_id' => 'sometimes|integer|exists:cities,id',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ];
    }
}
