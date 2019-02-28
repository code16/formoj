<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    const TYPE_TEXT = "text";
    const TYPE_TEXTAREA = "textarea";
    const TYPE_SELECT = "select";

    /** @var array */
    protected $casts = [
        'values' => 'json',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}