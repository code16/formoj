<?php

namespace Code16\Formoj\Models;

use Code16\Formoj\Models\Creators\SelectFieldCreator;
use Code16\Formoj\Models\Creators\TextareaFieldCreator;
use Code16\Formoj\Models\Creators\TextFieldCreator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Section extends Model
{

    protected $table = "formoj_sections";

    protected $guarded = ["id"];

    protected $casts = [
        "is_title_hidden" => "boolean",
    ];

    protected $dates = [
        "created_at", "updated_at",
    ];

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
        return $this->hasMany(Field::class)
            ->orderBy("order");
    }

    /**
     * @param string $label
     * @return TextFieldCreator
     */
    public function newTextField($label)
    {
        return new TextFieldCreator($this, $label);
    }

    /**
     * @param string $label
     * @return TextareaFieldCreator
     */
    public function newTextareaField($label)
    {
        return new TextareaFieldCreator($this, $label);
    }

    /**
     * @param string $label
     * @param array|Collection $options
     * @return SelectFieldCreator
     */
    public function newSelectField($label, $options)
    {
        return new SelectFieldCreator($this, $label, $options);
    }
}