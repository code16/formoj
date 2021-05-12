<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Resources\AnswerResource;

class FormojAnswerController
{
    public function show(Answer $answer)
    {
        return new AnswerResource($answer);
    }
}
