<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Section;
use Code16\Formoj\Sharp\Filters\FormojSectionFilterHandler;
use Code16\Formoj\Sharp\Reorder\FormojSectionReorderHandler;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojSectionSharpEntityList extends SharpEntityList
{
    function buildListDataContainers(): void
    {
        $this
            ->addDataContainer(
                EntityListDataContainer::make("title")
                    ->setLabel(trans("formoj::sharp.sections.list.columns.title_label"))
            )
            ->addDataContainer(
                EntityListDataContainer::make("description")
                    ->setLabel(trans("formoj::sharp.sections.list.columns.description_label"))
            );
    }

    function buildListLayout(): void
    {
        $this->addColumn("title", 4, 6)
            ->addColumn("description", 8, 6);
    }

    function buildListConfig(): void
    {
        $this
            ->setReorderable(FormojSectionReorderHandler::class);
    }

    function getListData(EntityListQueryParams $params)
    {
        $sections = Section::orderBy("order")
            ->where("form_id", $params->filterFor("formoj_form"));

        return $this
            ->setCustomTransformer("title", function($value, $instance) {
                return static::transformTitle($instance);
            })
            ->transform($sections->get());
    }

    public static function transformTitle(Section $section): string
    {
        return sprintf(
            '<div>%s</div><div><small>%s</small></div>',
            $section->title,
            $section->is_title_hidden
                ? trans("formoj::sharp.sections.list.data.title.is_hidden")
                : ""
        );
    }
}