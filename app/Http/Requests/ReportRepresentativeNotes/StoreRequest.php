<?php

namespace App\Http\Requests\ReportRepresentativeNotes;

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
