<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    const TYPE_TEXT = "text";
    const TYPE_TEXTAREA = "textarea";
    const TYPE_SELECT = "select";
    const TYPE_HEADING = "heading";

    protected $table = "formoj_fields";

    protected $guarded = ["id"];

    /** @var array */
    protected $casts = [
        'values' => 'json',
        'max_length' => 'integer',
        'max_values' => 'integer',
        'rows_count' => 'integer',
        'required' => 'boolean',
        'multiple' => 'boolean',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
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
}