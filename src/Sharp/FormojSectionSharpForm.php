<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Section;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormTextareaField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayout;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class FormojSectionSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;
    
    protected ?string $formValidatorClass = FormojSectionSharpValidator::class;

    function buildFormFields(FieldsContainer $formFields) : void
    {
        $formFields
            ->addField(
                SharpFormTextField::make("title")
                    ->setMaxLength(200)
                    ->setLabel(trans("formoj::sharp.sections.fields.title.label"))
            )
            ->addField(
                SharpFormCheckField::make("is_title_hidden", trans("formoj::sharp.sections.fields.is_title_hidden.label"))
            )
            ->addField(
                SharpFormTextareaField::make("description")
                    ->setLabel(trans("formoj::sharp.sections.fields.description.label"))
                    ->setRowCount(3)
            );
    }

    function buildFormLayout(FormLayout $formLayout): void
    {
        $formLayout
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withSingleField("title")
                    ->withSingleField("is_title_hidden");

            })
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withSingleField("description");
            });
    }

    function find($id): array
    {
        return $this
            ->transform(Section::findOrFail($id));
    }

    function update($id, array $data)
    {
        $form = $id
            ? Section::findOrFail($id)
            : new Section([
                "form_id" => currentSharpRequest()->getPreviousShowFromBreadcrumbItems()->instanceId(),
                "order" => 100
            ]);

        $this->save($form, $data);

        return $form->id;
    }
}
