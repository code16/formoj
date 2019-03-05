<?php

namespace Code16\Formoj\Controllers;

use Code16\Formoj\Controllers\Requests\FormRequest;
use Code16\Formoj\Models\Form;

class FormojFormFillController
{
    /**
     * @param Form $form
     * @param FormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Form $form, FormRequest $request)
    {
        $form->storeNewAnswer($request->all());

        return response()->json([
            "message" => $form->success_message ?: trans("formoj::form.success_message")
        ]);
    }
}