<?php

namespace Code16\Formoj\Models;

use Code16\Formoj\Database\Factories\SectionFactory;
use Code16\Formoj\Models\Creators\SelectFieldCreator;
use Code16\Formoj\Models\Creators\TextareaFieldCreator;
use Code16\Formoj\Models\Creators\TextFieldCreator;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Section extends Model
{
    use HasFactory;

    protected $table = "formoj_sections";
    protected $guarded = ["id"];
    protected $casts = [
        "is_title_hidden" => "boolean",
    ];

    protected static function newFactory()
    {
        return new SectionFactory();
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class)
            ->orderBy("order");
    }

    public function newTextField(string $label): TextFieldCreator
    {
        return new TextFieldCreator($this, $label);
    }

    public function newTextareaField(string $label): TextareaFieldCreator
    {
        return new TextareaFieldCreator($this, $label);
    }

    /**
     * @param string $label
     * @param array|Arrayable $options
     * @return SelectFieldCreator
     */
    public function newSelectField(string $label, $options): SelectFieldCreator
    {
        return new SelectFieldCreator($this, $label, $options);
    }
}
