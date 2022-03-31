<?php

namespace Code16\Formoj\Sharp\Entities;

use Code16\Formoj\Sharp\FormojFormSharpEntityList;
use Code16\Formoj\Sharp\FormojFormSharpForm;
use Code16\Formoj\Sharp\FormojFormSharpShow;
use Code16\Sharp\Utils\Entities\SharpEntity;

class FormojFormEntity extends SharpEntity
{
    protected ?string $list = FormojFormSharpEntityList::class;
    protected ?string $show = FormojFormSharpShow::class;
    protected ?string $form = FormojFormSharpForm::class;
}
