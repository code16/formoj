<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Sharp\Reorder\FormojFieldReorderHandler;
use Code16\Sharp\EntityList\Eloquent\SimpleEloquentReorderHandler;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;

class FormojFieldSharpEntityList extends SharpEntityList
{
    public function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make("type")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.type_label"))
                    ->setWidth(3)
                    ->setWidthOnSmallScreensFill()
            )
            ->addField(
                EntityListField::make("label")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.label_label"))
                    ->setWidth(5)
                    ->setWidthOnSmallScreensFill()
            )
            ->addField(
                EntityListField::make("help_text")
                    ->setLabel(trans("formoj::sharp.fields.list.columns.help_text_label"))
                    ->setWidth(4)
                    ->hideOnSmallScreens()
            );
    }

    function buildListConfig(): void
    {
        $this->configureReorderable(
            new SimpleEloquentReorderHandler(Field::class)
        );
    }

    public function getListData(): array|Arrayable
    {
        $fields = Field::orderBy("order")
            ->where("section_id", $this->queryParams->filterFor("formoj_section"));

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

    function delete($id): void
    {
        Field::findOrFail($id)->delete();
    }
}
