<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $table = "formoj_forms";

    protected $dates = [
        "created_at", "updated_at", "published_at", "unpublished_at"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class)
            ->orderBy("order");
    }
}