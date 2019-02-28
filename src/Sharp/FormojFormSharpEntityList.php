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
        );
    }

    /**
     * Build list layout using ->addColumn()
     *
     * @return void
     */
    function buildListLayout()
    {
        $this->addColumn("title", 4)
            ->addColumn("description", 4)
            ->addColumn("published_at", 4);
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
        return $this->transform(Form::all());
    }
}