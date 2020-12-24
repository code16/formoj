<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Sharp\Commands\FormojAnswerDownloadFilesCommand;
use Code16\Formoj\Sharp\Commands\FormojAnswerExportCommand;
use Code16\Formoj\Sharp\Commands\FormojAnswerViewCommand;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojReplySharpEntityList extends SharpEntityList
{

    function buildListDataContainers(): void
    {
        $this
            ->addDataContainer(
                EntityListDataContainer::make("label")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.label_label"))
            )
            ->addDataContainer(
                EntityListDataContainer::make("value")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.value_label"))
            );
    }

    function buildListLayout(): void
    {
        $this->addColumn("label", 3, 5)
            ->addColumn("value", 9, 7);
    }

    function buildListConfig(): void
    {
    }

    function getListData(EntityListQueryParams $params)
    {
        $answer = Answer::findOrFail($params->filterFor("formoj_answer"));

        return $this
            ->setCustomTransformer("label", function($value, $instance) {
                return $instance->label;
            })
            ->setCustomTransformer("value", function($value, $instance) {
                return $instance->value;
            })
            ->transform(
                collect($answer->content)
                    ->map(function($value, $label) {
                        return (object)[
                            "id" => uniqid(),
                            "label" => $label,
                            "value" => $value
                        ];
                    })
                    ->values()
            );
    }
}