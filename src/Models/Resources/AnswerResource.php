<?php

namespace Code16\Formoj\Models\Resources;

use Code16\Formoj\Models\Field;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public function toArray($request)
    {
        $formFields = Field::whereIn('identifier', collect($this->content)->keys())
            ->get();
        
        return [
            'id' => $this->id,
            'content' => $this->content,
            'fields' => collect($this->content)
                ->map(function($value, $identifier) use($formFields) {
                    /** @var Field $field */
                    $field = $formFields->where('identifier', $identifier)->first();
                    
                    return $field
                        ? [
                            'id' => $field->getFrontId(),
                            'name' => $identifier,
                            'label' => $field->label,
                            'type' => $field->type,
                        ]
                        : null;
                })
                ->filter()
                ->values()
        ];
    }
}
