<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class TextFieldCreator extends FieldCreator
{

    /** @var string|null */
    protected $maxLength = null;

    /**
     * @return string
     */
    protected function getType()
    {
        return Field::TYPE_TEXT;
    }

    /**
     * @param string $maxLength
     * @return $this
     */
    public function setMaxLength($maxLength)
    {
        $this->maxLength = $maxLength;

        return $this;
    }

    /**
     * @return array
     */
    protected function getFieldAttributes()
    {
        return [
            "max_length" => $this->maxLength
        ];
    }
}