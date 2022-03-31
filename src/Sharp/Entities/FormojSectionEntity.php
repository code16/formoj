<?php

namespace Code16\Formoj\Sharp\Entities;

use Code16\Formoj\Sharp\FormojSectionSharpEntityList;
use Code16\Formoj\Sharp\FormojSectionSharpForm;
use Code16\Formoj\Sharp\FormojSectionSharpShow;
use Code16\Sharp\Utils\Entities\SharpEntity;

class FormojSectionEntity extends SharpEntity
{
    protected ?string $list = FormojSectionSharpEntityList::class;
    protected ?string $show = FormojSectionSharpShow::class;
    protected ?string $form = FormojSectionSharpForm::class;
}
