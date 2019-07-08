<?php

namespace Code16\Formoj\Controllers\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return !$this->form->isNotPublishedYet()
            && !$this->form->isNoMorePublished()
            && $this->field->isTypeUpload();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "file" => array_merge([
                "file",
                "max:" . ($this->field->fieldAttribute("max_size") * 1024)
            ], $this->field->fieldAttribute("accept")
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