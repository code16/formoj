<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\FormRequest;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Illuminate\Support\Facades\Storage;

class FormojFormFillController
{
    /**
     * @param Form $form
     * @param FormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, FormRequest $request)
    {
        $answer = $form->storeNewAnswer($request->all());

        $this->moveFormUploads($form, $request->all(), $answer);

        return response()->json([
            "message" => $form->success_message ?: trans("formoj::form.success_message")
        ]);
    }

    /**
     * @param Form $form
     * @param array $data
     * @param Answer $answer
     */
    protected function moveFormUploads(Form $form, array $data, Answer $answer)
    {
        collect($data)
            ->filter(function ($value, $key) use ($form) {
                $field = $form->findField(substr($key, 1));

                return $field ? $field->isTypeUpload() : false;
            })
            ->each(function ($value) use ($form, $answer) {
                $filename = $value["file"];

                Storage::disk(config('formoj.storage.disk'))
                    ->writeStream(
                        config('formoj.storage.path') . "/{$form->id}/answers/{$answer->id}/$filename",
                        Storage::disk(config('formoj.upload.disk'))
                            ->readStream(config('formoj.upload.path') . "/{$form->id}/$filename")
                    );
            });
    }
}