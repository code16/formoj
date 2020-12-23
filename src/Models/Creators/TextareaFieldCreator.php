<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class TextareaFieldCreator extends TextFieldCreator
{
    protected int $rowsCount = 3;

    protected function getType(): string
    {
        return Field::TYPE_TEXTAREA;
    }

    public function setRowsCount(int $rowsCount): self
    {
        $this->$rowsCount = $rowsCount;

        return $this;
    }

    protected function getFieldAttributes(): array
    {
        return [
            "max_length" => $this->maxLength,
            "rows_count" => $this->rowsCount,
        ];
    }
}