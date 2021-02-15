<?php

namespace App\Http\Controllers;

use Code16\Formoj\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function show(Answer $answer)
    {
        return view('answer', compact('answer'));
    }
}
