<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class TextFieldCreator extends FieldCreator
{
    protected ?int $maxLength = null;

    protected function getType(): string
    {
        return Field::TYPE_TEXT;
    }

    public function setMaxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    protected function getFieldAttributes(): array
    {
        return [
            "max_length" => $this->maxLength
        ];
    }
}