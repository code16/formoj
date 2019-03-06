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
use Code16\Sharp\Form\SharpForm;

class FormojFieldSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;

    /**
     * Build form fields using ->addField()
     *
     * @return void
     */
    function buildFormFields()
    {
        $this->addField(
            SharpFormTextField::make("label")
                ->setLabel(trans("formoj::sharp.fields.fields.label.label"))
        )->addField(
            SharpFormMarkdownField::make("help_text")
                ->setLabel(trans("formoj::sharp.fields.fields.help_text.label"))
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
                ->addConditionalDisplay("type", "!" . Field::TYPE_HEADING)
        )->addField(
            SharpFormCheckField::make("required", trans("formoj::sharp.fields.fields.required.text"))
                ->addConditionalDisplay("type", "!" . Field::TYPE_HEADING)
        )->addField(
            SharpFormSelectField::make("type", FormojFieldSharpEntityList::fieldTypes())
                ->setLabel(trans("formoj::sharp.fields.fields.type.label"))
                ->setDisplayAsDropdown()
        )->addField(
            SharpFormTextField::make("max_length")
                ->setLabel(trans("formoj::sharp.fields.fields.max_length.label"))
                ->setHelpMessage(trans("formoj::sharp.fields.fields.max_length.help_text"))
                ->addConditionalDisplay("type", [Field::TYPE_TEXT, Field::TYPE_TEXTAREA])
        )->addField(
            SharpFormTextField::make("rows_count")
                ->setLabel(trans("formoj::sharp.fields.fields.rows_count.label"))
                ->addConditionalDisplay("type", Field::TYPE_TEXTAREA)
        )->addField(
            SharpFormCheckField::make("multiple", trans("formoj::sharp.fields.fields.multiple.text"))
                ->addConditionalDisplay("type", Field::TYPE_SELECT)
        )->addField(
            SharpFormTextField::make("max_options")
                ->setLabel(trans("formoj::sharp.fields.fields.max_options.label"))
                ->addConditionalDisplay("type", Field::TYPE_SELECT)
                ->addConditionalDisplay("multiple")
        )->addField(
            SharpFormListField::make("options")
                ->setLabel(trans("formoj::sharp.fields.fields.options.label"))
                ->setAddable()->setAddText(trans("formoj::sharp.fields.fields.options.add_label"))
                ->setRemovable()
                ->setSortable()
                ->addItemField(
                    SharpFormTextField::make("label")
                )
                ->addConditionalDisplay("type", Field::TYPE_SELECT)
        );
    }

    /**
     * Build form layout using ->addTab() or ->addColumn()
     *
     * @return void
     */
    function buildFormLayout()
    {
        $this->addColumn(6, function (FormLayoutColumn $column) {
            $column
                ->withSingleField("label")
                ->withSingleField("type")
                ->withSingleField("required")
                ->withSingleField("description");

        })->addColumn(6, function (FormLayoutColumn $column) {
            $column
                ->withSingleField("max_length")
                ->withSingleField("rows_count")
                ->withSingleField("options", function(FormLayoutColumn $column) {
                    $column->withSingleField("label");
                })
                ->withSingleField("multiple")
                ->withSingleField("max_options");
        });
    }

    /**
     * Retrieve a Model for the form and pack all its data as JSON.
     *
     * @param $id
     * @return array
     */
    function find($id): array
    {
        foreach(["max_length", "rows_count", "max_options", "multiple"] as $attribute) {
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

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    function update($id, array $data)
    {
        $field = $id
            ? Field::findOrFail($id)
            : new Field([
                "section_id" => session("_sharp_retained_filter_formoj_section") ?: app(FormojSectionFilterHandler::class)->defaultValue()
            ]);

        $data["field_attributes"] = [];

        if($data["type"] == Field::TYPE_TEXT) {
            $this->transformAttributesToFieldAttributes($data, ["max_length" => "int"]);

        } elseif($data["type"] == Field::TYPE_TEXTAREA) {
            $this->transformAttributesToFieldAttributes($data, ["max_length" => "int", "rows_count" => "int"]);

        } elseif($data["type"] == Field::TYPE_SELECT) {
            $this->transformAttributesToFieldAttributes($data, ["max_options" => "int", "multiple" => "boolean"]);
            $data["field_attributes"]["options"] = collect($data["options"])->pluck("label")->all();
        }

        unset($data["max_length"], $data["rows_count"], $data["options"], $data["max_options"], $data["multiple"]);

        $this->save($field, $data);

        return $field->id;
    }

    /**
     * @param $id
     */
    function delete($id)
    {
        Field::findOrFail($id)->delete();
    }

    protected function transformAttributesToFieldAttributes(&$data, array $attributeLabels)
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
     * @param string $value
     * @param string $type
     * @return bool|int|string
     */
    protected function castValue($value, $type)
    {
        if(strlen($value) == 0) {
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