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
        return new FormResource($form);
    }
}