<?php

namespace Code16\Formoj\Sharp;

use Illuminate\Foundation\Http\FormRequest;

class FormojFormSharpValidator extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['max:200', 'nullable'],
            'published_at' => ['date', 'nullable'],
            'unpublished_at' => ['date', 'after:published_at', 'nullable'],
            'administrator_email' => ['required_unless:notifications_strategy,none', 'email', 'nullable']
        ];
    }
}