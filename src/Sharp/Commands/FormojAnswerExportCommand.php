<?php

namespace Code16\Formoj\Sharp\Commands;

use Code16\Formoj\Job\ExportAnswersToXls;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Sharp\Filters\FormojSectionFilterHandler;
use Code16\Sharp\EntityList\Commands\EntityCommand;
use Code16\Sharp\EntityList\EntityListQueryParams;
use Maatwebsite\Excel\Excel;

class FormojAnswerExportCommand extends EntityCommand
{
    protected Excel $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }

    public function label(): string
    {
        return trans("formoj::sharp.answers.commands.export");
    }

    public function execute(EntityListQueryParams $params, array $data = []): array
    {
        $formId = $params->filterFor("formoj_form") ?: app(FormojSectionFilterHandler::class)->currentFormId();
        $fileName = uniqid('export') . ".xls";

        ExportAnswersToXls::dispatch(
            Form::findOrFail($formId),
            $fileName
        );

        return $this->download(
            config("formoj.export.path") . "/{$fileName}",
            "form-export.xls",
            config("formoj.export.disk")
        );
    }
}