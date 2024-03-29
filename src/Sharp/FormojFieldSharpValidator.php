<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormojFieldSharpValidator extends FormRequest
{
    public function rules()
    {
        return [
            'label' => [
                'required',
                'max:200'
            ],
            'identifier' => [
                'required',
                'max:100',
                'alpha_dash',
                Rule::unique('formoj_fields', 'identifier')
                    ->whereIn("section_id",
                        Section::select("id")
                            ->where("form_id", 
                                Section::find(currentSharpRequest()->getPreviousShowFromBreadcrumbItems()->instanceId())
                                    ->form_id
                            )
                            ->pluck("id")
                            ->all()
                    )
                    ->ignore(currentSharpRequest()->instanceId())
            ],
            'type' => 'required',
            'max_length' => 'integer|nullable',
            'max_values' => 'integer|nullable',
            'rows_count' => 'integer|nullable|required_if:type,' . Field::TYPE_TEXTAREA,
            'options' => 'required_if:type,' . Field::TYPE_SELECT,
            'options.*.label' => 'required',
            'max_size' => 'required_if:type,' . Field::TYPE_UPLOAD . '|integer|nullable',
            'accept' => ['nullable','regex:/^(\.[a-z]+,)*(\.[a-z]+)$/']
        ];
    }
}