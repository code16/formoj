<?php

namespace Code16\Formoj\Controllers\Requests;

use Illuminate\Support\Collection;

class FormRequest extends SectionRequest
{

    /**
     * @return Collection
     */
    protected function currentSectionFields()
    {
        return $this->form->sections->last()->fields;
    }
}