<?php

namespace Code16\Formoj\Sharp\Filters;

use Code16\Formoj\Models\Form;
use Code16\Sharp\EntityList\EntityListRequiredFilter;

class FormojFormFilterHandler implements EntityListRequiredFilter
{

    /**
     * @return string
     */
    public function label()
    {
        return "formulaire";
    }

    /**
     * @return array
     */
    public function values()
    {
        return Form::orderBy("title")
            ->get()
            ->mapWithKeys(function(Form $form) {
                return [$form->id => "#" . $form->id . " - " . ($form->title ?: "(sans titre)")];
            });
    }

    /**
     * @return string|int
     */
    public function defaultValue()
    {
        return Form::orderBy("title")->first()->id ?? null;
    }

    /**
     * @return bool
     */
    public function isMaster(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function retainValueInSession()
    {
        return true;
    }
}