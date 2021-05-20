<?php

use Code16\Formoj\Controllers\FormojAnswerController;
use Code16\Formoj\Controllers\FormojFormController;
use Code16\Formoj\Controllers\FormojFormFillController;
use Code16\Formoj\Controllers\FormojSectionController;
use Code16\Formoj\Controllers\FormojUploadController;

Route::group([
    'prefix' => config("formoj.base_url"),
    'middleware' => [
        \Illuminate\Routing\Middleware\SubstituteBindings::class
    ],
], function() {
    Route::get('/form/{form}', [FormojFormController::class, 'show']);
    Route::post('/form/{form}/validate/{section}', [FormojSectionController::class, 'update']);
    Route::post('/form/{form}', [FormojFormFillController::class, 'store']);
    Route::post('/form/{form}/answer/{answer}', [FormojFormFillController::class, 'update']);
    Route::post('/form/{form}/upload/{field}', [FormojUploadController::class, 'store']);
    Route::get('/answer/{answer}', [FormojAnswerController::class, 'show']);
});
