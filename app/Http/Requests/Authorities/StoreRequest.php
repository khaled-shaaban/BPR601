<?php 

namespace App\Http\Requests\Authorities;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'area_id' => 'required|integer|exists:areas,id',
            'address' => 'nullable|string|max:255',
            'contact_phone_1' => 'nullable|string|max:255',
            'contact_phone_2' => 'nullable|string|max:255',
            'active' => 'required|boolean',
        ];
    }
}