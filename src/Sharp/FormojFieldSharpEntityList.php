<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Sharp\Reorder\FormojFieldReorderHandler;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;

class FormojFieldSharpEntityList extends SharpEntityList
{
    function buildListDataContainers(): void
    {
        $this
            ->addDataContainer(
                EntityListDataContainer::make("type")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.type_label"))
            )
            ->addDataContainer(
                EntityListDataContainer::make("label")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.label_label"))
            )
            ->addDataContainer(
                EntityListDataContainer::make("help_text")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.help_text_label"))
            );
    }

    function buildListLayout(): void
    {
        $this->addColumn("type", 3, 5)
            ->addColumn("label", 5, 7)
            ->addColumnLarge("help_text", 4);
    }

    function buildListConfig(): void
    {
        $this->setReorderable(FormojFieldReorderHandler::class);
    }

    function getListData(EntityListQueryParams $params)
    {
        $fields = Field::orderBy("order")
            ->where("section_id", $params->filterFor("formoj_section"));

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
}