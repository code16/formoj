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
            ->where("form_id", $params->filterFor("form"));

        return $this
            ->setCustomTransformer("title", function($value, $instance) {
                return sprintf(
                    '<div>%s</div><div><small>%s</small></div>',
                    $value,
                    $instance->is_title_hidden 
                        ? trans("formoj::sharp.sections.list.data.title.is_hidden")
                        : ""
                );
            })
            ->setCustomTransformer("type", function($value, $instance) {
                return static::fieldTypes($value);
            })
            ->transform($sections->get());
    }

    /**
     * @param string|null $value
     * @return array|string|null
     */
    public static function fieldTypes(?string $value = null)
    {
        $types = [
            Field::TYPE_TEXT => trans("formoj::sharp.fields.types." . Field::TYPE_TEXT),
            Field::TYPE_TEXTAREA => trans("formoj::sharp.fields.types." . Field::TYPE_TEXTAREA),
            Field::TYPE_SELECT => trans("formoj::sharp.fields.types." . Field::TYPE_SELECT),
            Field::TYPE_HEADING => trans("formoj::sharp.fields.types." . Field::TYPE_HEADING),
            Field::TYPE_UPLOAD => trans("formoj::sharp.fields.types." . Field::TYPE_UPLOAD),
        ];

        return $value ? ($types[$value] ?? null) : $types;
    }

    protected function getCurrentSectionId(EntityListQueryParams $params): int
    {
        return $params->filterFor("formoj_section") ?: app(FormojSectionFilterHandler::class)->defaultValue();
    }
}