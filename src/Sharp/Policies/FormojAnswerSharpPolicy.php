<?php

namespace Code16\Formoj\Sharp\Policies;

class FormojAnswerSharpPolicy
{
    public function view($user)
    {
        return false;
    }

    public function create($user)
    {
        return false;
    }

    public function update($user)
    {
        return false;
    }

    public function delete($user)
    {
        return false;
    }
}