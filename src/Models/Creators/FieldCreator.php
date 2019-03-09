<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;

abstract class FieldCreator
{
    /** @var Section */
    protected $section;

    /** @var string */
    protected $label;

    /** @var string */
    protected $helpText = null;

    /** @var bool */
    protected $required = false;

    /**
     * @param Section $section
     * @param $label
     */
    public function __construct(Section $section, $label)
    {
        $this->label = $label;
        $this->section = $section;
    }

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired(bool $required = true)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * @param string $helpText
     * @return $this
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;

        return $this;
    }

    /**
     * @return Field
     */
    public function create()
    {
        return $this->section->fields()->create([
            "help_text" => $this->helpText,
            "label" => $this->label,
            "required" => $this->required,
            "type" => $this->getType(),
            "field_attributes" => $this->getFieldAttributes()
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getType();

    /**
     * @return array
     */
    abstract protected function getFieldAttributes();
}