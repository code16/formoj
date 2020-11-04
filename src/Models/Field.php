<?php

namespace Code16\Formoj\Models;

use Database\Factories\FieldFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    const TYPE_TEXT = "text";
    const TYPE_TEXTAREA = "textarea";
    const TYPE_SELECT = "select";
    const TYPE_HEADING = "heading";
    const TYPE_UPLOAD = "upload";

    protected $table = "formoj_fields";

    protected $guarded = ["id"];

    /** @var array */
    protected $casts = [
        'field_attributes' => 'json',
        'required' => 'boolean',
    ];

    protected $dates = [
        "created_at", "updated_at",
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return FieldFactory::new();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * @param string $attribute
     * @return mixed|null
     */
    public function fieldAttribute($attribute)
    {
        return $this->field_attributes[$attribute] ?? null;
    }

    /**
     * @return bool
     */
    public function isTypeText()
    {
        return $this->type === static::TYPE_TEXT;
    }

    /**
     * @return bool
     */
    public function isTypeTextarea()
    {
        return $this->type === static::TYPE_TEXTAREA;
    }

    /**
     * @return bool
     */
    public function isTypeSelect()
    {
        return $this->type === static::TYPE_SELECT;
    }

    /**
     * @return bool
     */
    public function isTypeHeading()
    {
        return $this->type === static::TYPE_HEADING;
    }

    /**
     * @return bool
     */
    public function isTypeUpload()
    {
        return $this->type === static::TYPE_UPLOAD;
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