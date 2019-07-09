<?php

namespace Code16\Formoj\Models;

use Code16\Formoj\Notifications\FormojFormWasJustAnswered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class Form extends Model
{
    const NOTIFICATION_STRATEGY_EVERY = "every";
    const NOTIFICATION_STRATEGY_GROUPED = "grouped";
    const NOTIFICATION_STRATEGY_NONE = "none";

    protected $table = "formoj_forms";

    protected $dates = [
        "created_at", "updated_at", "published_at", "unpublished_at"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class)
            ->orderBy("order");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)
            ->orderBy("created_at");
    }

    /**
     * @return bool
     */
    public function isNotPublishedYet()
    {
        return $this->published_at && $this->published_at->isFuture();
    }

    /**
     * @return bool
     */
    public function isNoMorePublished()
    {
        return $this->unpublished_at && $this->unpublished_at->isPast();
    }

    /**
     * @param $id
     * @return Field|null
     */
    public function findField($id)
    {
        if($field = Field::find($id)) {
            return in_array($field->section_id, $this->sections->pluck("id")->all())
                ? $field
                : null;
        }

        return null;
    }

    /**
     * @param string $title
     * @param string|null $description
     * @return Section
     */
    public function createSection($title, $description = null)
    {
        return $this->sections()->create(compact('title', 'description'));
    }

    /**
     * @param $data
     * @return Answer
     */
    public function storeNewAnswer($data)
    {
        $answer = Answer::create([
            "form_id" => $this->id,
            "content" => collect($data)

                // Map to fields
                ->map(function($value, $id) {
                    return [
                        "field" => $this->findField(substr($id, 1)),
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

                    return [$field->label => $value];
                })
                ->all()
        ]);

        return tap($answer, function($answer) {
            if($this->notifications_strategy == self::NOTIFICATION_STRATEGY_EVERY) {
                Notification::route('mail', $this->administrator_email)
                    ->notify(new FormojFormWasJustAnswered($answer));
            }
        });
    }
}