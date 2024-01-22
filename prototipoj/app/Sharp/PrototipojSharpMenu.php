<?php

namespace App\Sharp;

use Code16\Sharp\Utils\Menu\SharpMenu;

class PrototipojSharpMenu extends SharpMenu
{

    public function build(): SharpMenu
    {
        return $this->addEntityLink("formoj_form", "Forms", "fa-list-alt");
    }
}
