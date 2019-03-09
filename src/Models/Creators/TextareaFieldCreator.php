<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class TextareaFieldCreator extends TextFieldCreator
{

    /** @var int */
    protected $rowsCount = 3;

    /**
     * @return string
     */
    protected function getType()
    {
        return Field::TYPE_TEXTAREA;
    }

    /**
     * @param int $rowsCount
     * @return $this
     */
    public function setRowsCount($rowsCount)
    {
        $this->$rowsCount = $rowsCount;

        return $this;
    }

    /**
     * @return array
     */
    protected function getFieldAttributes()
    {
        return [
            "max_length" => $this->maxLength,
            "rows_count" => $this->rowsCount,
        ];
    }
}