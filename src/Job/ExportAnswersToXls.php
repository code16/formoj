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

    /** @var Form */
    protected $form;

    /** @var Collection */
    protected $answers;

    /** @var string */
    protected $fileName;

    /**
     * @param Form $form
     * @param string $fileName
     * @param Collection|null $answers
     */
    public function __construct($form, $fileName, $answers = null)
    {
        $this->form = $form;
        $this->fileName = $fileName;
        $this->answers = $answers ?: $form->answers;
    }

    /**
     * Execute the job.
     *
     * @param Excel $excel
     * @return void
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function handle(Excel $excel)
    {
        $excel->store(
            new AnswersExcelCollection($this->form, $this->answers),
            config("formoj.export.path") . "/{$this->fileName}",
            config("formoj.export.disk")
        );
    }
}