<?php

namespace Code16\Formoj\Sharp\Reorder;

use Code16\Formoj\Models\Field;
use Code16\Sharp\EntityList\Commands\ReorderHandler;

class FormojFieldReorderHandler implements ReorderHandler
{

    /**
     * @param array $ids
     */
    function reorder(array $ids)
    {
        Field::whereIn("id", $ids)
            ->get()
            ->each(function(Field $field) use($ids) {
                $field->update([
                    "order" => array_search($field->id, $ids) + 1
                ]);
            });
    }
}