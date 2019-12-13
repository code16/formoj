<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Illuminate\Support\Collection;

class SelectFieldCreator extends TextFieldCreator
{

    /** @var int|null */
    protected $maxOptions = null;

    /** @var array */
    protected $options = [];

    /** @var bool */
    protected $multiple = false;

    /** @var bool */
    protected $radios = false;

    /**
     * @param Section $section
     * @param $label
     * @param array|Collection $options
     */
    public function __construct(Section $section, $label, $options)
    {
        parent::__construct($section, $label);

        $this->setOptions($options);
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return Field::TYPE_SELECT;
    }

    /**
     * @param array|Collection $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param int $maxOptions
     * @return $this
     */
    public function setMaxOptions($maxOptions)
    {
        $this->maxOptions = $maxOptions;

        return $this;
    }

    /**
     * @param bool $multiple
     * @return $this
     */
    public function setMultiple(bool $multiple = true)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * @param bool $radios
     * @return $this
     */
    public function setRadios(bool $radios = true)
    {
        $this->radios = $radios;

        return $this;
    }

    /**
     * @return array
     */
    protected function getFieldAttributes()
    {
        return [
            "options" => (array) $this->options,
            "multiple" => $this->multiple && !$this->radios,
            "radios" => $this->radios,
            "max_options" => $this->maxOptions,
        ];
    }
}