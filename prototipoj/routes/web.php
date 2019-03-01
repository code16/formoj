<?php

Route::get('/', 'FormController@index');
Route::get('/forms/{form}', 'FormController@show');
