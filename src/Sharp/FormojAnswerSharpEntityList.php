<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Sharp\Commands\FormojAnswerExportCommand;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Fields\EntityListFieldsLayout;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;

class FormojAnswerSharpEntityList extends SharpEntityList
{

    public function buildListFields(EntityListFieldsContainer $fieldsContainer): void
    {
        $fieldsContainer
            ->addField(
                EntityListField::make("created_at")
                    ->setLabel(trans("formoj::sharp.answers.list.columns.created_at_label"))
            )
            ->addField(
                EntityListField::make("content")
                    ->setLabel(trans("formoj::sharp.answers.list.columns.content_label"))
            );
    }

    public function buildListLayout(EntityListFieldsLayout $fieldsLayout): void
    {
        $fieldsLayout
            ->addColumn("created_at", 3, 5)
            ->addColumn("content", 9, 7);
    }

    function buildListConfig(): void
    {
        $this
            ->configurePaginated();
    }

    function getEntityCommands(): ?array
    {
        return [
            FormojAnswerExportCommand::class
        ];
    }

    public function getListData(): array|Arrayable
    {
        $answers = Answer::orderBy("created_at", "desc")
            ->where("form_id", $this->queryParams->filterFor("formoj_form"));

        return $this
            ->setCustomTransformer("created_at", function($value, $instance) {
                return $instance->created_at->isoFormat("LLLL");
            })
            ->setCustomTransformer("content", function($value, $instance) {
                return collect($value)
                    ->map(function($value, $field) {
                        return $field . ": "
                            . (is_array($value) ? implode(", ", $value) : $value);
                    })
                    ->take(3)
                    ->implode("<br>");
            })
            ->transform($answers->paginate(40));
    }
}
