<?php

namespace Code16\Formoj\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !$this->form->isNotPublishedYet()
            && !$this->form->isNoMorePublished()
            && $this->field->isTypeUpload();
    }

    public function rules(): array
    {
        return [
            "file" => array_merge(
                [
                    "file",
                    "max:" . ($this->field->fieldAttribute("max_size") * 1024)
                ], 
                $this->field->fieldAttribute("accept")
                    ? ["mimes:" . $this->formatExtensions($this->field->fieldAttribute("accept"))]
                    : []
            )
        ];
    }

    /**
     * @param string $extensions
     * @return array
     */
    private function formatExtensions($extensions)
    {
        return collect(explode(",", $extensions))
            ->map(function($extension) {
                return substr($extension, 1);
            })
            ->implode(",");
    }
}