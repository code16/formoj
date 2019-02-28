<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = "formoj_sections";

    protected $guarded = ["id"];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}