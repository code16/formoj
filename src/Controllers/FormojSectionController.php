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
     * @return void
     */
    public function update(Form $form, Section $section)
    {
        $rules = $section
            ->fields
            ->mapWithKeys(function(Field $field) {
                $rules = [];

                if($field->required) {
                    $rules[] = "required";
                }

                if(($field->isTypeText() || $field->isTypeTextarea()) && $field->fieldAttribute("max_length")) {
                    $rules[] = "max:" . $field->fieldAttribute("max_length");
                }

                if($field->isTypeSelect() && !$field->fieldAttribute("multiple")) {
                    $rules[] = Rule::in(
                        collect($field->fieldAttribute("options"))
                            ->keys()
                            ->map(function($index) {
                                return $index + 1;
                            })
                    );
                }

                return [
                    "data.{$field->id}" => $rules
                ];
            })
            ->all();

//        dd($rules);

        request()->validate($rules);

        //
    }
}