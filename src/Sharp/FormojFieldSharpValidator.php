<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Sharp\Filters\FormojFormFilterHandler;
use Code16\Sharp\Http\WithSharpContext;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormojFieldSharpValidator extends FormRequest
{
    use WithSharpContext;

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
            'label' => [
                'required',
                Rule::unique('formoj_fields', 'label')
                    ->whereIn("section_id",
                        Section::select("id")
                            ->where("form_id", session("_sharp_retained_filter_formoj_form") ?: app(FormojFormFilterHandler::class)->defaultValue())
                            ->pluck("id")
                            ->all()
                    )
                    ->ignore($this->context()->instanceId())
            ],
            'type' => 'required',
            'max_length' => 'integer|nullable',
            'max_values' => 'integer|nullable',
            'rows_count' => 'integer|nullable|required_if:type,' . Field::TYPE_TEXTAREA,
            'options' => 'required_if:type,' . Field::TYPE_SELECT,
            'options.*.label' => 'required',
            'max_size' => 'integer|required_if:type,' . Field::TYPE_UPLOAD,
            'accept' => ['nullable','regex:/^(\.[a-z]+,)*(\.[a-z]+)$/']
        ];
    }
}