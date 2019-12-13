<?php

namespace Code16\Formoj\Controllers\Requests;

use Code16\Formoj\Models\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->form->isNotPublishedYet() && !$this->form->isNoMorePublished();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this
            ->currentSectionFields()
            ->mapWithKeys(function(Field $field) {
                $rules = $field->required ? ["required"] : ["nullable"];

                if(($field->isTypeText() || $field->isTypeTextarea()) && $field->fieldAttribute("max_length")) {
                    $rules[] = "max:" . $field->fieldAttribute("max_length");
                }

                if($field->isTypeSelect()) {
                    $rules[] = Rule::in(
                        collect($field->fieldAttribute("options"))
                            ->keys()
                            ->map(function($index) {
                                return $index + 1;
                            })
                    );

                    if(!$field->fieldAttribute("radios") && $field->fieldAttribute("multiple")) {
                        $rules[] = "array";

                        if($field->fieldAttribute("max_options")) {
                            $rules[] = "max:" . $field->fieldAttribute("max_options");
                        }
                    }
                }

                return [
                    "f" . $field->id => $rules
                ];
            })
            ->all();
    }

    /**
     * @return Collection
     */
    protected function currentSectionFields()
    {
        return $this->section->fields;
    }
}