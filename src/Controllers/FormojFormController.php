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
            abort(409, "Ce formulaire n'est pas encore disponible publiquement.");
        }

        if($form->isNoMorePublished()) {
            abort(409, "Ce formulaire n'est plus disponible publiquement.");
        }

        return new FormResource($form);
    }
}