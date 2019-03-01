<?php

namespace App\Http\Controllers;

use Code16\Formoj\Models\Form;

class FormController extends Controller
{
    public function index()
    {
        return view('forms', ['forms' => Form::orderBy("title")->get()]);
    }

    public function show(Form $form)
    {
        return view('form', compact('form'));
    }
}
