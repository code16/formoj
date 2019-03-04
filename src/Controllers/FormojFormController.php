<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Resources\FormResource;

class FormojFormController
{

    /**
     * @param Form $form
     * @return FormResource
     */
    public function show(Form $form)
    {
        if($form->isNotPublishedYet()) {
            abort(409, trans("formoj::form.form_too_soon"));
        }

        if($form->isNoMorePublished()) {
            abort(409, trans("formoj::form.form_too_late"));
        }

        return new FormResource($form);
    }
}