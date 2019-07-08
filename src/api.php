<?php

Route::group([
    'prefix' => config("formoj.base_url"),
    'middleware' => [
//        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
//        \Illuminate\Session\Middleware\StartSession::class,
//        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class
    ],
    'namespace' => 'Code16\Formoj\Controllers'
], function() {

    Route::get('/form/{form}', 'FormojFormController@show');

    Route::post('/form/{form}/validate/{section}', 'FormojSectionController@update');

    Route::post('/form/{form}', 'FormojFormFillController@store');

    Route::post('/form/{form}/upload/${field}');

});