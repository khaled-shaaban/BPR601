<?php

namespace App\Http\Requests\Areas;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:areas,code',
            'city_id' => 'required|integer|exists:cities,id',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ];
    }
}
