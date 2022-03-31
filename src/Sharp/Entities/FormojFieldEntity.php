<?php

namespace Code16\Formoj\Sharp\Entities;

use Code16\Formoj\Sharp\FormojFieldSharpEntityList;
use Code16\Formoj\Sharp\FormojFieldSharpForm;
use Code16\Sharp\Utils\Entities\SharpEntity;

class FormojFieldEntity extends SharpEntity
{
    protected ?string $list = FormojFieldSharpEntityList::class;
    protected ?string $form = FormojFieldSharpForm::class;
}
