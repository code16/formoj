<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojFormSharpEntityList extends SharpEntityList
{

    /**
     * Build list containers using ->addDataContainer()
     *
     * @return void
     */
    function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make("title")
                ->setLabel("Titre")
        )->addDataContainer(
            EntityListDataContainer::make("description")
                ->setLabel("Description")
        )->addDataContainer(
            EntityListDataContainer::make("published_at")
                ->setLabel("Dates publication")
        )->addDataContainer(
            EntityListDataContainer::make("sections")
                ->setLabel("Sections")
        );
    }

    /**
     * Build list layout using ->addColumn()
     *
     * @return void
     */
    function buildListLayout()
    {
        $this->addColumn("title", 3, 5)
            ->addColumnLarge("description", 3)
            ->addColumn("published_at", 3, 7)
            ->addColumnLarge("sections", 3);
    }

    /**
     * Build list config
     *
     * @return void
     */
    function buildListConfig()
    {
        // TODO: Implement buildListConfig() method.
    }

    /**
     * Retrieve all rows data as array.
     *
     * @param EntityListQueryParams $params
     * @return array
     */
    function getListData(EntityListQueryParams $params)
    {
        return $this
            ->setCustomTransformer("published_at", function($value, $instance) {
                if($instance->published_at) {
                    if($instance->unpublished_at) {
                        return sprintf(
                            "Du %s<br>au %s",
                            $instance->published_at->formatLocalized("%a %e %B %Y %Hh%M"),
                            $instance->unpublished_at->formatLocalized("%a %e %B %Y %Hh%M")
                        );
                    }
                    return sprintf(
                        "À partir du %s",
                        $instance->published_at->formatLocalized("%a %e %B %Y %Hh%M")
                    );
                }

                if($instance->unpublished_at) {
                    return sprintf(
                        "Jusqu'au %s",
                        $instance->unpublished_at->formatLocalized("%a %e %B %Y %Hh%M")
                    );
                }

                return "";
            })
            ->setCustomTransformer("sections", function($value, $instance) {
                return $instance->sections->pluck("title")->implode("<br>");
            })
            ->transform(Form::with("sections")->get());
    }
}