<?php

namespace Code16\Formoj\Controllers\Requests;

use Illuminate\Support\Collection;

class FormRequest extends SectionRequest
{
    protected function currentSectionFields(): Collection
    {
        return $this->query("validate_all", 0) 
            ? $this->form->sections->pluck('fields')->flatten()
            : $this->form->sections->last()->fields;
    }
}