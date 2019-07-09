<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
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
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value)
    {
        return $this->findOrFail(substr($value, 1));
    }
}