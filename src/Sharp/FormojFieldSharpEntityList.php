<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Sharp\Filters\FormojFormFilterHandler;
use Code16\Formoj\Sharp\Filters\FormojSectionFilterHandler;
use Code16\Formoj\Sharp\Reorder\FormojFieldReorderHandler;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojFieldSharpEntityList extends SharpEntityList
{

    /**
     * Build list containers using ->addDataContainer()
     *
     * @return void
     */
    function buildListDataContainers()
    {
        $this->addDataContainer(
            EntityListDataContainer::make("type")
                ->setLabel("")
        )->addDataContainer(
            EntityListDataContainer::make("label")
                ->setLabel("Libellé")
        )->addDataContainer(
            EntityListDataContainer::make("description")
                ->setLabel("Description")
        );
    }

    /**
     * Build list layout using ->addColumn()
     *
     * @return void
     */
    function buildListLayout()
    {
        $this->addColumn("type", 2)
            ->addColumn("label", 5)
            ->addColumn("description", 5);
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
            ->addFilter("formoj_section", FormojSectionFilterHandler::class)
            ->setReorderable(FormojFieldReorderHandler::class);
    }

    /**
     * Retrieve all rows data as array.
     *
     * @param EntityListQueryParams $params
     * @return array
     */
    function getListData(EntityListQueryParams $params)
    {
        $section = $params->filterFor("formoj_section") ?: app(FormojSectionFilterHandler::class)->defaultValue();

        $fields = Field::orderBy("order")
            ->where("section_id", $section);

        return $this->transform($fields->get());
    }
}