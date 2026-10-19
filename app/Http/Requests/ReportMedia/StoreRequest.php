<?php

namespace App\Http\Requests\ReportMedia;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'files' => 'required|array|min:1',
            'files.*' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,pdf,doc,docx',
        ];
    }
}
