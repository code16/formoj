<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Sharp\Commands\FormojAnswerDownloadFilesCommand;
use Code16\Sharp\Show\Fields\SharpShowEntityListField;
use Code16\Sharp\Show\Fields\SharpShowFileField;
use Code16\Sharp\Show\Fields\SharpShowTextField;
use Code16\Sharp\Show\Layout\ShowLayout;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\SharpShow;
use Code16\Sharp\Utils\Fields\FieldsContainer;

class FormojAnswerSharpShow extends SharpShow
{
    protected function buildShowFields(FieldsContainer $showFields): void
    {
        $showFields
            ->addField(
                SharpShowTextField::make("created_at")
                    ->setLabel(trans("formoj::sharp.answers.list.columns.created_at_label"))
            )
            // This field IS NOT displayed, and is used to handle the custom file DL in the FormojReplySharpEntityList list
            ->addField(
                SharpShowFileField::make("file")
            )
            ->addField(
                SharpShowEntityListField::make("replies", "formoj_reply")
                    ->setLabel(trans("formoj::sharp.answers.fields.replies.label"))
                    ->hideFilterWithValue("formoj_answer", fn($id) => $id)
            );
    }

    protected function buildShowLayout(ShowLayout $showLayout): void
    {
        $showLayout
            ->addSection('', function(ShowLayoutSection $section) {
                $section
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withField("created_at");
                    });
            })
            ->addEntityListSection("replies");
    }

    public function buildShowConfig(): void
    {
        $this->configurePageTitleAttribute("page_title");
    }

    function getInstanceCommands(): ?array
    {
        return [
            FormojAnswerDownloadFilesCommand::class,
        ];
    }

    function find($id): array
    {
        return $this
            ->setCustomTransformer('page_title', fn() => trans("formoj::sharp.entities.answer"))
            ->setCustomTransformer("created_at", function($value, $instance) {
                return $instance->created_at->isoFormat("LLLL");
            })
            ->transform(
                Answer::findOrFail($id)
            );
    }

    public function delete(mixed $id): void
    {
    }
}
