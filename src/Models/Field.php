<?php

namespace Code16\Formoj\Models;

use Code16\Formoj\Database\Factories\FieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    use HasFactory;

    const TYPE_TEXT = "text";
    const TYPE_TEXTAREA = "textarea";
    const TYPE_SELECT = "select";
    const TYPE_HEADING = "heading";
    const TYPE_UPLOAD = "upload";
    const TYPE_RATING = "rating";

    protected $table = "formoj_fields";
    protected $guarded = ["id"];
    protected $casts = [
        'field_attributes' => 'json',
        'required' => 'boolean',
    ];

    protected static function newFactory()
    {
        return new FieldFactory();
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * @param string $attribute
     * @return string|int|null
     */
    public function fieldAttribute(string $attribute)
    {
        return $this->field_attributes[$attribute] ?? null;
    }

    public function isTypeText(): bool
    {
        return $this->type === static::TYPE_TEXT;
    }

    public function isTypeTextarea(): bool
    {
        return $this->type === static::TYPE_TEXTAREA;
    }

    public function isTypeSelect(): bool
    {
        return $this->type === static::TYPE_SELECT;
    }

    public function isTypeHeading(): bool
    {
        return $this->type === static::TYPE_HEADING;
    }

    public function isTypeUpload(): bool
    {
        return $this->type === static::TYPE_UPLOAD;
    }

    public function isTypeRating(): bool
    {
        return $this->type === static::TYPE_RATING;
    }


    public function getFrontId(): string
    {
        return "f" . $this->id;
    }

    /**
     * Retrieve the model for a bound value.
     * Transform the "f[id]" sent by the front in "[id]"
     * Example: f123 -> 123
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->findOrFail(substr($value, 1));
    }
}
