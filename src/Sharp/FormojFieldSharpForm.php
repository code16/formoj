<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Field;
use Code16\Formoj\Sharp\Filters\FormojSectionFilterHandler;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormListField;
use Code16\Sharp\Form\Fields\SharpFormMarkdownField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Layout\FormLayoutFieldset;
use Code16\Sharp\Form\SharpForm;

class FormojFieldSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    function buildFormFields(): void
    {
        $this
            ->addField(
                SharpFormTextField::make("label")
                    ->setLabel(trans("formoj::sharp.fields.fields.label.label"))
            )
            ->addField(
                SharpFormTextField::make("identifier")
                    ->setLabel(trans("formoj::sharp.fields.fields.identifier.label"))
                    ->setHelpMessage(trans("formoj::sharp.fields.fields.identifier.help_text"))
            )
            ->addField(
                SharpFormMarkdownField::make("help_text")
                    ->setLabel(trans("formoj::sharp.fields.fields.help_text.label"))
                    ->setToolbar([
                        SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                        SharpFormMarkdownField::SEPARATOR,
                        SharpFormMarkdownField::A,
                    ])
                    ->setHeight(200)
                    ->addConditionalDisplay("type", "!" . Field::TYPE_HEADING)
            )
            ->addField(
                SharpFormCheckField::make("required", trans("formoj::sharp.fields.fields.required.text"))
                    ->addConditionalDisplay("type", "!" . Field::TYPE_HEADING)
            )
            ->addField(
                SharpFormSelectField::make("type", FormojFieldSharpEntityList::fieldTypes())
                    ->setLabel(trans("formoj::sharp.fields.fields.type.label"))
                    ->setDisplayAsDropdown()
            )
            ->addField(
                SharpFormTextField::make("max_length")
                    ->setLabel(trans("formoj::sharp.fields.fields.max_length.label"))
                    ->setHelpMessage(trans("formoj::sharp.fields.fields.max_length.help_text"))
                    ->addConditionalDisplay("type", [Field::TYPE_TEXT, Field::TYPE_TEXTAREA])
            )
            ->addField(
                SharpFormTextField::make("rows_count")
                    ->setLabel(trans("formoj::sharp.fields.fields.rows_count.label"))
                    ->addConditionalDisplay("type", Field::TYPE_TEXTAREA)
            )
            ->addField(
                SharpFormCheckField::make("multiple", trans("formoj::sharp.fields.fields.multiple.text"))
                    ->addConditionalDisplay("type", Field::TYPE_SELECT)
                    ->addConditionalDisplay("!radios")
            )
            ->addField(
                SharpFormCheckField::make("radios", trans("formoj::sharp.fields.fields.radios.text"))
                    ->addConditionalDisplay("type", Field::TYPE_SELECT)
            )
            ->addField(
                SharpFormTextField::make("max_options")
                    ->setLabel(trans("formoj::sharp.fields.fields.max_options.label"))
                    ->addConditionalDisplay("type", Field::TYPE_SELECT)
                    ->addConditionalDisplay("multiple")
                    ->addConditionalDisplay("!radios")
            )
            ->addField(
                SharpFormListField::make("options")
                    ->setLabel(trans("formoj::sharp.fields.fields.options.label"))
                    ->setAddable()->setAddText(trans("formoj::sharp.fields.fields.options.add_label"))
                    ->setRemovable()
                    ->setSortable()
                    ->addItemField(
                        SharpFormTextField::make("label")
                    )
                    ->addConditionalDisplay("type", Field::TYPE_SELECT)
            )
            ->addField(
                SharpFormTextField::make("max_size")
                    ->setLabel(trans("formoj::sharp.fields.fields.max_size.label"))
                    ->setHelpMessage(trans("formoj::sharp.fields.fields.max_size.help_text"))
                    ->addConditionalDisplay("type", Field::TYPE_UPLOAD)
            )
            ->addField(
                SharpFormTextField::make("accept")
                    ->setLabel(trans("formoj::sharp.fields.fields.accept.label"))
                    ->setPlaceholder("Ex: .jpeg,.gif,.png")
                    ->setHelpMessage(trans("formoj::sharp.fields.fields.accept.help_text"))
                    ->addConditionalDisplay("type", Field::TYPE_UPLOAD)
            );
    }

    function buildFormLayout(): void
    {
        $this
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withFieldset(trans("formoj::sharp.fields.fields.fieldsets.identifiers"), function (FormLayoutFieldset $fieldset) {
                        $fieldset->withSingleField("label")
                            ->withSingleField("identifier");
                    })
                    ->withSingleField("type")
                    ->withSingleField("required")
                    ->withSingleField("help_text");
    
            })
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withSingleField("max_size")
                    ->withSingleField("accept")
                    ->withSingleField("max_length")
                    ->withSingleField("rows_count")
                    ->withSingleField("options", function(FormLayoutColumn $column) {
                        $column->withSingleField("label");
                    })
                    ->withSingleField("radios")
                    ->withSingleField("multiple")
                    ->withSingleField("max_options");
            });
    }

    function find($id): array
    {
        foreach(["max_length", "rows_count", "max_options", "radios", "multiple", "max_size", "accept"] as $attribute) {
            $this->setCustomTransformer($attribute, function($value, $field) use($attribute) {
                return $field->fieldAttribute($attribute);
            });
        }

        return $this
            ->setCustomTransformer("options", function($value, $field) {
                return collect($field->fieldAttribute("options"))
                    ->map(function($option) {
                        return [
                            "id" => uniqid(),
                            "label" => $option
                        ];
                    })
                    ->values();
            })
            ->transform(Field::findOrFail($id));
    }

    function update($id, array $data)
    {
        $field = $id
            ? Field::findOrFail($id)
            : new Field([
                "section_id" => currentSharpRequest()->getPreviousShowFromBreadcrumbItems()->instanceId(),
                "order" => 100
            ]);

        $data["field_attributes"] = [];

        if($data["type"] == Field::TYPE_TEXT) {
            $this->transformAttributesToFieldAttributes($data, ["max_length" => "int"]);

        } elseif($data["type"] == Field::TYPE_TEXTAREA) {
            $this->transformAttributesToFieldAttributes($data, ["max_length" => "int", "rows_count" => "int"]);

        } elseif($data["type"] == Field::TYPE_SELECT) {
            $this->transformAttributesToFieldAttributes($data, ["max_options" => "int", "multiple" => "boolean", "radios" => "boolean"]);
            $data["field_attributes"]["options"] = collect($data["options"])->pluck("label")->all();

        } elseif($data["type"] == Field::TYPE_UPLOAD) {
            $this->transformAttributesToFieldAttributes($data, ["max_size" => "int", "accept" => "string"]);
        }

        unset(
            $data["max_length"], $data["rows_count"], $data["options"],
            $data["max_options"], $data["multiple"], $data["radios"],
            $data["max_size"], $data["accept"]
        );

        $this->save($field, $data);

        return $field->id;
    }

    function delete($id): void
    {
        Field::findOrFail($id)->delete();
    }

    protected function transformAttributesToFieldAttributes(&$data, array $attributeLabels): void
    {
        collect($data)
            ->filter(function ($value, $attribute) use($attributeLabels) {
                return isset($attributeLabels[$attribute]);
            })
            ->each(function($value, $attribute) use(&$data, $attributeLabels) {
                $data["field_attributes"][$attribute] = $this->castValue($value, $attributeLabels[$attribute]);
            });
    }

    /**
     * @param ?string $value
     * @param string $type
     * @return bool|int|string
     */
    protected function castValue(?string $value, string $type)
    {
        if($value === null || strlen($value) == 0) {
            return null;
        }

        switch($type) {
            case "int":
                return (int) $value;
            case "boolean":
                return (boolean) $value;
        }

        return $value;
    }
}