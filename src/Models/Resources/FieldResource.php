<?php

namespace Code16\Formoj\Models\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'label' => $this->label,
            'required' => $this->required,
            'maxlength' => $this->when(
                $this->isTypeText() || $this->isTypeTextarea(), $this->max_length
            ),
            'multiple' => $this->when(
                $this->isTypeSelect(), $this->multiple
            ),
            'max' => $this->when(
                $this->isTypeSelect() && $this->multiple, $this->max_values
            ),
            'options' => $this->when(
                $this->isTypeSelect(), collect($this->values)->map(
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