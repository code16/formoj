<?php

namespace Code16\Formoj\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}