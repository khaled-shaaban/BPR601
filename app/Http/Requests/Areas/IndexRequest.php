<?php 

namespace App\Http\Requests\Areas;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'filters' => 'nullable|array',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}