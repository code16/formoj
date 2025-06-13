<?php

namespace Code16\Formoj\Models;

use Code16\Formoj\Database\Factories\FormFactory;
use Code16\Formoj\Notifications\FormojFormWasJustAnswered;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Notification;

class Form extends Model
{
    use HasFactory;

    const NOTIFICATION_STRATEGY_EVERY = "every";
    const NOTIFICATION_STRATEGY_GROUPED = "grouped";
    const NOTIFICATION_STRATEGY_NONE = "none";

    protected $table = "formoj_forms";
    protected $guarded = ["id"];

    protected $casts = [
        "is_title_hidden" => "boolean",
        "published_at" => "datetime",
        "unpublished_at" => "datetime",
    ];

    protected static function newFactory()
    {
        return new FormFactory();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class)
            ->orderBy("order");
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class)
            ->orderBy("created_at");
    }

    public function isNotPublishedYet(): bool
    {
        return $this->published_at && $this->published_at->isFuture();
    }

    public function isNoMorePublished(): bool
    {
        return $this->unpublished_at && $this->unpublished_at->isPast();
    }

    public function getLabel(): string
    {
        if($this->title) {
            return sprintf("%s (#%s)", $this->title, $this->id);
        }

        return '#' . $this->id;
    }

    public function findField($id): ?Field
    {
        if($field = Field::find($id)) {
            return in_array($field->section_id, $this->sections->pluck("id")->all())
                ? $field
                : null;
        }

        return null;
    }

    public function createSection(string $title, ?string $description = null): Section
    {
        return $this->sections()->create(compact('title', 'description'));
    }

    /**
     * @param array|Arrayable $data
     * @return Answer
     */
    public function storeNewAnswer($data): Answer
    {
        $answer = new Answer([
            "form_id" => $this->id,
        ]);

        $answer->fillWithData($data)->save();

        return tap($answer, function(Answer $answer) {
            if($this->notifications_strategy == self::NOTIFICATION_STRATEGY_EVERY) {
                Notification::route('mail', $this->administrator_email)
                    ->notify(new FormojFormWasJustAnswered($answer));
            }
        });
    }
}
