<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $table = "formoj_answers";
    protected $guarded = ["id"];
    protected $casts = [
        'content' => 'json',
    ];
    protected $dates = [
        "created_at", "updated_at",
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function content(string $attribute): ?string
    {
        return $this->content[$attribute] ?? null;
    }

    public function fillWithData(array $data): self
    {
        $this->content = collect($data)
            // Map to fields
            ->map(function($value, $id) {
                return [
                    "field" => $this->form->findField(substr($id, 1)),
                    "value" => $value
                ];
            })

            // Filter out unexpected fields
            ->filter(function($fieldAndValue) {
                return !is_null($fieldAndValue["field"])
                    && !$fieldAndValue["field"]->isTypeHeading();
            })

            // Extract value (select and upload cases)
            ->mapWithKeys(function($fieldAndValue) {
                $value = $fieldAndValue["value"];
                $field = $fieldAndValue["field"];

                if($field->isTypeSelect()) {
                    if($field->fieldAttribute("multiple")) {
                        $value = collect($value)
                            ->map(function($value) use($field) {
                                return $field->fieldAttribute("options")[$value - 1] ?? null;
                            })
                            ->filter(function($value) {
                                return !is_null($value);
                            })
                            ->all();

                    } else {
                        $value = $field->fieldAttribute("options")[$value - 1] ?? '';
                    }

                } elseif($field->isTypeUpload()) {
                    $value = $value['file'] ?? null;
                }

                return [$field->identifier => $value];
            })
            ->toArray();
        
        return $this;
    }
}