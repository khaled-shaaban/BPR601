<?php

namespace App\Http\Requests\Reports;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'assigned_to' => 'required|integer|exists:users,id',
        ];
    }
}
