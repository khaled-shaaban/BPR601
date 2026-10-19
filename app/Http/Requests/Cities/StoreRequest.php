<?php

namespace App\Http\Requests\Cities;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:cities,code',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'radius' => 'nullable|integer|min:0',
            'active' => 'sometimes|boolean',
        ];
    }
}
