<?php

namespace Code16\Formoj\Sharp\Filters;

use Code16\Formoj\Models\Form;
use Code16\Sharp\EntityList\EntityListSelectRequiredFilter;

class FormojFormFilterHandler implements EntityListSelectRequiredFilter
{

    public function label()
    {
        return "formulaire";
    }

    public function values(): array
    {
        return Form::orderBy("id")
            ->get()
            ->mapWithKeys(function(Form $form) {
                return [$form->id => "#" . $form->id . " - " . ($form->title ?: trans("formoj::sharp.forms.no_title"))];
            })
            ->toArray();
    }

    public function defaultValue()
    {
        return Form::orderBy("id", "desc")->first()->id ?? null;
    }

    public function isMaster(): bool
    {
        return true;
    }

    public function retainValueInSession(): bool
    {
        return true;
    }
}