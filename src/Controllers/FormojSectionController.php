<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\SectionRequest;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;

class FormojSectionController
{
    public function update(Form $form, Section $section, SectionRequest $request)
    {
        return response()->json(["ok" => true]);
    }
}