<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class NotificationPreferencesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'notify_on_received' => 'required|boolean',
            'notify_on_status_change' => 'required|boolean',
            'notify_on_closed' => 'required|boolean',
        ];
    }
}
