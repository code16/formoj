<?php

namespace Code16\Formoj\Models\Creators;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Illuminate\Support\Collection;

class SelectFieldCreator extends TextFieldCreator
{
    protected ?int $maxOptions = null;
    protected array $options = [];
    protected bool $multiple = false;
    protected bool $radios = false;

    /**
     * @param Section $section
     * @param string $label
     * @param array|Collection $options
     */
    public function __construct(Section $section, string $label, $options)
    {
        parent::__construct($section, $label);

        $this->setOptions($options);
    }

    protected function getType(): string
    {
        return Field::TYPE_SELECT;
    }

    /**
     * @param array|Collection $options
     * @return $this
     */
    public function setOptions($options): self
    {
        $this->options = $options;

        return $this;
    }

    public function setMaxOptions(int $maxOptions): self
    {
        $this->maxOptions = $maxOptions;

        return $this;
    }

    public function setMultiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    public function setRadios(bool $radios = true): self
    {
        $this->radios = $radios;

        return $this;
    }

    protected function getFieldAttributes(): array
    {
        return [
            "options" => (array) $this->options,
            "multiple" => $this->multiple && !$this->radios,
            "radios" => $this->radios,
            "max_options" => $this->maxOptions,
        ];
    }
}