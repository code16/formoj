<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class UploadFieldCreator extends FieldCreator
{
    protected int $maxSize = 8;
    protected ?array $accept = null;

    protected function getType(): string
    {
        return Field::TYPE_UPLOAD;
    }

    public function setMaxSize(int $maxSize): self
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    public function setAccept(array $accept): self
    {
        $this->accept = $accept;

        return $this;
    }

    protected function getFieldAttributes(): array
    {
        return [
            "max_size" => $this->maxSize,
            "accept" => implode(",", $this->accept ?? [])
        ];
    }
}