<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Illuminate\Foundation\Http\FormRequest;

class FormojFieldSharpValidator extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'label' => 'required',
            'type' => 'required',
            'max_length' => 'integer|nullable',
            'max_values' => 'integer|nullable',
            'values' => 'required_if:type,' . Field::TYPE_SELECT,
            'values.*.value' => 'required',
        ];
    }
}