<?php

namespace App\Http\Controllers;

use Code16\Formoj\Models\Answer;
use Code16\Formoj\Models\Form;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'forms' => Form::orderBy("title")->get(),
        ]);
    }
}
