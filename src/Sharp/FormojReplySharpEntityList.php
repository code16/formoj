<?php

namespace Code16\Formoj\Sharp;

use Code16\Formoj\Models\Answer;
use Code16\Sharp\EntityList\Fields\EntityListField;
use Code16\Sharp\EntityList\Fields\EntityListFieldsContainer;
use Code16\Sharp\EntityList\Filters\HiddenFilter;
use Code16\Sharp\EntityList\SharpEntityList;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Storage;

class FormojReplySharpEntityList extends SharpEntityList
{

    public function buildList(EntityListFieldsContainer $fields): void
    {
        $fields
            ->addField(
                EntityListField::make("label")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.label_label"))
            )
            ->addField(
                EntityListField::make("value")
                    ->setLabel(trans("formoj::sharp.replies.list.columns.value_label"))
            );
    }

    protected function getFilters(): ?array
    {
        return [
            HiddenFilter::make("formoj_answer")
        ];
    }

    public function getListData(): array|Arrayable
    {
        if(!$answer = Answer::find($this->queryParams->filterFor("formoj_answer"))) {
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
        if($formShow = sharp()->context()->breadcrumb()->previousShowSegment("formoj_form")) {
            return $formShow->instanceId();
        }

        return null;
    }

    protected function getCurrentAnswerId(): ?string
    {
        if($answerShow = sharp()->context()->breadcrumb()->previousShowSegment("formoj_answer")) {
            return $answerShow->instanceId();
        }

        return null;
    }

    protected function buildSharpDownloadFileLink(string $fileName): string
    {
        return sprintf(
            '<a href="%s">%s</a>',
            route("code16.sharp.api.download.show", [
                "entityKey" => "formoj_answer",
                "instanceId" => $this->getCurrentAnswerId(),
                "path" => sprintf(
                    "%s/%s/answers/%s/%s",
                    config("formoj.storage.path"),
                    $this->getCurrentFormId(),
                    $this->getCurrentAnswerId(),
                    $fileName
                ),
                "disk" => config("formoj.storage.disk"),
            ]),
            $fileName
        );
    }
}
