<?php

namespace App\Http\Requests\Reports;

use App\Enums\ReportPriority;
use App\Enums\ReportTypes;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'authority_id' => 'required|integer|exists:authorities,id',
            'area_id' => 'required|integer|exists:areas,id',
            'latitude' => 'required|string|max:255',
            'longitude' => 'required|string|max:255',
            'priority' => ['required', Rule::enum(ReportPriority::class)],
            'type' => ['required', Rule::enum(ReportTypes::class)],
            'date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ];
    }
}
