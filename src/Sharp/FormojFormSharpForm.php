<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\Form\Eloquent\WithSharpFormEloquentUpdater;
use Code16\Sharp\Form\Fields\SharpFormCheckField;
use Code16\Sharp\Form\Fields\SharpFormDateField;
use Code16\Sharp\Form\Fields\SharpFormEditorField;
use Code16\Sharp\Form\Fields\SharpFormSelectField;
use Code16\Sharp\Form\Fields\SharpFormTextField;
use Code16\Sharp\Form\Layout\FormLayout;
use Code16\Sharp\Form\Layout\FormLayoutColumn;
use Code16\Sharp\Form\Layout\FormLayoutFieldset;
use Code16\Sharp\Form\SharpForm;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class FormojFormSharpForm extends SharpForm
{
    use WithSharpFormEloquentUpdater;
    
    protected ?string $formValidatorClass = FormojFormSharpValidator::class;

    function buildFormFields(FieldsContainer $formFields) : void
    {
        $formFields
            ->addField(
                SharpFormTextField::make("title")
                    ->setMaxLength(200)
                    ->setLabel(trans("formoj::sharp.forms.fields.title.label"))
            )
            ->addField(
                SharpFormCheckField::make("is_title_hidden", trans("formoj::sharp.forms.fields.is_title_hidden.label"))
            )
            ->addField(
                SharpFormEditorField::make("description")
                    ->setRenderContentAsMarkdown()
                    ->setLabel(trans("formoj::sharp.forms.fields.description.label"))
                    ->setToolbar([
                        SharpFormEditorField::B, SharpFormEditorField::I,
                        SharpFormEditorField::SEPARATOR,
                        SharpFormEditorField::A,
                    ])
                    ->setHeight(200)
            )
            ->addField(
                SharpFormEditorField::make("success_message")
                    ->setRenderContentAsMarkdown()
                    ->setLabel(trans("formoj::sharp.forms.fields.success_message.label"))
                    ->setToolbar([
                        SharpFormEditorField::B, SharpFormEditorField::I,
                        SharpFormEditorField::SEPARATOR,
                        SharpFormEditorField::A,
                    ])
                    ->setHeight(200)
                    ->setHelpMessage(trans("formoj::sharp.forms.fields.success_message.help_text"))
            )
            ->addField(
                SharpFormDateField::make("published_at")
                    ->setLabel(trans("formoj::sharp.forms.fields.published_at.label"))
                    ->setHasTime(true)
                    ->setDisplayFormat("DD/MM/YYYY HH:mm")
            )
            ->addField(
                SharpFormDateField::make("unpublished_at")
                    ->setLabel(trans("formoj::sharp.forms.fields.unpublished_at.label"))
                    ->setHasTime(true)
                    ->setDisplayFormat("DD/MM/YYYY HH:mm")
            )
            ->addField(
                SharpFormTextField::make("administrator_email")
                    ->setLabel(trans("formoj::sharp.forms.fields.administrator_email.label"))
            )
            ->addField(
                SharpFormSelectField::make("notifications_strategy", FormojFormSharpEntityList::notificationStrategies())
                    ->setDisplayAsDropdown()
                    ->setLabel(trans("formoj::sharp.forms.fields.notifications_strategy.label"))
            );
    }

    function buildFormLayout(FormLayout $formLayout): void
    {
        $formLayout
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.title"), function (FormLayoutFieldset $fieldset) {
                        $fieldset->withSingleField("title")
                            ->withSingleField("is_title_hidden");
                    })
                    ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.dates"), function (FormLayoutFieldset $fieldset) {
                        $fieldset->withFields("published_at|6", "unpublished_at|6");
                    })
                    ->withSingleField("description");

            })
            ->addColumn(6, function (FormLayoutColumn $column) {
                $column
                    ->withFieldset(trans("formoj::sharp.forms.fields.fieldsets.notifications"), function (FormLayoutFieldset $fieldset) {
                        $fieldset->withSingleField("notifications_strategy")
                            ->withSingleField("administrator_email");
                    })
                    ->withSingleField("success_message");
            });
    }

    function find($id): array
    {
        return $this->transform(Form::findOrFail($id));
    }

    function update($id, array $data)
    {
        $form = $id ? Form::findOrFail($id) : new Form();

        $this->save($form, $data);

        return $form->id;
    }

    function delete($id): void
    {
        Form::findOrFail($id)->delete();
    }
}
