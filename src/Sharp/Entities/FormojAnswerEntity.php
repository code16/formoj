<?php

namespace Code16\Formoj\Sharp\Entities;

use Code16\Formoj\Sharp\FormojAnswerSharpEntityList;
use Code16\Formoj\Sharp\FormojAnswerSharpShow;
use Code16\Sharp\Utils\Entities\SharpEntity;

class FormojAnswerEntity extends SharpEntity
{
    protected ?string $list = FormojAnswerSharpEntityList::class;
    protected ?string $show = FormojAnswerSharpShow::class;
    protected array $prohibitedActions = ['create', 'update', 'delete'];
}
