<?php

namespace Code16\Formoj\Sharp\Filters;

use Code16\Formoj\Models\Section;
use Code16\Sharp\EntityList\EntityListSelectRequiredFilter;

class FormojSectionFilterHandler implements EntityListSelectRequiredFilter
{

    public function label(): string
    {
        return "section";
    }

    public function values(): array
    {
        return Section::where("form_id", $this->currentFormId())
            ->orderBy("order")
            ->pluck("title", "id")
            ->toArray();
    }

    public function defaultValue()
    {
        return Section::where("form_id", $this->currentFormId())
                ->orderBy("order")
                ->first()
                ->id ?? null;
    }

    public function retainValueInSession(): bool
    {
        return true;
    }

    public function currentFormId(): ?string
    {
        return session("_sharp_retained_filter_formoj_form") ?: app(FormojFormFilterHandler::class)->defaultValue();
    }
}