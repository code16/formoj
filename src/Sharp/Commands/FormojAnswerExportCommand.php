<?php

namespace Code16\Formoj\Sharp\Commands;

use Code16\Formoj\Job\ExportAnswersToXls;
use Code16\Formoj\Models\Form;
use Code16\Sharp\EntityList\Commands\EntityCommand;
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

    public function execute(array $data = []): array
    {
        $formId = $this->queryParams->filterFor("formoj_form");
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
