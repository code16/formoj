<?php

namespace Code16\Formoj\Sharp;

use Illuminate\Foundation\Http\FormRequest;

class FormojSectionSharpValidator extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }
}