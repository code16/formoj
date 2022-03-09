<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Form;
use Code16\Sharp\Show\Fields\SharpShowEntityListField;
use Code16\Sharp\Show\Fields\SharpShowTextField;
use Code16\Sharp\Show\Layout\ShowLayout;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\SharpShow;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class FormojFormSharpShow extends SharpShow
{

    protected function buildShowFields(FieldsContainer $showFields): void
    {
        $showFields
            ->addField(
                SharpShowTextField::make("ref")
                    ->setLabel(trans("formoj::sharp.forms.list.columns.ref_label"))
            )
            ->addField(
                SharpShowTextField::make("title")
                    ->setLabel(trans("formoj::sharp.forms.list.columns.title_label"))
            )
            ->addField(
                SharpShowTextField::make("published_at")
                    ->setLabel(trans("formoj::sharp.forms.list.columns.published_at_label"))
            )
            ->addField(
                SharpShowTextField::make("notifications_strategy")
                    ->setLabel(trans("formoj::sharp.forms.fields.notifications_strategy.label"))
            )
            ->addField(
                SharpShowTextField::make("description")
                    ->setLabel(trans("formoj::sharp.forms.fields.description.label"))
            )
            ->addField(
                SharpShowTextField::make("success_message")
                    ->setLabel(trans("formoj::sharp.forms.fields.success_message.label"))
            )
            ->addField(
                SharpShowEntityListField::make("sections", "formoj_section")
                    ->setLabel(trans("formoj::sharp.forms.list.columns.sections_label"))
                    ->hideFilterWithValue("formoj_form", function($instanceId) {
                        return $instanceId;
                    })
            )
            ->addField(
                SharpShowEntityListField::make("answers", "formoj_answer")
                    ->setLabel(trans("formoj::sharp.forms.list.columns.answers_label"))
                    ->hideFilterWithValue("formoj_form", function($instanceId) {
                        return $instanceId;
                    })
            );
    }

    protected function buildShowLayout(ShowLayout $showLayout): void
    {
        $showLayout
            ->addSection(trans("formoj::sharp.entities.form"), function(ShowLayoutSection $section) {
                $section
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withSingleField("ref")
                            ->withSingleField("title")
                            ->withSingleField("published_at")
                            ->withSingleField("notifications_strategy");
                    })
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withSingleField("description")
                            ->withSingleField("success_message");
                    });
            })
            ->addEntityListSection("sections")
            ->addEntityListSection("answers");
    }

    public function buildShowConfig(): void
    {
        $this->configureBreadcrumbCustomLabelAttribute("breadcrumb");
    }

    function find($id): array
    {
        return $this
            ->setCustomTransformer("ref", function($value, $form) {
                return "#{$form->id}";
            })
            ->setCustomTransformer("breadcrumb", function($value, $form) {
                return trans("formoj::sharp.entities.form") . " #{$form->id}";
            })
            ->setCustomTransformer("published_at", function($value, $instance) {
                return FormojFormSharpEntityList::publicationDates($instance);
            })
            ->setCustomTransformer("notifications_strategy", function($value, $instance) {
                $label = FormojFormSharpEntityList::notificationStrategies($value);
                if($value != Form::NOTIFICATION_STRATEGY_NONE) {
                    return sprintf("%s (%s)", $label, $instance->administrator_email);
                }
                return $label;
            })
            ->transform(
                Form::findOrFail($id)
            );
    }
}
