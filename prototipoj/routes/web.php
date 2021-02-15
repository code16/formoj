<?php

Route::get('/', 'HomeController@index');
Route::get('/forms/{form}', 'FormController@show');
Route::get('/answers/{answer}', 'AnswerController@show');
