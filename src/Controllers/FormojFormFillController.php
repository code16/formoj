<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;

class FormojFormFillController
{
    /**
     * @param Form $form
     */
    public function store(Form $form)
    {
        if($form->isNotPublishedYet() || $form->isNoMorePublished()) {
            abort(403);
        }

        // TODO section validation

        Answer::create([
            "form_id" => $form->id,
            "content" => request()->all()
        ]);
    }
}