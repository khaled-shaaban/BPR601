<?php

namespace App\Http\Requests\ReportComments;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:2000',
        ];
    }
}
