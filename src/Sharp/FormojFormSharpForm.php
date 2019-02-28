<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormMarkdownField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Layout\FormLayoutFieldset;
use Code16\Sharp\Form\SharpForm;

class FormojFormSharpForm extends SharpForm
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
            SharpFormTextField::make("title")
                ->setLabel("Titre")
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
            SharpFormDateField::make("published_at")
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
                ->setLabel("Du (facultatif)")
        )->addField(
            SharpFormDateField::make("unpublished_at")
                ->setHasTime(true)
                ->setDisplayFormat("DD/MM/YYYY HH:mm")
                ->setLabel("Au (facultatif)")
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
                ->withSingleField("title")
                ->withFieldset("Publication", function (FormLayoutFieldset $fieldset) {
                    $fieldset->withFields("published_at|6", "unpublished_at|6");
                });

        })->addColumn(6, function (FormLayoutColumn $column) {
            $column->withSingleField("description");
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
        return $this->transform(Form::findOrFail($id));
    }

    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    function update($id, array $data)
    {
    }

    /**
     * @param $id
     */
    function delete($id)
    {
    }
}