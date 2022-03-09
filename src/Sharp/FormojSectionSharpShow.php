<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Section;
use Code16\Sharp\Show\Fields\SharpShowEntityListField;
use Code16\Sharp\Show\Fields\SharpShowTextField;
use Code16\Sharp\Show\Layout\ShowLayout;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\SharpShow;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class FormojSectionSharpShow extends SharpShow
{

    protected function buildShowFields(FieldsContainer $showFields): void
    {
        $showFields
            ->addField(
                SharpShowTextField::make("title")
                    ->setLabel(trans("formoj::sharp.sections.fields.title.label"))
            )
            ->addField(
                SharpShowTextField::make("description")
                    ->setLabel(trans("formoj::sharp.sections.fields.description.label"))
            )
            ->addField(
                SharpShowEntityListField::make("fields", "formoj_field")
                    ->setLabel(trans("formoj::sharp.sections.fields.fields.label"))
                    ->hideFilterWithValue("formoj_section", function($instanceId) {
                        return $instanceId;
                    })
            );
    }

    protected function buildShowLayout(ShowLayout $showLayout): void
    {
        $showLayout
            ->addSection(trans("formoj::sharp.entities.section"), function(ShowLayoutSection $section) {
                $section
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withSingleField("title");
                    })
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withSingleField("description");
                    });;
            })
            ->addEntityListSection("fields");
    }

    public function buildShowConfig(): void
    {
        $this->configureBreadcrumbCustomLabelAttribute("breadcrumb");
    }

    function find($id): array
    {
        return $this
            ->setCustomTransformer("breadcrumb", function($value, $instance) {
                return $instance->title;
            })
            ->setCustomTransformer("title", function($value, $instance) {
                return FormojSectionSharpEntityList::transformTitle($instance);
            })
            ->transform(
                Section::findOrFail($id)
            );
    }
}
