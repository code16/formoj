<?php

namespace Code16\Formoj\Controllers\Requests;

use Code16\Formoj\Models\Field;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !$this->form->isNotPublishedYet() && !$this->form->isNoMorePublished();
    }

    public function rules(): array
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

    protected function currentSectionFields(): Collection
    {
        return $this->section->fields;
    }
}