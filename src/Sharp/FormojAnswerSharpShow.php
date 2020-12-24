<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Sharp\Commands\FormojAnswerDownloadFilesCommand;
use Code16\Sharp\Form\Eloquent\Uploads\Transformers\SharpUploadModelFormAttributeTransformer;
use Code16\Sharp\Show\Fields\SharpShowEntityListField;
use Code16\Sharp\Show\Fields\SharpShowFileField;
use Code16\Sharp\Show\Fields\SharpShowListField;
use Code16\Sharp\Show\Fields\SharpShowTextField;
use Code16\Sharp\Show\Layout\ShowLayoutColumn;
use Code16\Sharp\Show\Layout\ShowLayoutSection;
use Code16\Sharp\Show\SharpShow;

class FormojAnswerSharpShow extends SharpShow
{

    function buildShowFields(): void
    {
        $this
            ->addField(
                SharpShowTextField::make("created_at")
                    ->setLabel(trans("formoj::sharp.answers.list.columns.created_at_label"))
            )
            // This field IS NOT displayed, and is used to handle the custom file DL in the FormojReplySharpEntityList list
            ->addField(
                SharpShowFileField::make("file")
                    ->setStorageDisk(config("formoj.storage.disk"))
                    ->setStorageBasePath(
                        sprintf(
                            "%s/%s/answers/{id}",
                            config("formoj.storage.path"),
                            currentSharpRequest()->getPreviousShowFromBreadcrumbItems("formoj_form")->instanceId()
                        )
                    )
            )
            ->addField(
                SharpShowEntityListField::make("replies", "formoj_reply")
                    ->setLabel(trans("formoj::sharp.answers.fields.replies.label"))
                    ->hideFilterWithValue("formoj_answer", function($instanceId) {
                        return $instanceId;
                    })
            );
    }

    function buildShowLayout(): void
    {
        $this
            ->addSection(trans("formoj::sharp.entities.answer"), function(ShowLayoutSection $section) {
                $section
                    ->addColumn(6, function(ShowLayoutColumn $column) {
                        $column
                            ->withSingleField("created_at");
                    });
            })
            ->addEntityListSection("replies");
    }
    
    public function buildShowConfig(): void
    {
        $this->addInstanceCommand("download_answer_files", FormojAnswerDownloadFilesCommand::class);
    }

    function find($id): array
    {
        return $this
            ->setCustomTransformer("created_at", function($value, $instance) {
                return $instance->created_at->isoFormat("LLLL");
            })
            ->transform(
                Answer::findOrFail($id)
            );
    }
}