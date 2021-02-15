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
        $formFields = Field::whereIn('identifier', collect($this->content)->keys())
            ->get();
        
        return [
            'id' => $this->id,
            'content' => $this->content,
            'fields' => collect($this->content)
                ->map(function($value, $identifier) use($formFields) {
                    $field = $formFields->where('identifier', $identifier)->first();
                    
                    return [
                        'key' => $identifier,
                        'label' => $field->label ?? $identifier,
                        'type' => $field->type ?? "text",
                    ];
                })
                ->values()
        ];
    }
}
