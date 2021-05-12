<?php

namespace Code16\Formoj\Models\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isTitleHidden' => $this->is_title_hidden,
            'description' => $this->description,
            'fields' => FieldResource::collection($this->fields)
        ];
    }
}