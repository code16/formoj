<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Sharp\Commands\FormojAnswerViewCommand;
use Code16\Formoj\Sharp\Filters\FormojFormFilterHandler;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojAnswerSharpEntityList extends SharpEntityList
{

    /**
     * Build list containers using ->addDataContainer()
     *
     * @return void
     */
    function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make("created_at")
                ->setLabel("Date")
        )->addDataContainer(
            EntityListDataContainer::make("content")
                ->setLabel("")
        );
    }

    /**
     * Build list layout using ->addColumn()
     *
     * @return void
     */
    function buildListLayout()
    {
        $this->addColumn("created_at", 3, 5)
            ->addColumn("content", 9, 7);
    }

    /**
     * Build list config
     *
     * @return void
     */
    function buildListConfig()
    {
        $this
            ->addFilter("formoj_form", FormojFormFilterHandler::class)
            ->addInstanceCommand("view_answer", FormojAnswerViewCommand::class);
    }

    /**
     * Retrieve all rows data as array.
     *
     * @param EntityListQueryParams $params
     * @return array
     */
    function getListData(EntityListQueryParams $params)
    {
        $answers = Answer::orderBy("created_at", "desc")
            ->where("form_id", $params->filterFor("formoj_form"));

        return $this
            ->setCustomTransformer("created_at", function($value, $instance) {
                return $instance->created_at->formatLocalized("%e %b %Y %Hh%M");
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
            ->transform($answers->get());
    }
}