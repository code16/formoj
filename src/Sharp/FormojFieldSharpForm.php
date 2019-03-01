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
                ->setLabel("Libellé")
        )->addField(
            SharpFormMarkdownField::make("description")
                ->setLabel("Description")
                ->setToolbar([
                    SharpFormMarkdownField::B, SharpFormMarkdownField::I,
                    SharpFormMarkdownField::SEPARATOR,
                    SharpFormMarkdownField::A,
                ])
                ->setHeight(200)
        )->addField(
            SharpFormCheckField::make("required", "Saisie obligatoire")
        )->addField(
            SharpFormSelectField::make("type", FormojFieldSharpEntityList::$FIELD_TYPES)
            ->setDisplayAsDropdown()
        )->addField(
            SharpFormTextField::make("max_length")
                ->setLabel("Longueur maximale")
                ->setHelpMessage("En nombre de caractères")
                ->addConditionalDisplay("type", [Field::TYPE_TEXT, Field::TYPE_TEXTAREA])
        )->addField(
            SharpFormCheckField::make("multiple", "Autoriser plusieurs réponses")
                ->addConditionalDisplay("type", Field::TYPE_SELECT)
        )->addField(
            SharpFormTextField::make("max_values")
                ->setLabel("Nombre maximum de réponses")
                ->addConditionalDisplay("type", Field::TYPE_SELECT)
                ->addConditionalDisplay("multiple")
        )->addField(
            SharpFormListField::make("values")
                ->setLabel("Valeurs possibles")
                ->setAddable()->setAddText("Ajouter une valeur")
                ->setRemovable()
                ->setSortable()
                ->addItemField(
                    SharpFormTextField::make("value")
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
                ->withSingleField("values", function(FormLayoutColumn $column) {
                    $column->withSingleField("value");
                })
                ->withSingleField("multiple")
                ->withSingleField("max_values");
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
        return $this
            ->setCustomTransformer("values", function($value, $field) {
                return collect($value)
                    ->map(function($value) {
                        return [
                            "id" => uniqid(),
                            "value" => $value
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

        $data["values"] = collect($data["values"])->pluck("value")->all();

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
}