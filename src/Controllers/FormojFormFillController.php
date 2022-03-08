<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\FormRequest;
use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FormojFormFillController
{
    public function store(Form $form, FormRequest $request)
    {
        $answer = $form->storeNewAnswer($request->all());

        $this->moveFormUploads($form, $request->all(), $answer);

        return response()->json([
            "answer_id" => $answer->id,
            "message" => $form->success_message
                ? Str::markdown($form->success_message)
                : trans("formoj::form.success_message")
        ]);
    }

    public function update(Form $form, Answer $answer, FormRequest $request)
    {
        $answer->fillWithData($request->all())->save();

        $this->moveFormUploads($form, $request->all(), $answer);

        return response()->json([
            "answer_id" => $answer->id,
            "message" => $form->success_message
                ? Str::markdown($form->success_message)
                : trans("formoj::form.success_message")
        ]);
    }
    
    protected function moveFormUploads(Form $form, array $data, Answer $answer)
    {
        collect($data)
            ->filter(function ($value, $key) use ($form) {
                if(!is_array($value)) {
                    return false;
                }

                if(!($value["uploaded"] ?? false)) {
                    return false;
                }
                
                $field = $form->findField(substr($key, 1));

                return  $field && $field->isTypeUpload();
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
