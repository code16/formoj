<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    protected $table = "formoj_answers";

    protected $guarded = ["id"];

    /** @var array */
    protected $casts = [
        'content' => 'json',
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
     * @param string $attribute
     * @return mixed|null
     */
    public function content($attribute)
    {
        return $this->content[$attribute] ?? null;
    }
}