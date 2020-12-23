<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;

abstract class FieldCreator
{
    protected Section $section;
    protected string $label;
    protected ?string $helpText = null;
    protected bool $required = false;

    public function __construct(Section $section, string $label)
    {
        $this->label = $label;
        $this->section = $section;
    }

    public function setRequired(bool $required = true): self
    {
        $this->required = $required;

        return $this;
    }

    public function setHelpText(string $helpText): self
    {
        $this->helpText = $helpText;

        return $this;
    }

    public function create(): Field
    {
        return $this->section
            ->fields()
            ->create([
                "help_text" => $this->helpText,
                "label" => $this->label,
                "required" => $this->required,
                "type" => $this->getType(),
                "field_attributes" => $this->getFieldAttributes()
            ]);
    }

    abstract protected function getType(): string;

    abstract protected function getFieldAttributes(): array;
}