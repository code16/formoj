<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Illuminate\Validation\Rule;

class FormojSectionController
{

    /**
     * @param Form $form
     * @param Section $section
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Form $form, Section $section)
    {
        $rules = $section
            ->fields
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

                    if($field->fieldAttribute("multiple")) {
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

        request()->validate($rules);

        return response()->json(["ok" => true]);
    }
}