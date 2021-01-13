<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Sharp\EntityList\Containers\EntityListDataContainer;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormojReplySharpEntityList extends SharpEntityList
{

    function buildListDataContainers(): void
    {
        $this
            ->addDataContainer(
                EntityListDataContainer::make("label")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.label_label"))
            )
            ->addDataContainer(
                EntityListDataContainer::make("value")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.value_label"))
            );
    }

    function buildListLayout(): void
    {
        $this->addColumn("label", 3, 5)
            ->addColumn("value", 9, 7);
    }

    function buildListConfig(): void
    {
    }

    function getListData(EntityListQueryParams $params)
    {
        if(!$answer = Answer::find($params->filterFor("formoj_answer"))) {
            return [];
        }

        return $this
            ->setCustomTransformer("label", function($value, $instance) {
                return $instance->label;
            })
            ->setCustomTransformer("value", function($value, $instance) {
                return $instance->value;
            })
            ->transform(
                collect($answer->content)
                    ->map(function($value, $label) {
                        return (object)[
                            "id" => uniqid(),
                            "label" => $label,
                            "value" => $this->formatValue($value, $label)
                        ];
                    })
                    ->values()
            );
    }

    private function formatValue($value, $label): string
    {
        if(is_array($value)) {
            return collect($value)
                ->map(function($item) {
                    return "- {$item}";
                })
                ->implode("<br>");
        }

        if($value !== null && $this->seemsToBeAFile($value)) {
            return $this->buildSharpDownloadFileLink($value);
        }

        return $value ?: "";
    }

    private function seemsToBeAFile(string $value): bool
    {
        if(preg_match('/^.+\.[A-Za-z]{3}$/U', $value)) {
            if(($formId = $this->getCurrentFormId()) && ($answerId = $this->getCurrentAnswerId())) {
                return Storage::disk(config("formoj.storage.disk"))
                    ->exists(
                        sprintf(
                            "%s/%s/answers/%s/%s",
                            config("formoj.storage.path"),
                            $formId,
                            $answerId,
                            $value
                        )
                    );
            }
            
            return false;
        }

        return false;
    }

    protected function getCurrentFormId(): ?string
    {
        if($formShow = currentSharpRequest()->getPreviousShowFromBreadcrumbItems("formoj_form")) {
            return $formShow->instanceId();
        }
        
        return null;
    }

    protected function getCurrentAnswerId(): ?string
    {
        if($answerShow = currentSharpRequest()->getPreviousShowFromBreadcrumbItems("formoj_answer")) {
            return $answerShow->instanceId();
        }
        
        return null;
    }

    protected function buildSharpDownloadFileLink(string $fileName): string
    {
        return sprintf(
            '<a href="%s">%s</a>',
            route("code16.sharp.api.show.download", [
                "fieldKey" => "file",
                "entityKey" => "formoj_answer",
                "instanceId" => $this->getCurrentAnswerId(),
                "fileName" => $fileName
            ]),
            $fileName
        );
    }
}