<?php

namespace Code16\Formoj\Models\Resources;

use Code16\Formoj\Models\Field;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Field
 */
class FieldResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->getFrontId(),
            'type' => $this->type,
            'name' => $this->identifier,
            'identifier' => $this->identifier,
            'label' => $this->when(
                !$this->isTypeHeading(),
                $this->label
            ),
            'content' => $this->when(
                $this->isTypeHeading(),
                $this->label
            ),
            'helpText' => $this->when(
                !$this->isTypeHeading(),
                $this->help_text
            ),
            'required' => $this->when(
                !$this->isTypeHeading(),
                $this->required
            ),
            'maxlength' => $this->when(
                $this->isTypeText() || $this->isTypeTextarea(),
                $this->fieldAttribute('max_length')
            ),
            'multiple' => $this->when(
                $this->isTypeSelect() && !$this->fieldAttribute('radios'),
                $this->fieldAttribute('multiple')
            ),
            'radios' => $this->when(
                $this->isTypeSelect(),
                $this->fieldAttribute('radios')
            ),
            'max' => $this->when(
                $this->isTypeSelect() && $this->fieldAttribute('multiple') && !$this->fieldAttribute('radios'),
                $this->fieldAttribute('max_options')
            ),
            'rows' => $this->when(
                $this->isTypeTextArea(),
                $this->fieldAttribute('rows_count')
            ),
            'maxSize' => $this->when(
                $this->isTypeUpload(),
                $this->fieldAttribute('max_size')
            ),
            'accept' => $this->when(
                $this->isTypeUpload(),
                $this->fieldAttribute('accept')
            ),
            'options' => $this->when(
                $this->isTypeSelect(),
                collect($this->fieldAttribute('options'))->map(
                    function($value, $index) {
                        return [
                            "id" => $index+1,
                            "label" => $value
                        ];
                    }
                )
            )
        ];
    }
}
