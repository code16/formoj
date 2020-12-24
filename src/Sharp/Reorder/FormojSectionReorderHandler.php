<?php

namespace Code16\Formoj\Sharp\Reorder;

use Code16\Formoj\Models\Section;
use Code16\Sharp\EntityList\Commands\ReorderHandler;

class FormojSectionReorderHandler implements ReorderHandler
{

    function reorder(array $ids): void
    {
        Section::whereIn("id", $ids)
            ->get()
            ->each(function(Section $section) use($ids) {
                $section->update([
                    "order" => array_search($section->id, $ids) + 1
                ]);
            });
    }
}