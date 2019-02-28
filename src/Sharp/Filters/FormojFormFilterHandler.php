<?php

namespace Code16\Formoj\Sharp\Filters;

use Code16\Formoj\Models\Form;
use Code16\Sharp\EntityList\EntityListRequiredFilter;

class FormojFormFilterHandler implements EntityListRequiredFilter
{

    /**
     * @return array
     */
    public function values()
    {
        return Form::orderBy("title")->pluck("title", "id");
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