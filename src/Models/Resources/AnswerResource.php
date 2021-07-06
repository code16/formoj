<?php

namespace Code16\Formoj\Models\Resources;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Field;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Answer
 */
class AnswerResource extends JsonResource
{
    public function toArray($request)
    {
        $formFields = $this->getRelatedFields();

        return [
            'id' => $this->id,
            'content' => $this->content,
            'fields' => collect($this->content)
                ->map(function($value, $identifier) use($formFields) {
                    /** @var Field $field */
                    $field = $formFields->firstWhere('identifier', $identifier);

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
