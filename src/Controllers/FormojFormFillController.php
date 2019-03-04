<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\FormRequest;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;

class FormojFormFillController
{
    /**
     * @param Form $form
     * @param FormRequest $request
     */
    public function store(Form $form, FormRequest $request)
    {
        Answer::create([
            "form_id" => $form->id,
            "content" => request()->all()
        ]);
    }
}