<?php

namespace App\Http\Requests\Reports;

use App\Enums\ReportPriority;
use App\Enums\ReportTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'authority_id' => 'sometimes|integer|exists:authorities,id',
            'area_id' => 'sometimes|integer|exists:areas,id',
            'latitude' => 'sometimes|string|max:255',
            'longitude' => 'sometimes|string|max:255',
            'priority' => ['sometimes', Rule::enum(ReportPriority::class)],
            'type' => ['sometimes', Rule::enum(ReportTypes::class)],
            'date' => 'sometimes|date',
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string|max:255',
        ];
    }
}
