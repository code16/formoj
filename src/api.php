<?php

Route::group([
    'prefix' => '/formoj/api',
    'middleware' => [\Illuminate\Routing\Middleware\SubstituteBindings::class],
    'namespace' => 'Code16\Formoj\Controllers'
], function() {

    Route::get('/form/{form}', 'FormojFormController@show');

});