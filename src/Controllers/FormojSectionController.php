<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\SectionRequest;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;

class FormojSectionController
{

    /**
     * @param Form $form
     * @param Section $section
     * @param SectionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Form $form, Section $section, SectionRequest $request)
    {
        return response()->json(["ok" => true]);
    }
}