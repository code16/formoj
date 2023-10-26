<?php

namespace Code16\Formoj\Sharp\Entities;

use Code16\Formoj\Sharp\FormojReplySharpEntityList;
use Code16\Sharp\Utils\Entities\SharpEntity;

class FormojReplyEntity extends SharpEntity
{
    protected ?string $list = FormojReplySharpEntityList::class;
    protected array $prohibitedActions = ['view', 'create', 'update', 'delete'];
}
