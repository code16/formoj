<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $table = "formoj_answers";

    protected $guarded = ["id"];

    protected $casts = [
        'content' => 'json',
    ];

    protected $dates = [
        "created_at", "updated_at",
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function content(string $attribute): ?string
    {
        return $this->content[$attribute] ?? null;
    }
}