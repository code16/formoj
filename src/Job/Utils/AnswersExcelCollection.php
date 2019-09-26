<?php

namespace Code16\Formoj\Job\Utils;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Field;
use Code16\Formoj\Models\Form;
use Code16\Formoj\Models\Section;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class AnswersExcelCollection extends DefaultValueBinder implements WithCustomValueBinder, FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting
{
    /** @var Form */
    protected $form;

    /** @var Collection */
    protected $answers;

    /**
     * @param Form $form
     * @param $answers
     */
    public function __construct(Form $form, $answers)
    {
        $this->form = $form;
        $this->answers = $answers;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return array_merge(
            ["Date"],
            $this->form->sections
                ->map(function(Section $section) {
                    return $section->fields->filter(function(Field $field) {
                        return !$field->isTypeHeading();
                    });
                })
                ->map(function($fields) {
                    return $fields->pluck("identifier");
                })
                ->flatten()
                ->all()
        );
    }

    /**
     * @return array
     */
    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $missingAnswers = collect($this->headings())
            ->mapWithKeys(function($key) {
                return [$key => ""];
            })
            ->all();

        return $this->answers
            ->map(function (Answer $answer) use($missingAnswers) {
                return array_merge(
                    $missingAnswers,
                    ["Date" => $answer->created_at],
                    $answer->content
                );
            });
    }

    /**
     * Bind value to a cell.
     *
     * @param Cell $cell Cell to bind value to
     * @param mixed $value Value to bind in cell
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function bindValue(Cell $cell, $value)
    {
        if (is_array($value)) {
            // Multiselect case
            $cell->setValueExplicit(implode("\n", $value), DataType::TYPE_STRING);

            return true;
        }

        return parent::bindValue($cell, $value);
    }
}