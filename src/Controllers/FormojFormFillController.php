<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\FormRequest;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;

class FormojFormFillController
{
    /**
     * @param Form $form
     * @param FormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, FormRequest $request)
    {
        Answer::create([
            "form_id" => $form->id,
            "content" => request()->only(
                $form
                    ->sections
                    ->map(function(Section $section) {
                        return $section->fields;
                    })
                    ->map(function($fields) {
                        return $fields
                            ->pluck("id")
                            ->map(function($id) {
                                return "f{$id}";
                            });
                    })
                    ->flatten()
                    ->all()
            )
        ]);

        return response()->json([
            "message" => $form->success_message ?: trans("formoj::form.success_message")
        ]);
    }
}