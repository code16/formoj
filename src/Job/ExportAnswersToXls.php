<?php

namespace Code16\Formoj\Job;

use Code16\Formoj\Job\Utils\AnswersExcelCollection;
use Code16\Formoj\Models\Form;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Excel;

class ExportAnswersToXls
{
    use Dispatchable;

    protected Form $form;
    protected Collection $answers;
    protected string $fileName;

    public function __construct(Form $form, string $fileName, ?Collection $answers = null)
    {
        $this->form = $form;
        $this->fileName = $fileName;
        $this->answers = $answers ?: $form->answers;
    }

    public function handle(Excel $excel)
    {
        $excel->store(
            new AnswersExcelCollection($this->form, $this->answers),
            config("formoj.export.path") . "/{$this->fileName}",
            config("formoj.export.disk")
        );
    }
}