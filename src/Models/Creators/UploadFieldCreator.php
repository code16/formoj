<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;

class UploadFieldCreator extends FieldCreator
{

    /** @var int */
    protected $maxSize = 8;

    /** @var array|null */
    protected $accept = null;

    /**
     * @return string
     */
    protected function getType()
    {
        return Field::TYPE_UPLOAD;
    }

    /**
     * @param string $maxSize
     * @return $this
     */
    public function setMaxSize($maxSize)
    {
        $this->maxSize = $maxSize;

        return $this;
    }

    /**
     * @param array $accept
     * @return $this
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * @return array
     */
    protected function getFieldAttributes()
    {
        return [
            "max_size" => $this->maxSize,
            "accept" => implode(",", $this->accept ?? [])
        ];
    }
}