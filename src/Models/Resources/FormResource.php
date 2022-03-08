<?php

namespace Code16\Formoj\Models\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class FormResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isTitleHidden' => $this->is_title_hidden,
            'description' => $this->description
                ? Str::markdown($this->description)
                : null,
            'sections' => SectionResource::collection($this->sections)
        ];
    }
}
