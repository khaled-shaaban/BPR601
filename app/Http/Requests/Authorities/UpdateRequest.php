<?php 

namespace App\Http\Requests\Authorities;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'code' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'area_id' => 'sometimes|integer|exists:areas,id',
            'address' => 'nullable|string|max:255',
            'contact_phone_1' => 'nullable|string|max:255',
            'contact_phone_2' => 'nullable|string|max:255',
            'active' => 'sometimes|boolean',
        ];
    }
}