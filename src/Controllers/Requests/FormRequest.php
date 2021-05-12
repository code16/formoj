<?php

namespace Code16\Formoj\Controllers\Requests;

use Illuminate\Support\Collection;

class FormRequest extends SectionRequest
{
    protected function currentSectionFields(): Collection
    {
        return $this->form->sections->last()->fields;
    }
}