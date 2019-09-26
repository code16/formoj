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
                ->setLabel(trans("formoj::sharp.fields.list.columns.type_label"))
        )->addDataContainer(
            EntityListDataContainer::make("label")
                ->setLabel(trans("formoj::sharp.fields.list.columns.label_label"))
        )->addDataContainer(
            EntityListDataContainer::make("help_text")
                ->setLabel(trans("formoj::sharp.fields.list.columns.help_text_label"))
        );
    }

    /**
     * Build list layout using ->addColumn()
     *
     * @return void
     */
    function buildListLayout()
    {
        $this->addColumn("type", 3, 5)
            ->addColumn("label", 5, 7)
            ->addColumnLarge("help_text", 4);
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

        return $this
            ->setCustomTransformer("label", function($value, $instance) {
                if($instance->isTypeHeading()) {
                    return sprintf(
                        '<div style="color:gray"><em>%s</em></div>',
                        $instance->label
                    );
                }

                return sprintf(
                    '<div>%s</div><div><span style="background:lightgray; padding:0 2px">%s</span></div><div style="color:orange"><small>%s</small></div>',
                    $instance->label,
                    $instance->identifier,
                    $instance->required ? trans("formoj::sharp.fields.list.data.label.required") : ""
                );
            })
            ->setCustomTransformer("type", function($value, $instance) {
                return static::fieldTypes($value);
            })
            ->transform($fields->get());
    }

    /**
     * @param string|null $value
     * @return array
     */
    public static function fieldTypes($value = null)
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
}