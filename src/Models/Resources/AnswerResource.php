<?php

namespace Code16\Formoj\Models\Resources;

use Code16\Formoj\Models\Field;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
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
            'content' => $this->content,
            'fields' => collect($this->content)->map(function($value, $identifier) {
                if(!$field = Field::where('identifier', $identifier)->first()) {
                    return null;
                }
                return [
                    'key' => $field->identifier,
                    'label' => $field->label,
                    'type' => $field->type,
                ];
            })->values()
        ];
    }
}
