<?php

namespace App\Http\Requests\Reports;

use App\Enums\ReportStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ReportStatus::class)],
            'note' => 'nullable|string|max:1000',
        ];
    }
}
