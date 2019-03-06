<?php

namespace Code16\Formoj\Sharp\Filters;

use Code16\Formoj\Models\Section;
use Code16\Sharp\EntityList\EntityListRequiredFilter;

class FormojSectionFilterHandler implements EntityListRequiredFilter
{

    /**
     * @return string
     */
    public function label()
    {
        return "section";
    }

    /**
     * @return array
     */
    public function values()
    {
        return Section::where("form_id", $this->currentFormId())
            ->orderBy("order")
            ->pluck("title", "id");
    }

    /**
     * @return string|int
     */
    public function defaultValue()
    {
        return Section::where("form_id", $this->currentFormId())
                ->orderBy("order")
                ->first()
                ->id ?? null;
    }

    /**
     * @return bool
     */
    public function retainValueInSession()
    {
        return true;
    }

    /**
     * @return string
     */
    public function currentFormId()
    {
        return session("_sharp_retained_filter_formoj_form") ?: app(FormojFormFilterHandler::class)->defaultValue();
    }
}