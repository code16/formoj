<?php

namespace Code16\Formoj\Sharp\Commands;

use Code16\Formoj\Models\Answer;
use Code16\Sharp\EntityList\Commands\InstanceCommand;

class FormojAnswerViewCommand extends InstanceCommand
{

    public function label(): string
    {
        return trans("formoj::sharp.answers.commands.view");
    }

    public function execute($instanceId, array $data = []): array
    {
        $answer = Answer::findOrFail($instanceId);

        return $this->view("formoj::sharp.answer", compact('answer'));
    }
}